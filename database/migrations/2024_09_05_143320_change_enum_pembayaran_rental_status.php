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
        Schema::table('pembayaran_rental', function (Blueprint $table) {
            $table->enum('status', ['Menunggu Pembayaran', 'Sukses', 'Gagal', 'Menunggu Verifikasi'])->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembayaran_rental', function (Blueprint $table) {
            $table->enum('status', ['Menunggu Pembayaran', 'Sukses', 'Gagal', 'Kadaluwarsa'])->nullable()->change();
        });
    }
};
