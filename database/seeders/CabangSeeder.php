<?php

namespace Database\Seeders;

use App\Models\MasterCabang;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CabangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dataCabang = [
            'Lampung',
            'Palembang',
            'Jakarta',
            'Cibinong',
            'Metro',
            'Karawang',
            'Cikarang',
            'Cileungsi',
            'Daya Murni',
            'Depok'
        ];

        foreach ($dataCabang as $cabang) {
            MasterCabang::create([
                'nama' => $cabang
            ]);
        }
    }
}
