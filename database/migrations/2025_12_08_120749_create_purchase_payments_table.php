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
        Schema::create('purchase_payments', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('tenant_id');
            $table->uuid('purchase_id');

            $table->date('payment_date');                // Tanggal bayar
            $table->string('payment_method')->nullable(); // Cash, Transfer, Giro
            $table->string('reference')->nullable();      // No bukti transfer / giro
            $table->text('note')->nullable();             // Catatan tambahan

            $table->bigInteger('amount');                 // Nominal dibayar
            $table->bigInteger('remaining_amount');       // Sisa hutang setelah bayar

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('purchase_id')->references('id')->on('purchases');
            $table->foreign('tenant_id')->references('id')->on('tenants');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_payments');
    }
};
