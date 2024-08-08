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
           $table->enum('seat_code', ['Commuter-14','Hiace-10','Premio-9','Premio-8'])->default('Hiace-10');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_mobil', function(Blueprint $table){
            $table->dropColumn('seat_code');
        });
    }
};
