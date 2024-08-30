<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rental', function (Blueprint $table) {
            $table->string('username_ig')->nullable();
            $table->string('username_fb')->nullable();
            $table->string('image_ktp')->nullable();
            $table->string('image_swafoto')->nullable();
            $table->text('catatan_sopir')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('rental', function (Blueprint $table) {
            $table->dropColumn(['username_ig', 'username_fb', 'image_ktp', 'image_swafoto', 'catatan_sopir']);
        });
    }
};
