<?php

namespace Database\Seeders;

use App\Models\FasilitasMobilRental;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FasilitasMobilRentalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fasilitas = [
            'AC',
            'Radio',
            'USB',
            'Bluetooth',
            'Heatback',
        ];

        foreach ($fasilitas as $fasilitasItem) {
            FasilitasMobilRental::create([
                'nama' => $fasilitasItem,
            ]);
        }
    }
}
