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
        Schema::table('master_mobil', function (Blueprint $table) {
            $table->integer('available_seats')->after('jumlah_kursi')->default(0);
            $table->string('fasilitas')->after('jumlah_kursi')->nullable();
        });

        Schema::table('master_supir', function (Blueprint $table) {
            $table->string('nik')->after('no_telp')->nullable();
            $table->text('alamat')->after('nik')->nullable();
            $table->date('tanggal_bergabung')->after('alamat')->nullable();
        });

        Schema::table('master_cabang', function (Blueprint $table) {
            $table->text('alamat')->after('nama')->nullable();
        });

        Schema::create('syarat_ketentuan', function (Blueprint $table) {
            $table->id();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_mobil', function (Blueprint $table) {
            $table->dropColumn('available_seats');
            $table->dropColumn('fasilitas');
        });

        Schema::table('master_supir', function (Blueprint $table) {
            $table->dropColumn('nik');
            $table->dropColumn('alamat');
            $table->dropColumn('tanggal_bergabung');
        });

        Schema::table('master_cabang', function (Blueprint $table) {
            $table->dropColumn('alamat');
        });

        Schema::dropIfExists('syarat_ketentuan');
    }
};
