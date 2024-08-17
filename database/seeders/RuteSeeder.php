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
                'kota_asal' => 'Lampung',
                'kota_tujuan' => 'Palembang',
                'harga' => 250000,
            ], [
                'kota_asal' => 'Palembang',
                'kota_tujuan' => 'Lampung',
                'harga' => 250000,
            ],[
                'kota_asal' => 'Lampung',
                'kota_tujuan' => 'Jakarta',
                'harga' => 350000,
            ],[
                'kota_asal' => 'Lampung',
                'kota_tujuan' => 'Depok',
                'harga' => 350000,
            ],[
                'kota_asal' => 'Lampung',
                'kota_tujuan' => 'Bogor',
                'harga' => 500000,
            ],[
                'kota_asal' => 'Lampung',
                'kota_tujuan' => 'Cileungsi',
                'harga' => 450000,
            ],[
                'kota_asal' => 'Lampung',
                'kota_tujuan' => 'Cikarang',
                'harga' => 450000,
            ],[
                'kota_asal' => 'Lampung',
                'kota_tujuan' => 'Tambun Bekasi Timur',
                'harga' => 400000,
            ],[
                'kota_asal' => 'Lampung',
                'kota_tujuan' => 'Jonggol',
                'harga' => 450000,
            ],[
                'kota_asal' => 'Lampung',
                'kota_tujuan' => 'Sawangan',
                'harga' => 450000,
            ],[
                'kota_asal' => 'Lampung',
                'kota_tujuan' => 'Ciawi',
                'harga' => 550000,
            ], [
                'kota_asal' => 'Lampung',
                'kota_tujuan' => 'Karawang',
                'harga' => 650000,
            ], [
                'kota_asal' => 'Lampung',
                'kota_tujuan' => 'Cilegon',
                'harga' => 350000,
            ],[
                'kota_asal' => 'Lampung',
                'kota_tujuan' => 'Pakupatan',
                'harga' => 350000,
            ], [
                'kota_asal' => 'Lampung',
                'kota_tujuan' => 'Tangerang Kota',
                'harga' => 350000,
            ], [
                'kota_asal' => 'Daya Murni',
                'kota_tujuan' => 'Jakarta',
                'harga' => 380000,
            ], [
                'kota_asal' => 'Daya Murni',
                'kota_tujuan' => 'Jakarta',
                'harga' => 380000,
            ], [
                'kota_asal' => 'Daya Murni',
                'kota_tujuan' => 'Depok',
                'harga' => 380000,
            ], [
                'kota_asal' => 'Daya Murni',
                'kota_tujuan' => 'Cibinong',
                'harga' => 480000,
            ], [
                'kota_asal' => 'Daya Murni',
                'kota_tujuan' => 'Cileungsi',
                'harga' => 480000,
            ],[
                'kota_asal' => 'Daya Murni',
                'kota_tujuan' => 'Cikarang',
                'harga' => 480000,
            ], [
                'kota_asal' => 'Daya Murni',
                'kota_tujuan' => 'Bogor',
                'harga' => 530000,
            ], [
                'kota_asal' => 'Daya Murni',
                'kota_tujuan' => 'Bekasi Timur',
                'harga' => 430000,
            ], [
                'kota_asal' => 'Daya Murni',
                'kota_tujuan' => 'Jonggol',
                'harga' => 480000,
            ],[
                'kota_asal' => 'Daya Murni',
                'kota_tujuan' => 'Sawangan',
                'harga' => 480000,
            ],
        ];
        foreach ($dataRuteSeeder as $item) {
            MasterRute::create($item);
        }

    }
}
