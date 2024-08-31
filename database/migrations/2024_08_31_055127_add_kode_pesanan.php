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
        Schema::table('rental', function (Blueprint $table) {
            $table->string('kode_pesanan')->nullable()->default('-')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rental', function (Blueprint $table) {
            $table->dropColumn('kode_pesanan');
        });
    }
};
