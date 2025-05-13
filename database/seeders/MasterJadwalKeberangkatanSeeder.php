<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterJadwalKeberangkatanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('master_jadwal_keberangkatan')->insert([
            [
                'nama_shift' => 'Pagi',
                'waktu_keberangkatan' => '08:00:00',
                'waktu_tiba' => '10:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_shift' => 'Siang',
                'waktu_keberangkatan' => '14:00:00',
                'waktu_tiba' => '15:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_shift' => 'Malam',
                'waktu_keberangkatan' => '19:00:00',
                'waktu_tiba' => '21:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
