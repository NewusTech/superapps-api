<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterKotaAsalDanTujuanOnMasterRute extends Migration
{
    public function up()
    {
        Schema::table('master_rute', function (Blueprint $table) {
            $table->dropColumn('kota_asal');
            $table->dropColumn('kota_tujuan');
        });
        
        Schema::table('master_rute', function (Blueprint $table) {
            $table->unsignedInteger('kota_asal')->nullable()->after('id');
            $table->unsignedInteger('kota_tujuan')->nullable()->after('kota_asal');
        });
        
        Schema::table('master_rute', function (Blueprint $table) {
            $table->foreign('kota_asal')->references('id')->on('master_cabang')->onDelete('cascade');
            $table->foreign('kota_tujuan')->references('id')->on('master_cabang')->onDelete('cascade');
        });
        
    }

    public function down()
    {
        Schema::table('master_rute', function (Blueprint $table) {
            $table->dropForeign(['kota_asal']);
            $table->dropForeign(['kota_tujuan']);
            $table->dropColumn(['kota_asal', 'kota_tujuan']);
        });

        // Tambahkan kembali sebagai string jika rollback
        Schema::table('master_rute', function (Blueprint $table) {
            $table->string('kota_asal')->nullable()->after('id');
            $table->string('kota_tujuan')->nullable()->after('kota_asal');
        });
    }
}
