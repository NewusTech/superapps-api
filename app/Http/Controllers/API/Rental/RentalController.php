<?php

namespace App\Http\Controllers\API\Rental;

use App\Http\Controllers\Controller;
use App\Http\Requests\Rental\StoreRentalRequest;
use App\Models\MobilRental;
use App\Models\Rental;
use App\Services\RentalPaymentService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function riwayat(Request $request)
    {
        try {
            $data = Rental::with('pembayaran', 'mobil')
                ->where('user_id', Auth::user()->id);

            if ($request->has('status')) {
                $response = $data->whereHas('pembayaran', function ($q) use ($request) {
                    $q->whereRaw('LOWER(status) LIKE ?', ["%{$request->status}%"]);
                });
            }
            $data = $data->get();
            $response = $data->map(function ($rental) {
                return [
                    'created_at' => $rental->created_at,
                    'kode_pembayaran' => $rental->pembayaran->kode_pembayaran,
                    'mobil_type' => $rental->mobil->type,
                    'area' => $rental->area,
                    'tanggal_awal_sewa' => $rental->tanggal_mulai_sewa,
                    'tanggal_akhir_sewa' => $rental->tanggal_akhir_sewa,
                    'status' => $rental->pembayaran->status,
                ];
            });
            return response()->json([
                'success' => true,
                'message' => 'Berhasil get data',
                'data' => $response
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
