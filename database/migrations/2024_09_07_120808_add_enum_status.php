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
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->dropForeign('fk_pembayaran_pesanan');
        });


        Schema::table('pembayaran', function (Blueprint $table) {
            $table->enum('status', ['Menunggu Pembayaran', 'Menunggu Verifikasi', 'Sukses', 'Gagal'])->default('Menunggu Pembayaran')->change();
        });
    }

    public function down(): void
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->foreign('pesanan_id')->references('id')->on('pesanan')->onDelete('no action')->onUpdate('no action');
        });
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->enum('status', ['Menunggu Pembayaran', 'Sukses', 'Gagal'])->default('Menunggu Pembayaran')->change();
        });
    }
};
