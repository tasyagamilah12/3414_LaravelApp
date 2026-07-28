<?php

namespace App\Http\Controllers;

use App\Mail\EventTicketMail;
use App\Models\Event;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MidtransWebhookController extends Controller
{
    public function handle(Request $request)
    {
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

        $transaction = Transaction::where('order_id', $orderId)
            ->first();

        if (!$transaction) {

            Log::warning("Transaksi tidak ditemukan : {$orderId}");

            return response()->json([
                'message' => 'Transaction not found'
            ], 404);
        }

        // Hindari webhook diproses dua kali
        if (in_array($transaction->status, ['success', 'settlement'])) {

            return response()->json([
                'message' => 'Already processed'
            ]);
        }

        switch ($transactionStatus) {

            case 'capture':

                if ($fraudStatus == 'challenge') {

                    $transaction->status = 'challenge';

                } else {

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

        Log::info("Status transaksi {$orderId} menjadi {$transaction->status}");

        return response()->json([
            'message' => 'OK'
        ]);
    }

    /**
     * Dipanggil ketika pembayaran berhasil.
     */
    private function processSuccess(Transaction $transaction)
    {
        DB::transaction(function () use ($transaction) {

            $event = Event::where('id', $transaction->event_id)
                ->lockForUpdate()
                ->first();

            if (!$event) {

                Log::warning('Event tidak ditemukan.');

                return;
            }

            if ($event->stock <= 0) {

                Log::warning(
                    'Stock habis. Order : '.$transaction->order_id
                );

                return;
            }

            // Kurangi stok secara aman
            $event->decrement('stock');

            try {

                Mail::to($transaction->customer_email)
                    ->send(new EventTicketMail($transaction));

            } catch (\Exception $e) {

                Log::error(
                    'Gagal mengirim email : '.$e->getMessage()
                );

            }

        });
    }
}