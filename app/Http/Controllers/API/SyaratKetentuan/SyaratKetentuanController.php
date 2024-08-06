<?php

namespace App\Http\Controllers\API\SyaratKetentuan;

use App\Http\Controllers\Controller;
use App\Models\SyaratKetentuan;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SyaratKetentuanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $data = SyaratKetentuan::all();
            if($data->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'data' => $data,
                    'message' => 'Data tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Berhasil get data'
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function create()
    {

    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'description' => 'required'
            ]);
            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            }
            $data = SyaratKetentuan::create($request->all());
            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Berhasil get data'
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
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
