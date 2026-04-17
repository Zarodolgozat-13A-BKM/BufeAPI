# BufeAPI

Backend API a Jedlik iskolai büfé rendelési rendszeréhez. A projekt Laravel 12-re épül, és az alábbiakat biztosítja:

- LDAP alapú bejelentkezés + Sanctum tokenes azonosítás
- Termék, kategória, rendelés és státusz kezelés policy alapú jogosultságkezeléssel
- Stripe checkout + webhook feldolgozás
- PDF nyugta generálás és e-mail küldés
- Valós idejű rendelésfrissítések Laravel Reverb csatornákon
- OpenAPI dokumentáció generálás Scramble segítségével

## Technológiai stack

- PHP 8.2+ (projektkövetelmény), Laravel 12
- MySQL (alapértelmezett), SQLite tesztekhez
- Laravel Sanctum
- LdapRecord Laravel
- Stripe PHP SDK
- Laravel Reverb (WebSocket alapú broadcast)
- DomPDF (PDF nyugta generálás)
- Pest + PHPUnit
- Vite/Tailwind eszközkészlet (a Laravel minimális frontend toolingja)

## Projekt felépítése

- `app/Http/Controllers`: API kontrollerek (auth, items, orders, payment, categories, statuses)
- `app/Models`: Domain modellek és kapcsolatok
- `app/Policies`: Jogosultsági szabályok
- `app/Observers`: Készlet- és rendelés oldali mellékhatások
- `app/Services`: Stripe, nyugta és csengetési rend integrációk
- `app/Events`: Broadcastolt rendelés események
- `routes/api.php`: Fő REST API útvonalak
- `routes/channels.php`: Privát broadcast csatorna jogosultságok
- `database/seeders`: Demo/teszt adatok seederei
- `deployment.yaml`: Kubernetes deployment/service/ingress leírások

## Fő funkciók

### Hitelesítés

- `POST /api/account/login` LDAP hitelesítéssel jelentkeztet be.
- Sikeres bejelentkezés esetén Sanctum bearer token érkezik válaszként.
- A `GET /api/account/me`, `GET /api/account/details`, `POST /api/account/logout` végpontokat `auth:sanctum` védi.

### Katalóguskezelés

- A termékek és kategóriák védett végpontokon érhetők el.
- A termék/kategória/státusz létrehozás, módosítás és törlés admin-only policykkel védett.
- A termék observer automatikusan inaktiválja a terméket alacsony készletnél (`inventory_count <= 2`).

### Rendelések

- Checkout során létrejön a rendelés és a hozzá tartozó rendelés-tételek.
- Az OrderItem observer a tételek létrehozásakor csökkenti a készletet.
- Szerepkör alapú láthatóság:
    - Admin minden rendelést lát.
    - Normál felhasználó csak a saját rendeléseit látja.

### Fizetés és nyugta

- Nem készpénzes checkout esetén Stripe payment intent jön létre.
- Stripe webhook frissíti a rendelés státuszát (`Fizetve` / `Törölve` útvonal a kódban).
- Sikeres fizetésnél nyugta e-mail kerül kiküldésre.
- A PDF nyugta Blade nézetből készül.

### Valós idejű frissítések

- A Reverb az order model eseményeit az alábbi csatornákra broadcastolja:
    - `orders_admin`
    - `ordersOfUser.{base64(email)}`
- Emellett külön események is vannak rendelés beküldésre/státuszváltásra.

## API útvonalak

Alap elérési út: `/api`

### Fiók

- `POST /account/login`
- `GET /account/me` (auth)
- `POST /account/logout` (auth)
- `GET /account/details` (auth)
- `POST /account/is-token-still-valid`

### Termékek (mind auth)

- `GET /items`
- `GET /items/{item}`
- `POST /items` (admin)
- `PATCH /items/{item}` (admin)
- `DELETE /items/{item}` (admin)
- `POST /items/{item}/toggle-active` (admin)
- `POST /items/{item}/toggle-featured` (admin)

### Rendelések (mind auth)

- `GET /orders`
- `GET /orders/active`
- `GET /orders/breaks/{date?}`
- `GET /orders/{order}`
- `PATCH /orders/{order}` (admin)

### Kategóriák (mind auth)

- `GET /categories`
- `POST /categories` (admin)
- `GET /categories/{category}`
- `PATCH /categories/{category}` (admin)
- `DELETE /categories/{category}` (admin)

### Fizetés

- `POST /payment/checkout` (auth)
- `GET /payment/stripe-key` (auth)
- `POST /payment/webhook` (Stripe callback, Sanctum nélkül)

### Státuszok (mind auth)

