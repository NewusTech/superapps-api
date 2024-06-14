<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('metode_pembayaran', function (Blueprint $table) {
            $table->id();
            $table->string('metode')->nullable();
            $table->string('keterangan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('pembayaran', function (Blueprint $table) {
            $table->foreignId('metode_id')->nullable()->constrained('metode_pembayaran')->onDelete('no action')->onUpdate('no action');
        });
    }

     public function down(): void
    {

        Schema::table('pembayaran', function (Blueprint $table) {
            $table->dropForeign(['metode_id']);
            $table->dropColumn('metode_id');
        });
        Schema::dropIfExists('metode_pembayaran');
    }
};
