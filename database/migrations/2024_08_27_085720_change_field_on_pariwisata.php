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
        Schema::table('pariwisata', function (Blueprint $table) {
            $table->dropColumn('sub-judul');
            $table->string('sub_judul')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pariwisata', function (Blueprint $table) {
            $table->dropColumn('sub_judul');
            $table->string('sub-judul')->nullable();
        });
    }
};
