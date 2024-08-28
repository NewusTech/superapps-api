<?php

namespace Database\Seeders;

use App\Models\Kebijakan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KebijakanHotelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kebijakans = [
            [
                'title' => 'Check-in',
                'deskripsi' => 'Check-in dari pukul 14:00',
            ],
            [
                'title' => 'Check-out',
                'deskripsi' => 'Check-out sebelum pukul 12:00',
            ],
            [
                'title' => 'Deposit',
                'deskripsi' => 'Deposit sebesar 20% diperlukan saat pemesanan',
            ],
            [
                'title' => 'Umur',
                'deskripsi' => 'Anak-anak di bawah 12 tahun menginap gratis',
            ],
            [
                'title' => 'Hewan Peliharaan',
                'deskripsi' => 'Hewan peliharaan tidak diperbolehkan',
            ],
            [
                'title' => 'Maksimal Tamu',
                'deskripsi' => 'Maksimal 4 tamu per kamar',
            ],
        ];

        foreach ($kebijakans as $kebijakan) {
            Kebijakan::create($kebijakan);
        }
    }
}
