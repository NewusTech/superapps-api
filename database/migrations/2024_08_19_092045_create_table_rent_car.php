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
        Schema::create('mobil_rental', function (Blueprint $table) {
            $table->id();
            $table->string('nopol');
            $table->string('type');
            $table->string('jumlah_kursi');
            $table->string('fasilitas')->nullable();
            $table->string('image_url')->nullable();
            $table->string('mesin')->nullable();
            $table->string('transmisi')->nullable();
            $table->string('kapasitas_bagasi')->nullable();
            $table->string('bahan_bakar')->nullable();
            $table->string('biaya_sewa')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mobil_rental');
    }
};
