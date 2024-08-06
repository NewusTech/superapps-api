<?php

namespace App\Http\Controllers\API\MasterMobil;

use App\Http\Controllers\Controller;
use App\Models\Kursi;
use App\Models\MasterMobil;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MasterMobilController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('check.admin')->only([ 'update', 'destroy']);
    }

    public function index(Request $request)
    {

        try {
            $master_mobil = MasterMobil::get(['id', 'nopol', 'type', 'jumlah_kursi', 'available_seats', 'fasilitas','status','created_at']);
            return response()->json([
                'success' => true,
                'data' => $master_mobil,
                'message' => 'Berhasil get data'
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nopol' => 'required',
                'type' => 'required',
                'jumlah_kursi' => 'required|numeric',
            ]);
            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            }

            $existingData = MasterMobil::where('nopol', $request->nopol)->first();

            if ($existingData) {
                return response()->json([
                    'success' => false,
                    'data' => '',
                    'message' => 'Data dengan nopol yang sama sudah ada.'
                ]);
            }


            $data = MasterMobil::create([
                'nopol' => $request->nopol,
                'type' => $request->type,
                'jumlah_kursi' => $request->jumlah_kursi,
                'status' => 'Non-Aktif',
                'image_url' => $request->image_url,
                'available_seats' => $request->jumlah_kursi,
                'fasilitas' => $request->fasilitas
            ]);

            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Berhasil create data'
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $data = MasterMobil::find($id);
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
        try {
            $validator = Validator::make($request->all(), [
                'nopol' => 'required',
                'type' => 'required',
                'jumlah_kursi' => 'required|numeric',
                'image_url' => 'required'
            ]);
            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            }

            $where = ['id' => $id];
            $collection = MasterMobil::where($where)->first();
            if (!$collection) {
                return response()->json([
                    'success' => false,
                    'data' => '',
                    'message' => 'ID tidak ditemukan'
                ]);
            }

            $data = MasterMobil::find($id);
            $data->nopol = $request->nopol;
            $data->type = $request->type;
            $data->jumlah_kursi = $request->jumlah_kursi;
            $data->image_url = $request->image_url;
            $data->save();

            if ($data->jumlah_kursi == $request->jumlah_kursi) {
                $kursi = Kursi::where('master_mobil_id', $id);
                $kursi->delete();

                for ($i = 0; $i < $request->jumlah_kursi; $i++) {
                    Kursi::create([
                        'master_mobil_id' => $data->id,
                        'status' => 'Kosong',
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Berhasil update data'
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $where = ['id' => $id];
            $collection = MasterMobil::where($where)->first();
            if (!$collection) {
                return response()->json([
                    'success' => false,
                    'data' => '',
                    'message' => 'ID tidak ditemukan'
                ]);
            }
            $data = MasterMobil::find($id);
            $data->delete();

            Kursi::where('master_mobil_id', $id)->delete();

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
