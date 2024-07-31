<?php

use App\Models\Pesanan;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->dropForeign('fk_pesanan_kursi');
        });

        Schema::table('pesanan', function (Blueprint $table) {
            $table->dropColumn('nama');
            $table->dropColumn('kursi_id');
            $table->dropColumn('no_telp');
        });

        Schema::create('penumpang', function (Blueprint $table) {
           $table->id();
           $table->string('nama');
           $table->string('nik');
           $table->string('email');
           $table->string('no_telp');
           $table->unsignedInteger('kursi_id')->unique();
           $table->unsignedInteger('pesanan_id');
           $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penumpang');

        Schema::table('pesanan', function (Blueprint $table) {
            $table->string('nama');
            $table->unsignedInteger('kursi_id');
            $table->string('no_telp');
            $table->foreign(['kursi_id'], 'fk_pesanan_kursi')->references(['id'])->on('kursi')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }
};
