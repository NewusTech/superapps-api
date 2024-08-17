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
            $table->dropColumn('available_seats');
        });

        Schema::table('jadwal', function (Blueprint $table) {
            $table->integer('available_seats')->after('waktu_keberangkatan')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_mobil', function (Blueprint $table) {
            $table->integer('available_seats')->after('fasilitas')->nullable()->default(0);
        });

        Schema::table('jadwal', function (Blueprint $table) {
            $table->dropColumn('available_seats');
        });
    }
};
