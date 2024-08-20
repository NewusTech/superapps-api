<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran_paket', function (Blueprint $table) {
            $table->id();
            $table->string('kode_paket');
            $table->unsignedBigInteger('paket_id');
            $table->unsignedBigInteger('metode_id');
            $table->string('status');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('paket', function (Blueprint $table) {
           $table->string('resi')->unique()->after('id');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('pembayaran_paket');
        Schema::table('paket', function (Blueprint $table) {
            $table->dropColumn('resi');
        });
    }
};
