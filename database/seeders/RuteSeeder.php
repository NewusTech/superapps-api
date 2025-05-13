<?php

namespace Database\Seeders;

use App\Models\MasterRute;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RuteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dataRuteSeeder =  [
            [
                'kota_asal' => 1, // Bandar Lampung
                'kota_tujuan' => 2, // Palembang
                'harga' => 250000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kota_asal' => 2, // Palembang
                'kota_tujuan' => 3, // Jakarta
                'harga' => 250000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kota_asal' => 1, // Bandar Lampung
                'kota_tujuan' => 3, // Jakarta
                'harga' => 300000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        foreach ($dataRuteSeeder as $item) {
            MasterRute::create($item);
        }

    }
}
