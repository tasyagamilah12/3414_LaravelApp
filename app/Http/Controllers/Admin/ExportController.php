<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Exports\TransactionsExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    /**
     * Export PDF
     */
    public function pdf()
    {
        $transactions = Transaction::with('event')
            ->latest()
            ->get();

        $pdf = Pdf::loadView(
            'pdf.transactions',
            compact('transactions')
        );

        return $pdf->download('laporan-transaksi.pdf');
    }

    /**
     * Export Excel
     */
    public function excel()
    {
        return Excel::download(
            new TransactionsExport,
            'laporan-transaksi.xlsx'
        );
    }
}