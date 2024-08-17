<?php

namespace Database\Seeders;

use App\Models\MasterSupir;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SupirSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dataSupirSeeder =  [[
            'nama' => 'Heri',
            'no_telp' => '0812345678912'
        ], [
            'nama' => 'Budi',
            'no_telp' => '084141512912'
        ]];
        foreach ($dataSupirSeeder as $item) {
            MasterSupir::create($item);
        };
    }
}
