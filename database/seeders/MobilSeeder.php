<?php

namespace Database\Seeders;

use App\Models\MasterMobil;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MobilSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dataMobilSeeder =  [
            [
                'nopol' => 'B 7039 ZAA',
                'type' => 'Toyota HIACE',
                'jumlah_kursi' => 10,
                'status' => 'aktif',
                'fasilitas' => "Makan Siang",
                'image_url' => 'https://newus-bucket.s3.ap-southeast-2.amazonaws.com/dir_mpp_lokal/video/1721809399371-3.jfif'
            ]
        ];
        foreach ($dataMobilSeeder as $item) {
            MasterMobil::create($item);
        }
    }
}
