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
        Schema::create('mobil_travel_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('master_mobil_id');
            $table->string('image_url');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('mobil_rental_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('mobil_rental_id');
            $table->string('image_url');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mobil_travel_images');
        Schema::dropIfExists('mobil_rental_images');
    }
};
