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
            ], [
                'nopol' => 'B 7040 ZAA',
                'type' => 'Toyota HIACE',
                'jumlah_kursi' => 10,
                'status' => 'aktif',
                'fasilitas' => "Makan Siang",
                'image_url' => 'https://newus-bucket.s3.ap-southeast-2.amazonaws.com/dir_mpp_lokal/video/1721809399371-3.jfif'
            ], [
                'nopol' => 'B 7035 ZAA',
                'type' => 'Toyota HIACE',
                'jumlah_kursi' => 10,
                'fasilitas' => "Makan Siang",
                'status' => 'aktif',
                'image_url' => 'https://newus-bucket.s3.ap-southeast-2.amazonaws.com/dir_mpp_lokal/video/1721809399371-3.jfif'
            ],[
                'nopol' => 'B 7042 ZAA',
                'type' => 'Toyota HIACE',
                'jumlah_kursi' => 10,
                'fasilitas' => "Makan Siang",
                'status' => 'aktif',
                'image_url' => 'https://newus-bucket.s3.ap-southeast-2.amazonaws.com/dir_mpp_lokal/video/1721809399371-3.jfif'
            ],[
                'nopol' => 'B 7044 ZAA',
                'type' => 'Toyota HIACE',
                'jumlah_kursi' => 10,
                'fasilitas' => "Makan Siang",
                'status' => 'aktif',
                'image_url' => 'https://newus-bucket.s3.ap-southeast-2.amazonaws.com/dir_mpp_lokal/video/1721809399371-3.jfif'
            ],[
                'nopol' => 'B 7046 ZAA',
                'type' => 'Toyota HIACE',
                'jumlah_kursi' => 10,
                'fasilitas' => "Makan Siang",
                'status' => 'aktif',
                'image_url' => 'https://newus-bucket.s3.ap-southeast-2.amazonaws.com/dir_mpp_lokal/video/1721809399371-3.jfif'
            ],[
                'nopol' => 'B 7048 ZAA',
                'type' => 'Toyota HIACE',
                'jumlah_kursi' => 10,
                'fasilitas' => "Makan Siang",
                'status' => 'aktif',
                'image_url' => 'https://newus-bucket.s3.ap-southeast-2.amazonaws.com/dir_mpp_lokal/video/1721809399371-3.jfif'
            ],[
                'nopol' => 'B 7049 ZAA',
                'type' => 'Toyota HIACE',
                'jumlah_kursi' => 10,
                'fasilitas' => "Makan Siang",
                'status' => 'aktif',
                'image_url' => 'https://newus-bucket.s3.ap-southeast-2.amazonaws.com/dir_mpp_lokal/video/1721809399371-3.jfif'
            ],[
                'nopol' => 'B 7050 ZAA',
                'type' => 'Toyota HIACE',
                'jumlah_kursi' => 10,
                'fasilitas' => "Makan Siang",
                'status' => 'aktif',
                'image_url' => 'https://newus-bucket.s3.ap-southeast-2.amazonaws.com/dir_mpp_lokal/video/1721809399371-3.jfif'
            ],
        ];
        foreach ($dataMobilSeeder as $item) {
            MasterMobil::create($item);
        }
    }
}
