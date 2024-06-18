<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->dropForeign(['metode_id']);
            $table->dropColumn('metode_id');
        });

        Schema::table('pesanan', function (Blueprint $table) {
            $table->foreignId('metode_id')->after('user_id')->nullable()->constrained('metode_pembayaran')->onDelete('no action')->onUpdate('no action');
        });
    }


    public function down(): void
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->foreignId('metode_id')->nullable()->constrained('metode_pembayaran')->onDelete('no action')->onUpdate('no action');
        });

        Schema::table('pesanan', function (Blueprint $table) {
            $table->dropForeign(['metode_id']);
            $table->dropColumn('metode_id');
        });
    }
};
