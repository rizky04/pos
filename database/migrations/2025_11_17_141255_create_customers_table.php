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
        Schema::create('customers', function (Blueprint $table) {
              // UUID primary key
              $table->uuid('id')->primary();

              // Tenant
              $table->uuid('tenant_id');

              // Customer data
              $table->string('kode', 50)->unique();
              $table->string('nama', 200);

              $table->string('telepon', 50)->nullable();
              $table->string('email', 100)->nullable();

              // Filters
              $table->enum('tipe', ['retail', 'wholesale', 'corporate'])->default('retail');
              $table->enum('member', ['none', 'silver', 'gold', 'platinum'])->default('none');
              $table->text('alamat')->nullable();
              $table->string('kota', 100)->nullable();

              // Limit piutang
              $table->decimal('limit', 15, 2)->default(0);

              // Status customer
              $table->enum('status', ['active', 'nonactive'])->default('active');
              $table->text('catatan')->nullable();
              $table->timestamps();
              $table->softDeletes();

              // Foreign key tenant
              $table->foreign('tenant_id')
                  ->references('id')
                  ->on('tenants')
                  ->onDelete('cascade');

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
        Schema::dropIfExists('customers');
    }
};
