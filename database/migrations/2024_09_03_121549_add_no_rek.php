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
        Schema::table('metode_pembayaran', function (Blueprint $table) {
            $table->string('no_rek')->nullable();
            $table->string('bank')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('metode_pembayaran', function (Blueprint $table) {
            $table->dropColumn('no_rek');
            $table->dropColumn('bank');
        });
    }
};
