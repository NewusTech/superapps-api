<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->decimal('amount')->default(0);
        });

        Schema::table('penumpang', function (Blueprint $table) {
           $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->dropColumn('amount');
        });

        Schema::table('penumpang', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
