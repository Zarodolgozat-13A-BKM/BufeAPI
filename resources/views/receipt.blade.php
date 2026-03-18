<!DOCTYPE html>
<html lang="hu">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Nyugta - {{ $order->id }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 14px;
            color: #333;
            margin: 0;
            padding: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table {
            margin-bottom: 40px;
        }

        .header-table td {
            vertical-align: top;
        }

        .title {
            font-size: 28px;
            font-weight: bold;
            text-align: right;
            color: #4CAF50;
            /* Egy kis márka szín */
            text-transform: uppercase;
        }

        .info-table {
            margin-bottom: 30px;
        }

        .info-table td {
            width: 50%;
            vertical-align: top;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #555;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }

        .items-table {
            margin-bottom: 30px;
        }

        .items-table th {
            background-color: #f4f4f4;
            color: #333;
            padding: 10px;
            text-align: left;
            border-bottom: 2px solid #ddd;
        }

        .items-table td {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }

        .text-right {
            text-align: right !important;
        }

        .text-center {
            text-align: center !important;
        }

        .totals-table {
            width: 50%;
            float: right;
        }

        .totals-table td {
            padding: 8px 10px;
        }

        .total-row {
            font-weight: bold;
            font-size: 18px;
            border-top: 2px solid #333;
        }

        .footer {
            clear: both;
            margin-top: 60px;
            text-align: center;
            font-size: 12px;
            color: #777;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
    </style>
</head>

<body>

    <table class="header-table">
        <tr>
            <td>
                <h2>Jedlik Ányos Technikum Büfé</h2>
                Név/Cégnév: Jedlik Büfé BT.<br>
                Cím: Győr, Szent István út 7, 9021<br>
                Adószám: 1111111-11-111<br>
                Email: info@jedlikbufe.hu
            </td>
            <td>
                <div class="title">Nyugta</div>
                <div class="text-right">
                    <strong>Nyugtaszám:</strong> {{ $order->id }}<br>
                    <strong>Dátum:</strong> {{ $order->created_at->format('Y. m. d.') }}
                </div>
            </td>
        </tr>
    </table>

    <table class="info-table">
        <tr>
            <td style="padding-right: 20px;">
                <div class="section-title">Vevő:</div>
                <strong>{{ $order->user->full_name }}</strong>
                <br />
                {{ $order->user->email }}
            </td>
            {{-- <td>
                <div class="section-title">Fizetési információk:</div>
                Fizetési mód: <strong>{{ $receipt['payment_method'] }}</strong><br>
                Tranzakció azonosító: {{ $receipt['transaction_id'] ?? '-' }}
            </td> --}}
        </tr>
    </table>
    {{-- @if (!empty($order->comment))
        <div class="note-section">
            <div class="section-title" style="border-bottom: none; margin-bottom: 5px;">Vevő megjegyzése:</div>
            <p>{{ $order->comment }}</p>
        </div>
    @endif --}}

    <table class="items-table">
        <thead>
            <tr>
                <th>Tétel megnevezése</th>
                <th class="text-center">Mennyiség</th>
                <th class="text-right">Egységár</th>
                <th class="text-right">Összesen</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td class="text-center">{{ $item->pivot->quantity }} db</td>
                    <td class="text-right">{{ number_format($item->price, 0, ',', ' ') }} Ft</td>
                    <td class="text-right">{{ number_format($item->pivot->quantity * $item->price, 0, ',', ' ') }} Ft
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals-table">
        <tr>
            <td>Részösszeg:</td>
            <td class="text-right">{{ number_format($order->totalPrice() * 0.73, 0, ',', ' ') }} Ft</td>
        </tr>
        <tr>
            <td>ÁFA 27%:</td>
            <td class="text-right">{{ number_format($order->totalPrice() * 0.27, 0, ',', ' ') }} Ft</td>
        </tr>
        {{-- <tr>
            <td>Kényelmi díj:</td>
            <td class="text-right">{{ number_format($order->totalPrice() * 0.05, 0, ',', ' ') }} Ft</td>
        </tr> --}}
        <tr class="total-row">
            <td>Végösszeg:</td>
            <td class="text-right">{{ number_format($order->totalPrice(), 0, ',', ' ') }} Ft</td>
        </tr>
    </table>

    <div class="footer">
        Köszönjük a vásárlást!<br>
        Ez a dokumentum elektronikusan került kiállításra. Kérdés esetén vedd fel velünk a kapcsolatot a(z)
        info@jedlikbufe.hu címen.
    </div>

</body>

</html>
