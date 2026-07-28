<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        // ==========================
        // Ambil Parameter Request
        // ==========================
        $search = $request->search;
        $status = $request->status;
        $start = $request->start_date;
        $end = $request->end_date;

        // ==========================
        // Sorting
        // ==========================
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');

        $allowedSort = [
            'order_id',
            'customer_name',
            'status',
            'total_price',
            'created_at',
        ];

        if (! in_array($sort, $allowedSort)) {
            $sort = 'created_at';
        }

        // ==========================
        // Query Transaction
        // ==========================
        $transactions = Transaction::with('event')

            // Search
            ->when($search, function ($query) use ($search) {

                $query->where(function ($q) use ($search) {

                    $q->where('order_id', 'like', "%{$search}%")
                        ->orWhere('customer_name', 'like', "%{$search}%")
                        ->orWhere('customer_email', 'like', "%{$search}%")
                        ->orWhere('customer_phone', 'like', "%{$search}%")
                        ->orWhere('status', 'like', "%{$search}%")
                        ->orWhereHas('event', function ($event) use ($search) {

                            $event->where('title', 'like', "%{$search}%");

                        });

                });

            })

            // Filter Status
            ->when($status, function ($query) use ($status) {

                $query->where('status', $status);

            })

            // Filter Tanggal Awal
            ->when($start, function ($query) use ($start) {

                $query->whereDate('created_at', '>=', $start);

            })

            // Filter Tanggal Akhir
            ->when($end, function ($query) use ($end) {

                $query->whereDate('created_at', '<=', $end);

            })

            // Sorting
            ->orderBy($sort, $direction)

            // Pagination
            ->paginate(20)

            // Menyimpan query saat pindah halaman
            ->withQueryString();

        $totalRevenue = Transaction::whereIn('status', [
            'success',
            'settlement',
        ])->sum('total_price');

        $successCount = Transaction::whereIn('status', [
            'success',
            'settlement',
        ])->count();

        $pendingCount = Transaction::where('status', 'pending')->count();

        $failedCount = Transaction::whereIn('status', [
            'cancel',
            'deny',
            'failed',
            'expire',
        ])->count();

        return view(
            'admin.transactions.index',
            compact(
                'transactions',
                'search',
                'status',
                'start',
                'end',
                'sort',
                'direction',

                'totalRevenue',
                'successCount',
                'pendingCount',
                'failedCount'
            )
        );
    }

    public function show(Transaction $transaction)
{
    $transaction->load('event');

    return response()->json([
        'order_id'        => $transaction->order_id,
        'customer_name'   => $transaction->customer_name,
        'customer_email'  => $transaction->customer_email,
        'customer_phone'  => $transaction->customer_phone,
        'event'           => $transaction->event->title ?? '-',
        'status'          => $transaction->status,
        'total_price'     => $transaction->total_price,
        'payment_type'    => $transaction->payment_type,
        'created_at'      => $transaction->created_at ? $transaction->created_at->format('d M Y H:i') : '-',
    ]);
}
}
