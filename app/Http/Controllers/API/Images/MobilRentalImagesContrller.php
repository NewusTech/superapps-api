<?php

namespace App\Http\Controllers\API\Images;

use App\Http\Controllers\Controller;
use App\Models\MobilRentalImages;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MobilRentalImagesContrller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $data = MobilRentalImages::get();
            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Berhasil get data'
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = Validator::make($request->all(), [
                'mobil_rental_id' => 'required',
                'image_url' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            if ($validatedData->fails()) return response()->json($validatedData->errors());

            $data = $request->all();
            if ($request->hasFile('image_url')) {
                $file = $request->file('image_url');
                $gambarPath = $file->store('superapps/travel/mobil', 's3');
                $fullUrl = 'https://' . env('AWS_BUCKET') . '.' . 's3' . '.' . env('AWS_DEFAULT_REGION') . '.' . 'amazonaws.com/' . $gambarPath;
                $data['image_url'] = $fullUrl;
            } else {
                $data['image_url'] = null;
            }

            $data = MobilRentalImages::create($data);
            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Berhasil create data'
            ], 201);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
