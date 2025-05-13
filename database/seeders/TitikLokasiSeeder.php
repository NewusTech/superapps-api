<?php

namespace Database\Seeders;

use App\Models\MasterTitikJemput;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TitikLokasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'nama' => 'Loket Ramatranz Lampung Teluk (Jl. Mayor Salim Batubara)',
                'master_cabang_id' => 1
            ],[
                'nama' => 'Perum Puri Gading',
                'master_cabang_id' => 1
            ]
        ];

        foreach ($data as $titik) {
            MasterTitikJemput::create([
                'nama' => $titik['nama'],
                'master_cabang_id' => $titik['master_cabang_id']
            ]);
        }
    }
}
