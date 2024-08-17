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
        Schema::table('pesanan', function (Blueprint $table) {
            $table->unsignedInteger('kategori_pesanan_id')->after('kode_pesanan')->nullable();
            $table->text('alamat')->after('no_telp')->nullable();
        });

        Schema::create('rental', function (Blueprint $table) {
            $table->id();
            $table->integer('durasi_sewa')->default(1);
            $table->string('area')->default('dalam kota');
            $table->date('tanggal_mulai_sewa')->nullable();
            $table->date('tanggal_akhir_sewa')->nullable();
            $table->text('alamat_keberangkatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->dropColumn('kategori_pesanan_id');
            $table->dropColumn('alamat');
        });

        Schema::dropIfExists('rental');
    }
};
