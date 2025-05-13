<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterJadwalKeberangkatan extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('master_jadwal_keberangkatan', function (Blueprint $table) {
            $table->id(); // => UNSIGNED BIGINT AUTO INCREMENT
            $table->string('nama_shift')->nullable();
            $table->time('waktu_keberangkatan');
            $table->time('waktu_tiba')->nullable();
            $table->timestamps();
        });

        // Optional: tambahkan kolom ini ke tabel jadwal jika belum ada
        Schema::table('jadwal', function (Blueprint $table) {
            $table->unsignedBigInteger('master_jadwal_keberangkatan_id')->nullable()->after('master_supir_id');
            $table->foreign('master_jadwal_keberangkatan_id')
                ->references('id')
                ->on('master_jadwal_keberangkatan')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal', function (Blueprint $table) {
            $table->dropForeign(['master_jadwal_keberangkatan_id']);
            $table->dropColumn('master_jadwal_keberangkatan_id');
        });

        Schema::dropIfExists('master_jadwal_keberangkatan');
    }
}
