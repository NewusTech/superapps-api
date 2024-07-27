<?php

namespace App\Http\Controllers\API\Jadwal;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Kursi;
use App\Models\MasterMobil;
use App\Models\MasterRute;
use Carbon\Carbon;
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

    public function getJadwalByRute(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'from' => 'required',
                'to' => 'required',
                'date' => 'required',
            ]);

            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            }
            $date = Carbon::parse($request->date)->format('Y-m-d');
            $rute = MasterRute::where('kota_asal', $request->from)->where('kota_tujuan', $request->to)->first();
            if (!$rute) {
                return response()->json([
                    'success' => true,
                    'data' => $rute,
                    'message' => 'Rute tidak ditemukan'
                ], 404);
            }

            $jadwal = Jadwal::where('master_rute_id', $rute->id)
                ->whereDate('tanggal_berangkat', $date)
                ->get([
                    "id",
                    "master_rute_id",
                    "master_mobil_id",
                    "master_supir_id",
                    "tanggal_berangkat"
                ]);
            if (!$jadwal) {
                return response()->json([
                    'success' => true,
                    'data' => $jadwal,
                    'message' => 'Rute tidak ditemukan'
                ], 404);
            }
            $jadwal->map(function ($item) {
                $mobil = MasterMobil::where('id', $item->master_mobil_id)->first();
                $rute = MasterRute::where('id', $item->master_rute_id)->first();
                $item->img_url = $mobil->image_url;
                $item->availableSeat = $mobil->jumlah_kursi - Kursi::where('master_mobil_id', $item->master_mobil_id)->where('status', 'Terisi')->count();
                $item->carModel = $mobil->type;
                $item->carSeat = $mobil->jumlah_kursi;
                $item->departureDate = $item->tanggal_berangkat;
                $item->destinationDepartureDate = $item->tanggal_berangkat;
                $item->originDepartureDate = $item->tanggal_berangkat;
                $item->originCity = $rute->kota_asal;
                $item->destinationCity = $rute->kota_tujuan;
                $item->price = $rute->harga;
                $item->facility = 'free meal';
                $seatTaken = Kursi::where('master_mobil_id', $item->master_mobil_id)->where('status', 'Terisi')->get('nomor_kursi');
                $item->seatTaken = $seatTaken->map(fn ($item) => $item->nomor_kursi);
                $item->syarat_dan_ketentuan = "<p>Syarat dan Ketentuan berlaku.</p>";
            });
            return response()->json([
                'success' => true,
                'data' => $jadwal,
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

            $existing = Jadwal::where('master_supir_id', $request->master_supir_id)
                ->where('tanggal_berangkat', $request->tanggal_berangkat)
                ->where('master_mobil_id', $request->master_mobil_id)
                ->where('waktu_keberangkatan', $request->waktu_keberangkatan)->first();
            if ($existing) {
                throw new Exception('Jadwal dengan data yang sama sudah ada');
            }
            // dd($existing, $request->all());

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
        try {
            $data = Jadwal::with('master_rute', 'master_mobil.kursi', 'master_supir')->find($id);
            if (!$data) {
                return response()->json(['message' => 'Jadwal not found'], 404);
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
