<?php

namespace App\Http\Controllers\API\Rental;

use App\Http\Controllers\Controller;
use App\Http\Requests\Rental\StoreRentalRequest;
use App\Models\MobilRental;
use App\Models\Rental;
use App\Services\RentalPaymentService;
use Exception;
use Illuminate\Http\Request;

class RentalController extends Controller
{
    protected $paymentService;
    public function __construct(RentalPaymentService $paymentService)
    {
        $this->middleware('auth:api');
        $this->middleware('check.admin')->only(['store', 'update', 'destroy']);
        $this->paymentService = $paymentService;
    }
    public function index()
    {
        try {
            $data = Rental::get();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil get data',
                'data' => $data
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function getMobil()
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
        try {
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }


    public function processPayment(StoreRentalRequest $request)
    {
        try {
            $validatedData = $request->validated();
            return $this->paymentService->processRentalPayment($validatedData);
        } catch (Exception $th) {
            return response()->json(['message' => "Unxecepted error: {$th->getMessage()}"], 500);
        }
    }


    public function show(string $id)
    {
        //
    }


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
