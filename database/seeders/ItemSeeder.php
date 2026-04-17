<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Item;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $generateDescriptionBasedOnCategory = function ($categoryName): string {
            return match ($categoryName) {
                'Ételek' => 'Friss és ízletes szendvicsek, hamburgerek és melegszendvicsek széles választéka.',
                'Péksütemények' => 'Frissen sült zsemlék, és egyéb péksütemények minden ízléshez.',
                'Édességek' => 'Nassolnivalók széles választéka, a klasszikus csokiktól a müzliszeletekig.',
                'Snackek' => 'Rágcsálnivalók, amelyek tökéletesek egy gyors harapásra.',
                'Italok' => 'Frissítő italok, meleg teák és kávék minden alkalomra.',
                default => "Megéri megkóstolni!",
            };
        };
        Item::create(['name' => 'Mr. Big szendvics', 'is_featured' => true, 'description' => $generateDescriptionBasedOnCategory('Ételek'), 'default_time_to_deliver' => 15, 'price' => 800, 'category_id' => Category::where('name', 'Ételek')->first()->id]);
        Item::create(['name' => 'Szendvics', 'is_featured' => false, 'description' => $generateDescriptionBasedOnCategory('Ételek'), 'default_time_to_deliver' => 10, 'price' => 500, 'category_id' => Category::where('name', 'Ételek')->first()->id]);
        Item::create(['name' => 'Teljes kiőrlésű szendvics', 'is_featured' => false, 'description' => $generateDescriptionBasedOnCategory('Ételek'), 'default_time_to_deliver' => 12, 'price' => 600, 'category_id' => Category::where('name', 'Ételek')->first()->id]);
        Item::create(['name' => 'Ciabatta szendvics', 'is_featured' => false, 'description' => $generateDescriptionBasedOnCategory('Ételek'), 'default_time_to_deliver' => 10, 'price' => 700, 'category_id' => Category::where('name', 'Ételek')->first()->id]);
        Item::create(['name' => 'Fincsi szendvics', 'is_featured' => false, 'description' => $generateDescriptionBasedOnCategory('Ételek'), 'default_time_to_deliver' => 10, 'price' => 700, 'category_id' => Category::where('name', 'Ételek')->first()->id]);
        Item::create(['name' => 'Háromszög szendvics', 'is_featured' => true, 'description' => $generateDescriptionBasedOnCategory('Ételek'), 'default_time_to_deliver' => 10, 'price' => 700, 'category_id' => Category::where('name', 'Ételek')->first()->id]);
        Item::create(['name' => 'Meleg szendvics - sonkás', 'is_featured' => false, 'description' => $generateDescriptionBasedOnCategory('Ételek'), 'default_time_to_deliver' => 15, 'price' => 1100, 'category_id' => Category::where('name', 'Ételek')->first()->id]);
        Item::create(['name' => 'Meleg szendvics - gombás', 'is_featured' => true, 'description' => $generateDescriptionBasedOnCategory('Ételek'), 'default_time_to_deliver' => 15, 'price' => 1100, 'category_id' => Category::where('name', 'Ételek')->first()->id]);
        Item::create(['name' => 'Meleg szendvics - baconös', 'is_featured' => true, 'description' => $generateDescriptionBasedOnCategory('Ételek'), 'default_time_to_deliver' => 15, 'price' => 1200, 'category_id' => Category::where('name', 'Ételek')->first()->id]);
        Item::create(['name' => 'Classic burger', 'is_featured' => false, 'description' => $generateDescriptionBasedOnCategory('Ételek'), 'default_time_to_deliver' => 10, 'price' => 950, 'category_id' => Category::where('name', 'Ételek')->first()->id]);
        Item::create(['name' => 'Sajtos burger', 'is_featured' => true, 'description' => $generateDescriptionBasedOnCategory('Ételek'), 'default_time_to_deliver' => 10, 'price' => 1050, 'category_id' => Category::where('name', 'Ételek')->first()->id]);
        Item::create(['name' => 'Dupla húsos burger', 'is_featured' => true, 'description' => $generateDescriptionBasedOnCategory('Ételek'), 'default_time_to_deliver' => 15, 'price' => 1600, 'category_id' => Category::where('name', 'Ételek')->first()->id]);
        Item::create(['name' => 'Hot dog', 'is_featured' => false, 'is_active' => false, 'description' => $generateDescriptionBasedOnCategory('Ételek'), 'default_time_to_deliver' => 10, 'price' => 700, 'category_id' => Category::where('name', 'Ételek')->first()->id]);

        // Péksütemények
        Item::create(['name' => 'Fehér zsemle', 'description' => $generateDescriptionBasedOnCategory('Péksütemények'), 'default_time_to_deliver' => 5, 'price' => 120, 'category_id' => Category::where('name', 'Péksütemények')->first()->id]);
        Item::create(['name' => 'Tejes kifli', 'description' => $generateDescriptionBasedOnCategory('Péksütemények'), 'default_time_to_deliver' => 5, 'price' => 120, 'category_id' => Category::where('name', 'Péksütemények')->first()->id]);
        Item::create(['name' => 'Zsemle', 'description' => $generateDescriptionBasedOnCategory('Péksütemények'), 'default_time_to_deliver' => 5, 'price' => 120, 'category_id' => Category::where('name', 'Péksütemények')->first()->id]);
        Item::create(['name' => 'Körspitz', 'description' => $generateDescriptionBasedOnCategory('Péksütemények'), 'default_time_to_deliver' => 5, 'price' => 150, 'category_id' => Category::where('name', 'Péksütemények')->first()->id]);

        // Édességek
        Item::create(['name' => 'Twix', 'is_featured' => true, 'description' => $generateDescriptionBasedOnCategory('Édességek'), 'default_time_to_deliver' => 5, 'price' => 400, 'category_id' => Category::where('name', 'Édességek')->first()->id]);
        Item::create(['name' => 'Sport szelet', 'is_featured' => false, 'description' => $generateDescriptionBasedOnCategory('Édességek'), 'default_time_to_deliver' => 5, 'price' => 300, 'category_id' => Category::where('name', 'Édességek')->first()->id]);
        Item::create(['name' => 'Sport szelet XL', 'is_featured' => true, 'description' => $generateDescriptionBasedOnCategory('Édességek'), 'default_time_to_deliver' => 5, 'price' => 450, 'category_id' => Category::where('name', 'Édességek')->first()->id]);
        Item::create(['name' => 'Bounty', 'is_featured' => false, 'description' => $generateDescriptionBasedOnCategory('Édességek'), 'default_time_to_deliver' => 5, 'price' => 450, 'category_id' => Category::where('name', 'Édességek')->first()->id]);
        Item::create(['name' => 'Balaton szelet', 'is_featured' => true, 'description' => $generateDescriptionBasedOnCategory('Édességek'), 'default_time_to_deliver' => 5, 'price' => 300, 'category_id' => Category::where('name', 'Édességek')->first()->id]);
        Item::create(['name' => 'Balaton Bumm', 'is_featured' => true, 'description' => $generateDescriptionBasedOnCategory('Édességek'), 'default_time_to_deliver' => 5, 'price' => 350, 'category_id' => Category::where('name', 'Édességek')->first()->id]);
        Item::create(['name' => 'Bueno Classic', 'description' => $generateDescriptionBasedOnCategory('Édességek'), 'default_time_to_deliver' => 5, 'price' => 500, 'category_id' => Category::where('name', 'Édességek')->first()->id]);
        Item::create(['name' => 'Bueno White', 'description' => $generateDescriptionBasedOnCategory('Édességek'), 'default_time_to_deliver' => 5, 'price' => 500, 'category_id' => Category::where('name', 'Édességek')->first()->id]);
        Item::create(['name' => '3Bit', 'description' => $generateDescriptionBasedOnCategory('Édességek'), 'default_time_to_deliver' => 5, 'price' => 350, 'category_id' => Category::where('name', 'Édességek')->first()->id]);
        Item::create(['name' => 'Wafelini csokis', 'description' => $generateDescriptionBasedOnCategory('Édességek'), 'default_time_to_deliver' => 5, 'price' => 300, 'category_id' => Category::where('name', 'Édességek')->first()->id]);
        Item::create(['name' => 'Wafelini vaníliás', 'description' => $generateDescriptionBasedOnCategory('Édességek'), 'default_time_to_deliver' => 5, 'price' => 300, 'category_id' => Category::where('name', 'Édességek')->first()->id]);
        Item::create(['name' => 'Müzliszelet', 'description' => $generateDescriptionBasedOnCategory('Édességek'), 'default_time_to_deliver' => 5, 'price' => 280, 'category_id' => Category::where('name', 'Édességek')->first()->id]);
        Item::create(['name' => 'Milka', 'description' => $generateDescriptionBasedOnCategory('Édességek'), 'default_time_to_deliver' => 5, 'price' => 700, 'category_id' => Category::where('name', 'Édességek')->first()->id]);
        Item::create(['name' => 'Tábla csoki', 'description' => $generateDescriptionBasedOnCategory('Édességek'), 'default_time_to_deliver' => 5, 'price' => 900, 'category_id' => Category::where('name', 'Édességek')->first()->id]);
        Item::create(['name' => 'Kinder Maxi', 'description' => $generateDescriptionBasedOnCategory('Édességek'), 'default_time_to_deliver' => 5, 'price' => 450, 'category_id' => Category::where('name', 'Édességek')->first()->id]);
        Item::create(['name' => 'Kinder Pingui', 'description' => $generateDescriptionBasedOnCategory('Édességek'), 'default_time_to_deliver' => 5, 'price' => 500, 'category_id' => Category::where('name', 'Édességek')->first()->id]);

        // Snackek
        Item::create(['name' => 'Chips - Cheetos', 'description' => $generateDescriptionBasedOnCategory('Snackek'), 'default_time_to_deliver' => 5, 'price' => 700, 'category_id' => Category::where('name', 'Snackek')->first()->id]);
        Item::create(['name' => 'Chips - Lays', 'description' => $generateDescriptionBasedOnCategory('Snackek'), 'default_time_to_deliver' => 5, 'price' => 700, 'category_id' => Category::where('name', 'Snackek')->first()->id]);
        Item::create(['name' => 'Bake Rolls', 'description' => $generateDescriptionBasedOnCategory('Snackek'), 'default_time_to_deliver' => 5, 'price' => 650, 'category_id' => Category::where('name', 'Snackek')->first()->id]);
        Item::create(['name' => 'TUC keksz', 'description' => $generateDescriptionBasedOnCategory('Snackek'), 'default_time_to_deliver' => 5, 'price' => 450, 'category_id' => Category::where('name', 'Snackek')->first()->id]);
        Item::create(['name' => 'Rágós nyalóka', 'description' => $generateDescriptionBasedOnCategory('Snackek'), 'default_time_to_deliver' => 5, 'price' => 140, 'category_id' => Category::where('name', 'Snackek')->first()->id]);
        Item::create(['name' => 'Rágó', 'description' => $generateDescriptionBasedOnCategory('Snackek'), 'default_time_to_deliver' => 5, 'price' => 100, 'category_id' => Category::where('name', 'Snackek')->first()->id]);

        // Italok
        Item::create(['name' => 'Meleg tea', 'description' => $generateDescriptionBasedOnCategory('Italok'), 'default_time_to_deliver' => 5, 'price' => 250, 'category_id' => Category::where('name', 'Italok')->first()->id]);
        Item::create(['name' => 'Kávé', 'is_featured' => true, 'description' => $generateDescriptionBasedOnCategory('Italok'), 'default_time_to_deliver' => 5, 'price' => 250, 'category_id' => Category::where('name', 'Italok')->first()->id]);
        Item::create(['name' => 'Forró csoki', 'description' => $generateDescriptionBasedOnCategory('Italok'), 'default_time_to_deliver' => 5, 'price' => 400, 'category_id' => Category::where('name', 'Italok')->first()->id]);
        Item::create(['name' => 'Jeges kávé', 'is_featured' => true, 'description' => $generateDescriptionBasedOnCategory('Italok'), 'default_time_to_deliver' => 5, 'price' => 600, 'category_id' => Category::where('name', 'Italok')->first()->id]);
        Item::create(['name' => 'Pepsi Cola 0.5L', 'description' => $generateDescriptionBasedOnCategory('Italok'), 'default_time_to_deliver' => 5, 'price' => 500, 'category_id' => Category::where('name', 'Italok')->first()->id]);
        Item::create(['name' => 'Pepsi Black 0.5L', 'description' => $generateDescriptionBasedOnCategory('Italok'), 'default_time_to_deliver' => 5, 'price' => 500, 'category_id' => Category::where('name', 'Italok')->first()->id]);
        Item::create(['name' => 'Dr Pepper 0.5L', 'description' => $generateDescriptionBasedOnCategory('Italok'), 'default_time_to_deliver' => 5, 'price' => 500, 'category_id' => Category::where('name', 'Italok')->first()->id]);
        Item::create(['name' => 'Canada Dry 0.5L', 'description' => $generateDescriptionBasedOnCategory('Italok'), 'default_time_to_deliver' => 5, 'price' => 500, 'category_id' => Category::where('name', 'Italok')->first()->id]);
        Item::create(['name' => '7UP 0.5L', 'description' => $generateDescriptionBasedOnCategory('Italok'), 'default_time_to_deliver' => 5, 'price' => 500, 'category_id' => Category::where('name', 'Italok')->first()->id]);
        Item::create(['name' => 'Mountain Dew 0.5L', 'description' => $generateDescriptionBasedOnCategory('Italok'), 'default_time_to_deliver' => 5, 'price' => 500, 'category_id' => Category::where('name', 'Italok')->first()->id]);
        Item::create(['name' => 'Schweppes 0.5L', 'description' => $generateDescriptionBasedOnCategory('Italok'), 'default_time_to_deliver' => 5, 'price' => 500, 'category_id' => Category::where('name', 'Italok')->first()->id]);
        Item::create(['name' => 'Toma gyümölcslé 0.5L', 'description' => $generateDescriptionBasedOnCategory('Italok'), 'default_time_to_deliver' => 5, 'price' => 500, 'category_id' => Category::where('name', 'Italok')->first()->id]);
        Item::create(['name' => 'Lipton tea 0.5L', 'description' => $generateDescriptionBasedOnCategory('Italok'), 'default_time_to_deliver' => 5, 'price' => 500, 'category_id' => Category::where('name', 'Italok')->first()->id]);
        Item::create(['name' => 'Theodora víz 0.5L', 'description' => $generateDescriptionBasedOnCategory('Italok'), 'default_time_to_deliver' => 5, 'price' => 300, 'category_id' => Category::where('name', 'Italok')->first()->id]);
        Item::create(['name' => 'Theodora ízesített víz 0.5L', 'description' => $generateDescriptionBasedOnCategory('Italok'), 'default_time_to_deliver' => 5, 'price' => 300, 'category_id' => Category::where('name', 'Italok')->first()->id]);
        Item::create(['name' => 'Pepsi Cola 1L', 'description' => $generateDescriptionBasedOnCategory('Italok'), 'default_time_to_deliver' => 5, 'price' => 600, 'category_id' => Category::where('name', 'Italok')->first()->id]);
        Item::create(['name' => 'Canada Dry 1L', 'description' => $generateDescriptionBasedOnCategory('Italok'), 'default_time_to_deliver' => 5, 'price' => 600, 'category_id' => Category::where('name', 'Italok')->first()->id]);
        Item::create(['name' => 'Theodora víz 1.5L', 'description' => $generateDescriptionBasedOnCategory('Italok'), 'default_time_to_deliver' => 5, 'price' => 600, 'category_id' => Category::where('name', 'Italok')->first()->id]);
        Item::create(['name' => 'Xixo Tea 1.5L', 'is_featured' => true, 'description' => $generateDescriptionBasedOnCategory('Italok'), 'default_time_to_deliver' => 5, 'price' => 600, 'category_id' => Category::where('name', 'Italok')->first()->id]);
    }
}
