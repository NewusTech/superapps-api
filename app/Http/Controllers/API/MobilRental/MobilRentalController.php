<?php

namespace App\Http\Controllers\API\MobilRental;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMobilRentalRequest;
use App\Models\MobilRental;
use Exception;
use Illuminate\Http\Request;

class MobilRentalController extends Controller
{

    public function __construct(){
        $this->middleware('auth:api');
        $this->middleware('check.admin')->only(['store', 'update', 'destroy']);
    }
    public function index()
    {
        try {
            $data = MobilRental::get();
            $data->map(function ($mobil) {
                $mobil->bagasi = "Heatback";
            });
            return response()->json([
                'success' => true,
                'message' => 'Berhasil get data',
                'data' => $data,
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function create()
    {
    }

    public function store(StoreMobilRentalRequest $request)
    {
        try {
            $validatedData = $request->validated();
            MobilRental::create($validatedData);
            return response()->json(['message' => 'Berhasil create data'], 201);
        } catch (Exception $e)  {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

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
