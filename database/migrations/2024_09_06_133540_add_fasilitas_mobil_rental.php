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
        Schema::create('fasilitas_mobil_rental', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('fasilitas_mobil', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('mobil_rental_id')->nullable();
            $table->unsignedInteger('fasilitas_mobil_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fasilitas_mobil');
        Schema::dropIfExists('fasilitas_mobil_rental');
    }
};
