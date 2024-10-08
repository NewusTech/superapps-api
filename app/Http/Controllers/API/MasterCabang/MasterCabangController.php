<?php

namespace App\Http\Controllers\API\MasterCabang;

use App\Http\Controllers\Controller;
use App\Models\MasterCabang;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MasterCabangController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index', 'show']]);
        $this->middleware('check.admin')->only(['store', 'update', 'destroy']);
    }

    public function index()
    {
        try {
            $data = MasterCabang::all();
            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Berhasil get data'
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nama' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => $validator->errors()->first()
                    ], 422);
            }

            $existingData = MasterCabang::where('nama', $request->nama)->first();

            if ($existingData) {
                return response()->json([
                    'success' => false,
                    'data' => $existingData,
                    'message' => 'Data sudah ada'
                ]);
            }

            $master_cabang = new MasterCabang();
            $master_cabang->nama = $request->nama;
            $master_cabang->alamat = $request->alamat;
            $master_cabang->save();

            return response()->json([
                'success' => true,
                'data' => $master_cabang,
                'message' => 'Berhasil menambah data'
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function show($id){
        try {
            $data = MasterCabang::find($id);
            if (!$data) {
                return response()->json([
                    'success' => false,
                    'data' => $data,
                    'message' => 'ID tidak ditemukan'
                ]);
            }
            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Berhasil get data'
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nama' => 'required',
            ]);

            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            }

            $where = ['id' => $id];
            $collection = MasterCabang::where($where)->first();
            if (!$collection) {
                return response()->json([
                    'success' => false,
                    'data' => '',
                    'message' => 'ID tidak ditemukan'
                ]);
            }

            $master_cabang = MasterCabang::find($id);
            $master_cabang->nama = $request->nama;
            $master_cabang->alamat = $request->alamat;
            $master_cabang->save();

            return response()->json([
                'success' => true,
                'data' => $master_cabang,
                'message' => 'Berhasil update data'
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function destroy(string $id)
    {
        try {

            $where = ['id' => $id];
            $collection = MasterCabang::where($where)->first();
            if (!$collection) {
                return response()->json([
                    'success' => false,
                    'data' => '',
                    'message' => 'ID tidak ditemukan'
                ]);
            }
            $data = MasterCabang::find($id);
            $data->delete();

            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Berhasil delete data'
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
