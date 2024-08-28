<?php

namespace Database\Seeders;

use App\Models\Penginapan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PenginapanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $penginapans = [
            [
                'title' => 'Penginapan A',
                'lokasi' => 'Lokasi A',
                'jumlah_kamar' => 2,
                'luas_ruangan' => 36,
                'rating' => 5,
                'harga' => 100000,
                'tipe' => 'apartemen',
                'status' => 'tersedia',
            ],
            [
                'title' => 'Penginapan B',
                'lokasi' => 'Lokasi B',
                'jumlah_kamar' => 3,
                'luas_ruangan' => 50,
                'rating' => 5,
                'harga' => 100000,
                'tipe' => 'apartemen',
                'status' => 'tersedia',
            ],
        ];

        foreach ($penginapans as $penginapan) {
            Penginapan::create($penginapan);
        }

        $fasilitas = [1, 2, 3];
        foreach (Penginapan::all() as $penginapan) {
            $penginapan->fasilitas()->attach($fasilitas);
        }

        $kebijakan = [1, 2];
        foreach (Penginapan::all() as $penginapan) {
            $penginapan->kebijakan()->attach($kebijakan);
        }

        foreach (Penginapan::all() as $penginapan) {
            $penginapan->image()->attach([1,3,4]);
        }
    }
}