- `GET /statuses`
- `POST /statuses` (admin)
- `GET /statuses/{status}`
- `DELETE /statuses/{status}` (admin)

## Endpoint sémák (mit várnak, mit adnak vissza)

Megjegyzések:

- A védett végpontoknál kötelező fejléc: `Authorization: Bearer <token>`.
- Validációs hiba esetén Laravel alapú `422 Unprocessable Entity` válasz érkezik.
- Jogosultsági hiba esetén tipikusan `401` vagy `403` válasz jön.

### Fiók végpontok

#### POST /api/account/login

Elvárt body:

```json
{
    "username": "string, kötelező",
    "password": "string, kötelező"
}
```

Sikeres válasz (200):

```json
{
    "access_token": "string"
}
```

Hibás hitelesítés (401):

```json
{
    "message": "Érvénytelen bejelentkezési adatok"
}
```

#### GET /api/account/me

Elvárás: hitelesített felhasználó.

Válasz (200):

```json
{
    "full_name": "string",
    "email": "string",
    "role": "string|null"
}
```

#### GET /api/account/details

Elvárás: hitelesített felhasználó.

Válasz (200, UserResource):

```json
{
    "full_name": "string",
    "orders": ["array - Order model elemek"]
}
```

#### POST /api/account/logout

Elvárás: hitelesített felhasználó.

Válasz (200):

```json
{
    "message": "Sikeresen kijelentkeztél!"
}
```

#### POST /api/account/is-token-still-valid

Elvárás: nincs kötelező body.

Válasz (200):

```json
{
    "valid": true
}
```

### Termék végpontok

#### GET /api/items

Elvárás: hitelesített felhasználó.

Válasz (200):

```json
{
    "items": [
        {
            "id": 1,
            "name": "string",
            "description": "string|null",
            "price": 500,
            "is_active": true,
            "default_time_to_deliver": 10,
            "is_featured": false,
            "category_id": 1,
            "picture_url": "string",
            "inventory_count": 20
        }
    ]
}
```

Megjegyzés: admin és nem admin felhasználóknál a mezők eltérhetnek (`inventory_count` csak admin oldalon jelenik meg az ItemResource alapján).

#### GET /api/items/{item}

Elvárás: hitelesített felhasználó.

Válasz (200):

```json
{
    "item": {
        "id": 1,
        "name": "string",
        "description": "string|null",
        "price": 500,
        "picture_url": "string",
        "is_active": true,
        "is_featured": false,
        "default_time_to_deliver": 10,
        "category_id": 1,
        "inventory_count": 20
    }
}
```

#### POST /api/items (admin)

Elvárt body (`multipart/form-data`):

```json
{
    "name": "string, kötelező",
    "image": "file, opcionális",
    "description": "string|null, opcionális",
    "price": "number >= 0, kötelező",
    "is_active": "boolean, kötelező",
    "default_time_to_deliver": "integer >= 0, kötelező",
    "category_id": "existing categories.id, kötelező",
    "is_featured": "boolean, opcionális",
    "inventory_count": "integer >= 0, opcionális"
}
```

Válasz (201, ItemResource):

```json
{
    "id": 1,
    "name": "string",
    "description": "string|null",
    "price": 500,
    "is_active": true,
    "default_time_to_deliver": 10,
    "category_id": 1,
    "is_featured": false,
    "picture_url": "string",
    "inventory_count": 20
}
```

#### PATCH /api/items/{item} (admin)

Elvárt body (minden mező opcionális, de ha szerepel, valid):

```json
{
    "name": "string",
    "image": "file|null",
    "description": "string|null",
    "price": "number >= 0",
    "is_active": "boolean",
    "default_time_to_deliver": "integer >= 0",
    "category_id": "existing categories.id",
    "is_featured": "boolean",
    "inventory_count": "integer >= 0"
}
```

Válasz (200, ItemResource): az előzőhöz hasonló objektum.

#### DELETE /api/items/{item} (admin)

Válasz (200):

```json
{
    "message": "Termék sikeresen törölve"
}
```

#### POST /api/items/{item}/toggle-active (admin)

Elvárás: nincs body.

Válasz (200):

```json
{
    "message": "Termék státusza sikeresen frissítve",
    "item": {
        "id": 1,
        "is_active": false
    }
}
```

#### POST /api/items/{item}/toggle-featured (admin)

Elvárás: nincs body.

Válasz (200):

```json
{
    "message": "Termék státusza sikeresen frissítve",
    "item": {
        "id": 1,
        "is_featured": true
    }
}
```

### Kategória végpontok

#### GET /api/categories

Elvárás: hitelesített felhasználó.

Válasz (200, CategoryResource kollekció):

