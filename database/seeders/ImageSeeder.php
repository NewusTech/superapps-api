<?php

namespace Database\Seeders;

use App\Models\Image;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for($i = 1; $i <= 5; $i++) {
            Image::create([
                'image' => 'https://newus-bucket.s3.ap-southeast-2.amazonaws.com/dir_mpp_lokal/video/1721809399371-3.jfif'
            ]);
        }
    }
}
