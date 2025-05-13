<?php

namespace Database\Seeders;

use App\Models\MasterCabang;
use Illuminate\Database\Seeder;

class CabangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dataCabang = [
            [
                'nama' => 'Bandar Lampung',
                'alamat' => 'Jl. Salim Batubara No.118, Kupang Teba, Kec. Tlk. Betung Utara, Kota Bandar Lampung, Lampung 35212',
                'kode_provinsi' => '18',
                'kode_kota' => '1871'
            ],
            [
                'nama' => 'Palembang',
                'alamat' => 'Jl. Mayor Santoso No.3112, 20 Ilir D. III, Kec. Ilir Tim. I, Kota Palembang, Sumatera Selatan 30121',
                'kode_provinsi' => '16',
                'kode_kota' => '1671'
            ],
            [
                'nama' => 'Jakarta',
                'alamat' => 'Podomoro Golf View Ruko B1 - 76, Bojong Nangka, Kec. Cimanggis, Kabupaten Bogor, Jawa Barat 16963',
                'kode_provinsi' => '31',
                'kode_kota' => '3171'
            ]
        ];

        foreach ($dataCabang as $cabang) {
            MasterCabang::create($cabang);
        }
    }
}
