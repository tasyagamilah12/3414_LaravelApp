<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">

    <title>Laporan Transaksi</title>

    <style>

        body{
            font-family: DejaVu Sans, sans-serif;
            font-size:12px;
        }

        h2{
            text-align:center;
            margin-bottom:5px;
        }

        p{
            text-align:center;
            margin-top:0;
            color:#666;
        }

        table{

            width:100%;

            border-collapse:collapse;

            margin-top:20px;

        }

        table th{

            background:#1e40af;

            color:white;

            padding:8px;

            border:1px solid #ddd;

        }

        table td{

            border:1px solid #ddd;

            padding:8px;

        }

        .right{

            text-align:right;

        }

    </style>

</head>

<body>

    <h2>LAPORAN TRANSAKSI</h2>

    <p>

        Dicetak pada :

        {{ now()->format('d M Y H:i') }}

    </p>

    <table>

        <thead>

            <tr>

                <th>No</th>

                <th>Order ID</th>

                <th>Pembeli</th>

                <th>Event</th>

                <th>Status</th>

                <th>Total</th>

                <th>Tanggal</th>

            </tr>

        </thead>

        <tbody>

            @forelse($transactions as $trx)

                <tr>

                    <td>

                        {{ $loop->iteration }}

                    </td>

                    <td>

                        {{ $trx->order_id }}

                    </td>

                    <td>

                        {{ $trx->customer_name }}

                    </td>

                    <td>

                        {{ $trx->event->title ?? '-' }}

                    </td>

                    <td>

                        {{ strtoupper($trx->status) }}

                    </td>

                    <td class="right">

                        Rp {{ number_format($trx->total_price,0,',','.') }}

                    </td>

                    <td>

                        {{ $trx->created_at->format('d-m-Y') }}

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="7" style="text-align:center">

                        Tidak ada data transaksi.

                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>

</body>

</html>