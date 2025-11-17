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
        Schema::create('units', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->uuid('tenant_id');
    $table->string('nama');
    $table->string('kode')->unique();
    $table->string('tipe')->default('unit'); // unit, volume, berat, lain
    $table->string('deskripsi')->nullable();
    $table->enum('status', ['active', 'nonactive'])->default('active');
    $table->boolean('is_default')->default(false);
    $table->timestamps();
       $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');

            // Index
            $table->index('tenant_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
