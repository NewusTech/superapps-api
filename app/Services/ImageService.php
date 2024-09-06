<?php

namespace App\Services;

use App\Models\MobilRentalImages;

class ImageService
{
    public function __construct() {}


    public function getImages() {}

    public function storeMobilRentalImages($request, $mobilRental)
    {
        $data = $request->all();

        if ($request->hasFile('images')) {
            $imageUrls = [];
            $files = $request->file('images');
            foreach ($files as $file) {
                $gambarPath = $file->store('superapps/travel/mobil', 's3');
                $fullUrl = 'https://' . env('AWS_BUCKET') . '.' . 's3' . '.' . env('AWS_DEFAULT_REGION') . '.' . 'amazonaws.com/' . $gambarPath;
                $imageUrls[] = $fullUrl;
            }
            $data['images'] = $fullUrl;
        } else {
            $data['images'] = [];
        }

        foreach ($imageUrls as $url) {
            $data = new MobilRentalImages();
            $data->mobil_rental_id = $mobilRental->id;
            $data->image_url = $url;
            $data->save();
        }
    }
}