```json
[
    {
        "id": 1,
        "name": "Ételek",
        "items": ["ItemResource[]"]
    }
]
```

Megjegyzés: nem admin felhasználónál csak aktív itemek jelennek meg a kategóriában.

#### POST /api/categories (admin)

Elvárt body:

```json
{
    "name": "string, kötelező"
}
```

Válasz (201):

```json
{
    "message": "Kategória sikeresen létrehozva",
    "category": {
        "id": 1,
        "name": "string",
        "items": []
    }
}
```

#### GET /api/categories/{category}

Válasz (200, CategoryResource):

```json
{
    "id": 1,
    "name": "string",
    "items": ["ItemResource[]"]
}
```

#### PATCH /api/categories/{category} (admin)

Elvárt body:

```json
{
    "name": "string, kötelező"
}
```

Válasz (200):

```json
{
    "message": "Kategória sikeresen frissítve",
    "category": {
        "id": 1,
        "name": "string",
        "items": ["ItemResource[]"]
    }
}
```

#### DELETE /api/categories/{category} (admin)

Válasz (200):

```json
{
    "message": "Kategória sikeresen törölve"
}
```

### Státusz végpontok

#### GET /api/statuses

Válasz (200):

```json
[
    {
        "id": 1,
        "name": "Fizetésre vár"
    }
]
```

#### POST /api/statuses (admin)

Elvárt body:

```json
{
    "name": "string, kötelező"
}
```

Válasz (201, StatusResource):

```json
{
    "id": 1,
    "name": "string"
}
```

#### GET /api/statuses/{status}

Válasz (200, StatusResource):

```json
{
    "id": 1,
    "name": "string"
}
```

#### DELETE /api/statuses/{status} (admin)

Válasz: `204 No Content`.

### Rendelés végpontok

#### GET /api/orders

Elvárás: hitelesített felhasználó.

Válasz (200, OrderResource kollekció):

```json
[
    {
        "id": 12,
        "order_identifier_number": 45,
        "user_username": "string",
        "status": "Fizetve",
        "delivery_date": "2026-04-03",
        "items": [
            {
                "item_id": 1,
                "item_name": "string",
                "item_price": 500,
                "picture_url": "string",
                "quantity": 2,
                "price": 1000
            }
        ],
        "total_price": 1000,
        "default_completion_time": 20,
        "comment": "string|null",
        "payment_intent_id": "pi_xxx|null"
    }
]
```

#### GET /api/orders/active

Válasz: azonos séma, mint a `GET /api/orders`, de csak aktív státuszú rendelések.

#### GET /api/orders/{order}

Válasz: egyetlen `OrderResource` objektum.

#### PATCH /api/orders/{order} (admin)

Elvárt body:

```json
{
    "status_id": "existing statuses.id, opcionális",
    "delivery_date": "datetime, opcionális"
}
```

Válasz (200):

```json
{
    "message": "Rendelés sikeresen frissítve",
    "order": {
        "id": 12,
        "status": "Fizetve"
    }
}
```

#### GET /api/orders/breaks/{date?}

Elvárás: opcionális `date` paraméter, formátum: `YYYY-MM-DD`.

Válasz (200):

```json
{
    "date": "2026-04-03",
    "breaks": [
        {
            "start": "09:45",
            "end": "10:00"
        }
    ]
}
```

### Fizetés végpontok

#### GET /api/payment/stripe-key

Elvárás: hitelesített felhasználó.

Válasz (200):

```json
{
    "key": "pk_live_or_test..."
}
```

#### POST /api/payment/checkout

Elvárt body:

```json
{
    "delivery_date": "date, opcionális",
    "items": [
        {
            "item_id": "existing items.id, kötelező",
            "quantity": "integer >= 1, kötelező"
        }
    ],
    "comment": "string <= 255, opcionális",
    "cash": "boolean, kötelező"
}
```

Válasz (200):

```json
{
    "client_secret": "string|null",
    "order": {
        "id": 12,
        "order_identifier_number": 45,
        "user_username": "string",
        "status": "Fizetésre vár",
        "delivery_date": "2026-04-03",
        "items": ["OrderItemResource[]"],
        "total_price": 1000,
        "default_completion_time": 20,
        "comment": "string|null",
        "payment_intent_id": "pi_xxx|null"
    }
}
```

Megjegyzés:

- Ha `cash = true`, akkor a `client_secret` jellemzően `null`.
- A backend minimum fizetendő összeget alkalmazhat (a jelenlegi implementációban legalább 200 HUF, majd ez centesített formában kerül Stripe felé).

#### POST /api/payment/webhook

Elvárás:

