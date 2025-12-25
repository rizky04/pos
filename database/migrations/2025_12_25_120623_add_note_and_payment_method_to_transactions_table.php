<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {

            // Catatan transaksi
            $table->text('note')
                ->nullable()
                ->after('kode');

            // Metode pembayaran
            $table->string('payment_method')
                ->default('cash')
                ->after('status');
            // contoh: cash, transfer, qris, debit card
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['note', 'payment_method']);
        });
    }
};
