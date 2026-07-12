<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransWebhookController extends Controller
{
    public function handle(Request $request)
    {
        // Simpan log untuk memastikan webhook benar-benar masuk
        Log::info('Webhook Midtrans diterima', $request->all());

        $payload = $request->all();

        $orderId = $payload['order_id'] ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;
        $fraudStatus = $payload['fraud_status'] ?? null;

        if (!$orderId) {
            return response()->json([
                'message' => 'Invalid payload'
            ], 400);
        }

        // Cari transaksi berdasarkan order_id
        $transaction = Transaction::with('event')
            ->where('order_id', $orderId)
            ->first();

        if (!$transaction) {
            Log::warning("Transaksi tidak ditemukan: {$orderId}");

            return response()->json([
                'message' => 'Transaction not found'
            ], 404);
        }

        // Hindari proses webhook lebih dari sekali
        if (in_array($transaction->status, ['success', 'settlement'])) {
            return response()->json([
                'message' => 'Already processed'
            ]);
        }

        // Mapping status Midtrans
        switch ($transactionStatus) {

            case 'capture':
                if ($fraudStatus === 'challenge') {
                    $transaction->status = 'challenge';
                } elseif ($fraudStatus === 'accept') {
                    $transaction->status = 'success';
                    $this->processSuccess($transaction);
                }
                break;

            case 'settlement':
                $transaction->status = 'settlement';
                $this->processSuccess($transaction);
                break;

            case 'pending':
                $transaction->status = 'pending';
                break;

            case 'expire':
            case 'cancel':
            case 'deny':
                $transaction->status = 'failed';
                break;
        }

        $transaction->save();

        Log::info("Status transaksi {$orderId} berhasil diubah menjadi {$transaction->status}");

        return response()->json([
            'message' => 'OK'
        ]);
    }

    /**
     * Dipanggil ketika pembayaran berhasil.
     * Modul berikutnya dapat menambahkan logika pengurangan stok tiket di sini.
     */
    private function processSuccess(Transaction $transaction)
    {
        // Contoh:
        // $transaction->event->decrement('available_tickets');

        Log::info("Pembayaran berhasil untuk Order ID: {$transaction->order_id}");
    }
}