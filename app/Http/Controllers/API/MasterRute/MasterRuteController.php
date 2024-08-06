<?php

namespace App\Http\Controllers\API\MasterRute;

use App\Http\Controllers\Controller;
use App\Models\MasterRute;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MasterRuteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('check.admin')->only(['store', 'update', 'destroy']);
    }
    public function index(Request $request)
    {
        try {
            $data = MasterRute::get();
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
        //
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'kota_asal' => 'required',
                'kota_tujuan' => 'required',
                'harga' => 'required|numeric'
            ]);

            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            }

            $existingData = MasterRute::where('kota_asal', $request->kota_asal)
                ->where('kota_tujuan', $request->kota_tujuan)
                ->first();

            if ($existingData) {
                throw new Exception('Data dengan kota_asal dan kota_tujuan yang sama sudah ada.');
            }

            $master_rute = new MasterRute();
            $master_rute->kota_asal = $request->kota_asal;
            $master_rute->kota_tujuan = $request->kota_tujuan;
            $master_rute->harga = $request->harga;
            $master_rute->waktu_keberangkatan = $request->waktu_keberangkatan;
            $master_rute->save();

            return response()->json([
                'success' => true,
                'data' => $master_rute,
                'message' => 'Berhasil menambah data'
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show(string $id)
    {
        try {
            $data = MasterRute::find($id);
            if (!$data) {
                return response()->json('Data not found', 404);
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

    public function edit(string $id)
    {

    }

    public function update(Request $request, string $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'kota_asal' => 'required',
                'kota_tujuan' => 'required',
                'harga' => 'required|numeric'
            ]);

            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            }

            $where = ['id' => $id];
            $collection = MasterRute::where($where)->first();
            if (!$collection) {
                return response()->json([
                    'success' => false,
                    'data' => '',
                    'message' => 'ID tidak ditemukan'
                ]);
            }

            $data = MasterRute::find($id);
            $data->kota_asal = $request->kota_asal;
            $data->kota_tujuan = $request->kota_tujuan;
            $data->harga = $request->harga;
            $data->save();

            return response()->json([
                'success' => true,
                'data' => $data,
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
            $collection = MasterRute::where($where)->first();
            if (!$collection) {
                return response()->json([
                    'success' => false,
                    'data' => '',
                    'message' => 'ID tidak ditemukan'
                ]);
            }
            $data = MasterRute::find($id);
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
