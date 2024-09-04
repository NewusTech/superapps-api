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
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->string('bukti_url')->nullable();
        });
        Schema::table('pembayaran_rental', function (Blueprint $table) {
            $table->string('bukti_url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->dropColumn('bukti_url');
        });
        Schema::table('pembayaran_rental', function (Blueprint $table) {
            $table->dropColumn('bukti_url');
        });
    }
};
