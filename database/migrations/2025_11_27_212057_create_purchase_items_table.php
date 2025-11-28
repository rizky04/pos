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
        Schema::create('purchase_items', function (Blueprint $table) {
             $table->uuid('id')->primary();

            $table->uuid('purchase_id')->index();
            $table->uuid('product_id')->nullable()->index(); // Kalau Anda pakai master barang

            $table->string('nama_barang'); // fallback jika product_id null
            $table->integer('qty');
            $table->bigInteger('harga_beli');
            $table->bigInteger('subtotal');

            $table->timestamps();
            // FOREIGN KEY
    $table->foreign('purchase_id')->references('id')->on('purchases')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_items');
    }
};
