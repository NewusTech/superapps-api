<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mobil_rental', function (Blueprint $table) {
            $table->decimal('biaya_all_in',10,2)->default(0)->nullable();
            $table->decimal('biaya_sewa',10,2)->default(0)->nullable()->change();
        });

        Schema::table('rental', function (Blueprint $table) {
            $table->integer('all_in')->default(0)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('rental', function (Blueprint $table) {
            $table->dropColumn('all_in');
        });

        Schema::table('mobil_rental', function (Blueprint $table) {
            $table->dropColumn('biaya_all_in');
            $table->string('biaya_sewa')->nullable()->change();
        });
    }
};
