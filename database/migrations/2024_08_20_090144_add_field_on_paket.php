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
        Schema::table('paket', function (Blueprint $table) {
            $table->string('tujuan')->nullable();
            $table->string('no_telp_pengirim')->nullable();
            $table->string('no_telp_penerima')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('paket', function (Blueprint $table) {
            $table->dropColumn('tujuan');
            $table->dropColumn('no_telp_penerima');
            $table->dropColumn('no_telp_pengirim');
        });
    }
};
