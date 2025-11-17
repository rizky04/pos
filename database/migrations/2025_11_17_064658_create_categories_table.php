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
        Schema::create('categories', function (Blueprint $table) {
              $table->uuid('id')->primary();
        $table->uuid('tenant_id'); // Multi Tenant
        $table->string('kode', 50);
        $table->string('nama', 200);
        $table->enum('status', ['active', 'nonactive'])->default('active');
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
        Schema::dropIfExists('categories');
    }
};
