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
        Schema::create('products', function (Blueprint $table) {
             $table->uuid('id')->primary();

            // relasi tenant
            $table->uuid('tenant_id');

            // relasi kategori
            $table->uuid('category_id');

            // relasi unit
            $table->uuid('unit_id');

            $table->string('kode')->unique();
            $table->string('nama');
            $table->integer('harga_modal')->default(0);
            $table->integer('harga_jual')->default(0);
            $table->integer('stok')->default(0);

            $table->enum('status', ['active', 'nonactive'])->default('active');

            $table->timestamps();
            $table->softDeletes();

            // FK
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
