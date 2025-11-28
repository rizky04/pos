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
        Schema::create('purchases', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Tenant
            $table->uuid('tenant_id')->index();

            // Supplier
            $table->uuid('supplier_id')->nullable()->index();

            // Data utama
            $table->string('kode');
            $table->string('invoice');
            $table->date('tanggal');
            $table->date('jatuh_tempo')->nullable();

            $table->enum('status_pembelian', ['draft', 'posted', 'paid', 'unpaid'])->default('draft');
            $table->enum('metode_bayar', ['Cash', 'Transfer', 'Giro', 'Lainnya'])->default('Cash');

            $table->string('catatan')->nullable();

            // PPN
            $table->integer('ppn_percent')->default(11);
            $table->bigInteger('subtotal')->default(0);
            $table->bigInteger('total_ppn')->default(0);
            $table->bigInteger('grand_total')->default(0);

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
