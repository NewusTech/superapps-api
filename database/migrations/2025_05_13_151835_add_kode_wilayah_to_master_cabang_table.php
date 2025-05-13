<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('master_cabang', function (Blueprint $table) {
            $table->string('kode_provinsi', 10)->nullable()->after('alamat');
            $table->string('kode_kota', 10)->nullable()->after('kode_provinsi');
        });
    }

    public function down(): void
    {
        Schema::table('master_cabang', function (Blueprint $table) {
            $table->dropColumn(['kode_provinsi', 'kode_kota']);
        });
    }
};
