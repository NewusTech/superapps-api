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
        Schema::table('kursi', function (Blueprint $table) {
            $table->integer('jadwal_id')->nullable()->after('master_mobil_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kursi', function (Blueprint $table) {
            $table->dropColumn('jadwal_id');
        });
    }
};
