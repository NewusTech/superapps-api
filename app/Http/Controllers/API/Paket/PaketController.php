<?php

namespace App\Http\Controllers\API\Paket;

use App\Http\Controllers\Controller;
use App\Models\Paket;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaketController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('check.admin')->only(['update', 'destroy']);
    }
    public function index()
    {
        try {
            $data = Paket::all();
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nama_pengirim' => 'required',
                'nama_penerima' => 'required',
                'alamat_pengirim' => 'required',
                'alamat_penerima' => 'required',
                'tanggal_dikirim' => 'required',
                'jenis_paket' => 'required',
                'status' => 'required',
                'biaya' => 'required|numeric',
                'total_berat' => 'required|numeric',
            ]);
            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            }

            $paket = new Paket();
            $paket->nama_pengirim = $request->nama_pengirim;
            $paket->nama_penerima = $request->nama_penerima;
            $paket->alamat_pengirim = $request->alamat_pengirim;
            $paket->alamat_penerima = $request->alamat_penerima;
            $paket->tanggal_dikirim = $request->tanggal_dikirim;
            $paket->tanggal_diterima = $request->tanggal_diterima;
            $paket->jenis_paket = $request->jenis_paket;
            $paket->status = $request->status;
            $paket->biaya = $request->biaya;
            $paket->total_berat = $request->total_berat;
            $paket->save();

            return response()->json([
                'success' => true,
                'data' => $paket,
                'message' => 'Berhasil created'
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $data = Paket::find($id);
            if (!$data) {
                throw new Exception('Data not found');
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
                'nama_pengirim' => 'required',
                'nama_penerima' => 'required',
                'alamat_pengirim' => 'required',
                'alamat_penerima' => 'required',
                'tanggal_dikirim' => 'required',
                'jenis_paket' => 'required',
                'status' => 'required',
                'biaya' => 'required|numeric',
                'total_berat' => 'required|numeric',
            ]);
            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            }

            $where = ['id' => $id];
            $collection = Paket::where($where)->first();
            if (!$collection) {
                return response()->json([
                    'success' => false,
                    'data' => '',
                    'message' => 'ID tidak ditemukan'
                ]);
            }

            $data = Paket::find($id);
            $data->nama_pengirim = $request->nama_pengirim;
            $data->nama_penerima = $request->nama_penerima;
            $data->alamat_pengirim = $request->alamat_pengirim;
            $data->alamat_penerima = $request->alamat_penerima;
            $data->tanggal_dikirim = $request->tanggal_dikirim;
            $data->tanggal_diterima = $request->tanggal_diterima;
            $data->jenis_paket = $request->jenis_paket;
            $data->status = $request->status;
            $data->biaya = $request->biaya;
            $data->total_berat = $request->total_berat;
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $where = ['id' => $id];
            $collection = Paket::where($where)->first();
            if (!$collection) {
                return response()->json([
                    'success' => false,
                    'data' => '',
                    'message' => 'ID tidak ditemukan'
                ]);
            }
            $data = Paket::find($id);
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
