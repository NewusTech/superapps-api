<?php

namespace App\Http\Controllers\API\Pesanan;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Jadwal;
use App\Models\Kursi;
use App\Models\MasterMobil;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PesananController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('check.admin')->only(['konfirmasiPesanan', 'destroy']);
    }

    public function index()
    {
        try {
            $data = Pesanan::with('jadwal', 'jadwal.master_rute', 'jadwal.master_mobil', 'jadwal.master_supir')->get();
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
                'no_telp' => 'required',
                'no_kursi' => 'required|numeric',
                'jadwal_id' => 'required',
                'titik_jemput_id' => 'required',
            ]);

            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            }

            $existJadwal = Jadwal::find($request->jadwal_id);
            if (!$existJadwal) {
                throw new Exception('Jadwal tidak ditemukan');
            }

            $mobilByJadwal = Jadwal::find($request->jadwal_id)->master_mobil_id;
            $jumlahTotalKursi = MasterMobil::where('id', $mobilByJadwal)->first()->jumlah_kursi;

            if ($request->no_kursi > $jumlahTotalKursi) {
                throw new Exception('Kursi tidak tersedia');
            }

            $kursi = new Kursi();
            $kursi->master_mobil_id = $mobilByJadwal;
            $kursi->nomor_kursi = $request->no_kursi;
            $kursi->save();

            $data = new Pesanan();
            $data->nama = $request->nama;
            $data->no_telp = $request->no_telp;
            $data->jadwal_id = $request->jadwal_id;
            $data->master_titik_jemput_id = $request->titik_jemput_id;
            $data->biaya_tambahan = $request->biaya_tambahan;
            $data->kursi_id = $kursi->id;
            $data->status = auth()->user()->roles == "Customer" ? "Menunggu" : "Sukses";
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



    public function konfirmasiPesanan(Request $request)
    {
        try {
            $where = ['id' => $request->id];

            $data = Pesanan::where($where)->first();
            $data->update([
                'status' => 'Sukses'
            ]);

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
            $data = Pesanan::find($id);
            if (!$data) {
                throw new Exception('Pesanan tidak ditemukan');
            }
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
