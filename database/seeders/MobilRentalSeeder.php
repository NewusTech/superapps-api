<?php

namespace Database\Seeders;

use App\Models\MobilRental;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MobilRentalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mobil = [
            [
                'nopol' => 'B 1234 ZAA',
                'type' => 'Hi Ace Premio',
                'jumlah_kursi' => 12,
                'fasilitas' => 'Heatback, AC, Radio, USB, Bluetooth',
                'image_url' => 'https://newus-bucket.s3.ap-southeast-2.amazonaws.com/dir_mpp_lokal/video/1721809399371-3.jfif',
                'mesin' => '2000 CC',
                'transmisi' => 'Manual',
                'kapasitas_bagasi' => '50L',
                'bahan_bakar' => 'Bensin',
                'biaya_sewa' => 1800000,
                'biaya_all_in' => 1000000,
            ],
            [
                'nopol' => 'B 5678 ZAA',
                'type' => 'Hi Ace Commuter',
                'jumlah_kursi' => 16,
                'fasilitas' => 'AC, Radio, USB, Bluetooth',
                'image_url' => 'https://newus-bucket.s3.ap-southeast-2.amazonaws.com/dir_mpp_lokal/video/1721809399371-3.jfif',
                'mesin' => '1800 CC',
                'transmisi' => 'Otomatis',
                'kapasitas_bagasi' => '80 L',
                'bahan_bakar' => 'Bensin',
                'biaya_sewa' => 1500000,
                'biaya_all_in' => 1000000,
            ],
            // tambahkan data lainnya...
        ];

        foreach ($mobil as $data) {
            MobilRental::create($data);
        }
        $fasilitas = [1, 2, 3, 4, 5];
        foreach (MobilRental::all() as $mobilRental) {
            $mobilRental->fasilitas()->attach($fasilitas);
        }
    }
}
