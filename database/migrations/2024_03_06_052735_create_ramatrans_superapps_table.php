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

        Schema::create('master_cabang', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama')->nullable();
            $table->string('email');
            $table->string('no_telp')->nullable();
            $table->string('password');
            $table->unsignedInteger('master_cabang_id');
            $table->foreignId('role_id')->constrained('roles')->onDelete('no action')->onUpdate('no action');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign(['master_cabang_id'], 'fk_users_master_cabang')->references(['id'])->on('master_cabang')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
        Schema::create('master_mobil', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nopol')->nullable();
            $table->string('type')->nullable();
            $table->integer('jumlah_kursi')->nullable();
            $table->string('status')->nullable();
            $table->string('image_url')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('kursi', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('master_mobil_id');
            $table->string('status')->nullable();
            $table->integer('nomor_kursi')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign(['master_mobil_id'], 'fk_kursi_master_mobil')->references(['id'])->on('master_mobil')->onUpdate('CASCADE')->onDelete('CASCADE');
        });


        Schema::create('master_supir', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama');
            $table->string('no_telp')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('master_rute', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kota_asal')->nullable();
            $table->string('kota_tujuan')->nullable();
            $table->integer('harga')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('master_titik_jemput', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama');
            $table->unsignedInteger('master_cabang_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign(['master_cabang_id'], 'fk_master_titik_jemput_master_cabang')->references(['id'])->on('master_cabang')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });

        Schema::create('jadwal', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('master_rute_id');
            $table->unsignedInteger('master_mobil_id');
            $table->unsignedInteger('master_supir_id');
            $table->date('tanggal_berangkat')->nullable();
            $table->time('waktu_keberangkatan')->nullable();
            $table->string('ketersediaan')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign(['master_rute_id'], 'fk_jadwal_master_rute')->references(['id'])->on('master_rute')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['master_mobil_id'], 'fk_jadwal_master_mobil')->references(['id'])->on('master_mobil')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['master_supir_id'], 'fk_jadwal_master_supir')->references(['id'])->on('master_supir')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });

        Schema::create('pesanan', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kode_pesanan')->unique();
            $table->unsignedInteger('jadwal_id');
            $table->string('nama');
            $table->string('no_telp');
            $table->unsignedInteger('master_titik_jemput_id');
            $table->unsignedInteger('kursi_id');
            $table->unsignedInteger('biaya_tambahan')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign(['kursi_id'], 'fk_pesanan_kursi')->references(['id'])->on('kursi')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['jadwal_id'], 'fk_pesanan_jadwal')->references(['id'])->on('jadwal')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['master_titik_jemput_id'], 'fk_pesanan_master_titik_jemput')->references(['id'])->on('master_titik_jemput')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });

        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pembayaran')->unique();
            $table->unsignedInteger('pesanan_id');
            $table->enum('status', ['Menunggu Pembayaran', 'Sukses', 'Gagal', 'Kadaluarsa'])->default('Menunggu Pembayaran');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign(['pesanan_id'], 'fk_pembayaran_pesanan')->references(['id'])->on('pesanan')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });

        Schema::create('paket', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama_pengirim');
            $table->string('nama_penerima');
            $table->text('alamat_pengirim');
            $table->text('alamat_penerima');
            $table->date('tanggal_dikirim');
            $table->date('tanggal_diterima')->nullable();
            $table->string('jenis_paket');
            $table->string('status');
            $table->unsignedInteger('biaya');
            $table->unsignedDouble('total_berat');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('fk_users_master_cabang');
        });

        Schema::dropIfExists('master_cabang');
        Schema::dropIfExists('master_mobil');
        Schema::dropIfExists('master_supir');
        Schema::dropIfExists('master_rute');
        Schema::dropIfExists('master_titik_jemput');
        Schema::dropIfExists('jadwal');
        Schema::dropIfExists('pesanan');
        Schema::dropIfExists('paket');
        Schema::dropIfExists('kursi');
    }
};
