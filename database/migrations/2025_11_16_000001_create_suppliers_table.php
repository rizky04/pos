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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->string('kode_supplier', 50)->unique();
            $table->string('nama_supplier', 200);
            $table->string('contact_person', 100)->nullable();
            $table->string('telepon', 50)->nullable();
            $table->string('email', 100)->nullable();
            $table->text('alamat')->nullable();
            $table->string('kota', 100)->nullable();
            $table->integer('termin_pembayaran')->default(0)->comment('dalam hari');
            $table->string('npwp', 50)->nullable();
            $table->enum('status', ['active', 'nonactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();

            // Foreign key
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
        Schema::dropIfExists('suppliers');
    }
};
