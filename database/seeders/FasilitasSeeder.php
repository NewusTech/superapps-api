<?php

namespace Database\Seeders;

use App\Models\Fasilitas;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FasilitasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fasilitas = [
            'AC',
            'Ruang Keluarga',
            'Kamar Mandi',
            'Dapur',
            'Wi-Fi',
        ];

        foreach ($fasilitas as $fasilitasItem) {
            Fasilitas::create([
                'nama' => $fasilitasItem,
            ]);
        }
    }
}
