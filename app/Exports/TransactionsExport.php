<?php

namespace App\Exports;

use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TransactionsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $query = Transaction::with('event');

        // Organizer hanya export transaksi miliknya
        if (Auth::user()->role != 'admin') {

            $query->whereHas('event', function ($q) {
                $q->where('user_id', Auth::id());
            });

        }

        return $query
            ->latest()
            ->get()
            ->map(function ($trx) {

                return [

                    'Order ID'        => $trx->order_id,

                    'Nama Pembeli'    => $trx->customer_name,

                    'Email'           => $trx->customer_email,

                    'No HP'           => $trx->customer_phone,

                    'Event'           => $trx->event->title ?? '-',

                    'Status'          => strtoupper($trx->status),

                    'Total'           => $trx->total_price,

                    'Tanggal'         => $trx->created_at->format('d-m-Y H:i'),

                ];

            });

    }

    public function headings(): array
    {
        return [

            'Order ID',

            'Nama Pembeli',

            'Email',

            'No HP',

            'Event',

            'Status',

            'Total',

            'Tanggal',

        ];
    }
}