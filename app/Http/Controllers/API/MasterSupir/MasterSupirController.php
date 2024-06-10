<?php

namespace App\Http\Controllers\API\MasterSupir;

use App\Http\Controllers\Controller;
use App\Models\MasterSupir;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MasterSupirController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('check.admin')->only(['store', 'update', 'destroy']);
    }

    public function index()
    {
        try {
            $data = MasterSupir::all();
            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Berhasil get data'
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nama' => 'required',
                'no_telp' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            }

            $existingData = MasterSupir::where('nama', $request->nama)->first();

            if ($existingData) {
                throw new Exception('Data dengan nama yang sama sudah ada.');
            }

            $master_supir = new MasterSupir();
            $master_supir->nama = $request->nama;
            $master_supir->no_telp = $request->no_telp;
            $master_supir->save();

            return response()->json([
                'success' => true,
                'data' => $master_supir,
                'message' => 'Berhasil menambah data'
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nama' => 'required',
                'no_telp' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            }

            $where = ['id' => $id];
            $collection = MasterSupir::where($where)->first();
            if (!$collection) {
                return response()->json([
                    'success' => false,
                    'data' => '',
                    'message' => 'ID tidak ditemukan'
                ]);
            }

            $master_supir = MasterSupir::find($id);
            $master_supir->nama = $request->nama;
            $master_supir->no_telp = $request->no_telp;
            $master_supir->save();

            return response()->json([
                'success' => true,
                'data' => $master_supir,
                'message' => 'Berhasil update data'
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy(string $id)
    {
        try {

            $where = ['id' => $id];
            $collection = MasterSupir::where($where)->first();
            if (!$collection) {
                return response()->json([
                    'success' => false,
                    'data' => '',
                    'message' => 'ID tidak ditemukan'
                ]);
            }
            $data = MasterSupir::find($id);
            $data->delete();

            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Berhasil delete data'
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
