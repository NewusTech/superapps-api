<?php

namespace App\Http\Controllers\API\MobilRental;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMobilRentalRequest;
use App\Models\MobilRental;
use App\Services\ImageService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MobilRentalController extends Controller
{

    protected  $imageService;
    public function __construct(ImageService $imageService)
    {
        $this->middleware('auth:api', ['except' => ['index', 'show']]);
        $this->middleware('check.admin')->only(['store', 'update', 'destroy']);
        $this->imageService = $imageService;
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

    public function create() {}

    public function store(StoreMobilRentalRequest $request)
    {
        try {
            DB::beginTransaction();

            $validatedData = $request->validated();
            $mobilRental = MobilRental::create($validatedData);

            $this->imageService->storeMobilRentalImages($request, $mobilRental);

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil create data',
                'data' => $mobilRental,
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function show(string $id)
    {
        try {
            $data = MobilRental::with('images')->findOrFail($id);
            $data->images->map(function ($mobil) {
                return $mobil->image_url;
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
