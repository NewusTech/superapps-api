<?php

namespace App\Http\Controllers\API\Jadwal;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JadwalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('check.admin')->only(['store', 'update', 'destroy']);
    }
    public function index()
    {
        try {
            $data = Jadwal::with('master_rute', 'master_mobil.kursi', 'master_supir')->get();
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
                'master_rute_id' => 'required',
                'master_mobil_id' => 'required',
                'master_supir_id' => 'required',
                'waktu_keberangkatan' => 'required',
                'tanggal_berangkat' => 'required',
                'ketersediaan' => 'required',
            ]);

            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            }

            $existing = Jadwal::where('master_supir_id', $request->master_supir_id);
            if ($existing->exists()) {
                throw new Exception('Jadwal dengan supir yang sama sudah ada');
            }

            $data = new Jadwal();
            $data->master_rute_id = $request->master_rute_id;
            $data->master_mobil_id = $request->master_mobil_id;
            $data->master_supir_id = $request->master_supir_id;
            $data->tanggal_berangkat = $request->tanggal_berangkat;
            $data->waktu_keberangkatan = $request->waktu_keberangkatan;
            $data->ketersediaan = $request->ketersediaan;
            $data->save();

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

        try {
            $user = auth()->user()->roles->first()->name;
            if ($user != 'Admin') {
                throw new Exception('Anda bukan Admin');
            }

            $validator = Validator::make($request->all(), [
                'master_rute_id' => 'required',
                'master_mobil_id' => 'required',
                'master_supir_id' => 'required',
                'waktu_keberangkatan' => 'required',
                'tanggal_berangkat' => 'required',
                'ketersediaan' => 'required'
            ]);
            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            }

            $data = Jadwal::find($id);

            $data->master_rute_id = $request->master_rute_id;
            $data->master_mobil_id = $request->master_mobil_id;
            $data->master_supir_id = $request->master_supir_id;
            $data->waktu_keberangkatan = $request->waktu_keberangkatan;
            $data->ketersediaan = $request->ketersediaan;
            $data->save();
            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Berhasil get data'
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function destroy(string $id)
    {
        try {
            $data = Jadwal::find($id);
            if (!$data) {
                throw new Exception('Jadwal tidak ditemukan');
            }

            $data->delete();
            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Berhasil get data'
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
