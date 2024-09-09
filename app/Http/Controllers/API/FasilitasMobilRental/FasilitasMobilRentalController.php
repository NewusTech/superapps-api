<?php

namespace App\Http\Controllers\API\FasilitasMobilRental;

use App\Http\Controllers\Controller;
use App\Models\FasilitasMobilRental;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FasilitasMobilRentalController extends Controller
{
    public function __construct(){
        $this->middleware('auth:api');
        $this->middleware('check.admin')->only(['update', 'destroy']);
    }
    public function index()
    {
        try {

            $data = FasilitasMobilRental::all();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil get data',
                'data' => $data
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function show()
    {
        //
    }
    public function store(Request $request)
    {
        try {
            $validatedData = Validator::make($request->all(), [
                'nama' => 'required',
            ]);

            if ($validatedData->fails()) return response()->json(['message' => $validatedData->errors()]);

            $data = new FasilitasMobilRental();
            $data->nama = $request->nama;
            $data->save();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil created',
                'data' => $data
            ], 201);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try{
            $data = FasilitasMobilRental::find($id);
            if (!$data) return response()->json([
                'success' => false,
                'message' => 'ID tidak ditemukan'
            ]);

            $data->update($request->all());
            return response()->json([
                'success' => true,
                'message' => 'Berhasil update data',
                'data' => $data
            ]);

        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }
}
