<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->unsignedBigInteger('user_id');

            $table->string('kode')->unique(); // nomor invoice

            $table->double('sub_total')->default(0);
            $table->double('discount_value')->default(0);
            $table->string('discount_type')->default('rp'); // rp / percent
            $table->double('total_after_discount')->default(0);
            $table->double('ppn')->default(0);
            $table->double('total_after_ppn')->default(0);

            $table->double('pay_amount')->default(0);
            $table->double('change_amount')->default(0);

            $table->enum('status', ['paid', 'unpaid', 'void'])->default('paid');

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
