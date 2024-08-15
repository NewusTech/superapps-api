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
        Schema::table('metode_pembayaran', function(Blueprint $table){
            $table->decimal('biaya_tambahan')->default(0)->nullable();
        });

        Schema::table('pesanan', function(Blueprint $table){
            $table->dropColumn('biaya_tambahan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('metode_pembayaran', function(Blueprint $table){
            $table->dropColumn('biaya_tambahan');
        });
        Schema::table('pesanan', function(Blueprint $table){
            $table->decimal('biaya_tambahan')->nullable();
        });
    }
};
