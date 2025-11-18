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
        Schema::table('units', function (Blueprint $table) {
            $table->dropUnique(['kode']); // Hapus unique lama
        $table->unique(['tenant_id', 'kode']); // Tambahkan unique per tenant
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('units', function (Blueprint $table) {
           $table->dropUnique(['units_tenant_id_kode_unique']);
        $table->unique('kode');
        });
    }
};
