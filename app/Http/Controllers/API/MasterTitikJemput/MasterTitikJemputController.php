<?php

namespace App\Http\Controllers\API\MasterTitikJemput;

use App\Http\Controllers\Controller;
use App\Models\MasterCabang;
use App\Models\MasterTitikJemput;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MasterTitikJemputController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index', 'show']]);
        $this->middleware('check.admin')->only(['store', 'update', 'destroy']);
    }

    public function index(Request $request)
    {
        try {
            $data = MasterTitikJemput::with('master_cabang');
            if ($request->cabang) {
                $cabang = MasterCabang::where('nama', 'like', "%{$request->cabang}%")->first();
                $data->where('master_cabang_id', $cabang->id);
            }
            $data = $data->get();
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
                'master_cabang_id' => 'required',
            ]);

            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            }

            $existingData = MasterTitikJemput::where('nama', $request->nama)->first();

            if ($existingData) {
                throw new Exception('Data dengan nama yang sama sudah ada.');
            }

            $data = new MasterTitikJemput();
            $data->nama = $request->nama;
            $data->master_cabang_id = $request->master_cabang_id;
            $data->save();

            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Berhasil menambah data'
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
                'master_cabang_id' => 'required',
            ]);

            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            }

            $where = ['id' => $id];
            $collection = MasterTitikJemput::where($where)->first();
            if (!$collection) {
                return response()->json([
                    'success' => false,
                    'data' => '',
                    'message' => 'ID tidak ditemukan'
                ]);
            }

            $data = MasterTitikJemput::find($id);
            $data->nama = $request->nama;
            $data->master_cabang_id = $request->master_cabang_id;
            $data->save();

            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Berhasil update data'
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function show(string $id)
    {
        try {
            $data = MasterTitikJemput::find($id);
            if (!$data) {
                return response()->json(['message' => 'Jadwal not found'], 404);
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

    public function destroy(string $id)
    {
        try {

            $where = ['id' => $id];
            $collection = MasterTitikJemput::where($where)->first();
            if (!$collection) {
                return response()->json([
                    'success' => false,
                    'data' => '',
                    'message' => 'ID tidak ditemukan'
                ]);
            }
            $data = MasterTitikJemput::find($id);
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
