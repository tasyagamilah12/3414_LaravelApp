<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {

            $table->id();

            $table->foreignId('event_id')
                ->constrained()
                ->cascadeOnDelete();

            // Nomor Order Midtrans
            $table->string('order_id')->unique();

            // Data Customer
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');

            // Total pembayaran
            $table->integer('total_price');

            /*
            |--------------------------------------------------------------------------
            | Status Transaksi
            |--------------------------------------------------------------------------
            | pending
            | settlement
            | success
            | failed
            | challenge
            |--------------------------------------------------------------------------
            */
            $table->string('status')
                ->default('pending');

            // Snap Token Midtrans
            $table->string('snap_token')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};