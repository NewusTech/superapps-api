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
        Schema::create('penginapan', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('lokasi');
            $table->integer('jumlah_kamar');
            $table->integer('luas_ruangan');
            $table->float('rating');
            $table->decimal('harga');
            $table->string('tipe');
            $table->enum('status', ['tersedia', 'tidak tersedia'])->default('tersedia');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('fasilitas', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('fasilitas_penginapan', function (Blueprint $table) {
            $table->id();
            $table->integer('fasilitas_id');
            $table->integer('penginapan_id');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('kebijakan', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('deskripsi');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('kebijakan_penginapan', function (Blueprint $table) {
            $table->id();
            $table->integer('penginapan_id');
            $table->string('kebijakan_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penginapan');
        Schema::dropIfExists('fasilitas');
        Schema::dropIfExists('fasilitas_penginapan');
        Schema::dropIfExists('kebijakan_hotel');
        Schema::dropIfExists('kebijakan_penginapan');
    }
};
