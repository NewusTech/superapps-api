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
        Schema::create('pembayaran_rental', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pembayaran');
            $table->unsignedBigInteger('rental_id');
            $table->string('nominal')->nullable();
            $table->string('payment_link')->nullable();
            $table->enum('status', ['Menunggu Pembayaran', 'Sukses', 'Gagal', 'Kadaluwarsa'])->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('rental', function (Blueprint $table) {
            $table->unsignedInteger('metode_id');
            $table->unsignedInteger('mobil_rental_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rental', function (Blueprint $table) {
            $table->dropColumn('metode_id');
            $table->dropColumn('mobil_rental_id');
        });

        Schema::dropIfExists('pembayaran_rental');
    }
};