- Stripe által küldött raw payload
- `Stripe-Signature` fejléc

Sikeres válasz (200):

```json
{
    "status": "success"
}
```

Ismeretlen event típus (400):

```json
{
    "message": "Unhandled event type"
}
```

## Lokális fejlesztői beállítás

### 1. Követelmények

- PHP 8.2+
- Composer
- Node.js 18+
- MySQL (vagy más, megfelelően konfigurált adatbázis)

### 2. Függőségek telepítése

```bash
composer install
npm install
```

### 3. Környezet konfigurálása

```bash
cp .env.example .env
php artisan key:generate
```

Állítsd be a szükséges értékeket a `.env` fájlban:

- `APP_URL`
- `DB_*`
- `LDAP_*`
- `STRIPE_KEY`, `STRIPE_SECRET`, `STRIPE_WEBHOOK_SECRET`
- `MAIL_*`
- `REVERB_APP_ID`, `REVERB_APP_KEY`, `REVERB_APP_SECRET`
- `REVERB_HOST`, `REVERB_PORT`, `REVERB_SCHEME`
- `JCS_URL`
- `FRONTEND_URL`

### 4. Adatbázis

```bash
php artisan migrate
php artisan db:seed
```

### 5. Storage link

```bash
php artisan storage:link
```

### 6. Alkalmazás futtatása

A opció: a composer dev script használata (API szerver + queue listener + Vite):

```bash
composer run dev
```

B opció: szolgáltatások indítása kézzel:

```bash
php artisan serve
php artisan queue:listen --tries=1
php artisan reverb:start
npm run dev
```

## Tesztelés

Tesztek futtatása:

```bash
composer test
```

vagy

```bash
php artisan test
```

A jelenlegi tesztlefedettség tartalmazza az `AuthController` feature szintű működését (validáció, hibás hitelesítés, token generálás, logout, profil végpontok).

## API dokumentáció (Scramble)

A Scramble telepítve van (`dedoc/scramble`), és az `/api` útvonalakra van konfigurálva.

- Dokumentáció UI: `/docs/api`
- Export útvonal: `/api.json`

Ha a dokumentáció korlátozott a környezetedben, állítsd a middleware-t a `config/scramble.php` fájlban.

## Broadcasting / Reverb megjegyzések

- Az elvárt broadcast driver: `reverb`.
- A privát csatorna jogosultságok a `routes/channels.php` fájlban vannak.
- A WebSocket host és app hitelesítő adatok környezeti változókból jönnek.

## Seed adatok

A `php artisan db:seed` futtatja:

- `UserSeeder`
- `StatusSeeder`
- `CategorySeeder`
- `ItemSeeder`
- `OrderSeeder`
- `OrderItemSeeder`

Ez demo felhasználókat, státuszokat, kategóriákat, termékeket, rendeléseket és rendelés-tétel rekordokat hoz létre.

## Telepítés (Docker + Kubernetes)

- A Dockerfile PHP-FPM alapú image-et épít, telepíti a függőségeket és kiterjesztéseket (`pdo_mysql`, `ldap`, `sockets`, stb.), majd az alkalmazás indulását az `entrypoint.sh` kezeli.
- Az `entrypoint.sh` cache-eli a Laravel config/route/view elemeket, létrehozza a storage symlinket, elindítja a Reverbet, majd a Laravel HTTP szervert.
- A `deployment.yaml` tartalmaz:
    - ConfigMap nem titkos környezeti változókhoz
    - Deployment image pull secrettel
    - ClusterIP service-eket HTTP-hez és WebSockethez
    - TLS tanúsítványt és ingress szabályokat API és WS hostokhoz

## Gyakori hibák és megoldások

- Sikertelen bejelentkezés:
    - Ellenőrizd az LDAP hostot, bind DN-t, jelszót és base DN-t.
- `401 Unauthorized` védett végpontokon:
    - Ellenőrizd, hogy az `Authorization: Bearer <token>` fejléc valóban elküldésre kerül.
- Stripe webhook problémák:
    - Ellenőrizd a `STRIPE_WEBHOOK_SECRET` értéket és az aláírás továbbítását.
- Nem érkezik nyugta e-mail:
    - Ellenőrizd az SMTP hitelesítést és feladó beállításokat.
- Reverb csatlakozási problémák:
    - Ellenőrizd a `REVERB_*` változókat és az ingress/service routingot a WebSocket hosthoz.

## Hasznos parancsok

```bash
php artisan route:list
php artisan optimize:clear
php artisan config:cache
php artisan reverb:start
php artisan queue:listen --tries=1
```

## Licenc

A projekt Laravel alapokra épül, MIT licenc alatt.
