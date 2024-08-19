<?php

namespace App\Http\Controllers\API\Rental;

use App\Http\Controllers\Controller;
use App\Http\Requests\Rental\StoreRentalRequest;
use App\Models\Rental;
use App\Services\RentalPaymentService;
use Exception;
use Illuminate\Http\Request;

class RentalController extends Controller
{
    protected $paymentService;
    public function __construct(RentalPaymentService $paymentService){
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRentalRequest $request)
    {
        try {
            $validatedData = $request->validated();
            return $this->paymentService->processRentalPayment($validatedData);
        } catch (Exception $th) {
            return response()->json(['message' => "Unxecepted error: {$th->getMessage()}"], 500);
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
