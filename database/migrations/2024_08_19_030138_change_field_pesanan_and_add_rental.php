<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->dropColumn('kategori_pesanan_id');
            $table->dropColumn('alamat');
        });

        Schema::table('rental', function (Blueprint $table) {
            $table->string('nama')->after('alamat_keberangkatan');
            $table->string('nik')->after('nama');
            $table->string('email')->after('nik');
            $table->string('no_telp')->after('email');
            $table->text('alamat')->after('no_telp');
        });
    }

    public function down(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->unsignedInteger('kategori_pesanan_id')->after('kode_pesanan')->nullable();
            $table->text('alamat')->after('no_telp')->nullable();
        });

        Schema::table('rental', function (Blueprint $table) {
            $table->dropColumn('nama');
            $table->dropColumn('nik');
            $table->dropColumn('email');
            $table->dropColumn('no_telp');
            $table->dropColumn('alamat');
        });

    }
};
