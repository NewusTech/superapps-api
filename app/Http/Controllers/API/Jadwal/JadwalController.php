<?php

namespace App\Http\Controllers\API\Jadwal;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Kursi;
use App\Models\MasterCabang;
use App\Models\MasterMobil;
use App\Models\MasterRute;
use App\Models\MasterSupir;
use App\Models\MasterTitikJemput;
use App\Models\SyaratKetentuan;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function getJadwalPerTanggal($tanggal){
        try {
            $jadwal = Jadwal::with('master_rute', 'master_mobil', 'master_supir')->whereDate('tanggal_berangkat', $tanggal)->get();
            return response()->json([
                'success' => true,
                'data' => $jadwal,
                'message' => 'Berhasil get data'
            ]);

        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function getJadwalByRute(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'from' => 'required',
                'to' => 'required',
                'date' => 'required',
                'seats' => 'required'
            ]);

            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            }
            $date = Carbon::parse($request->date)->format('Y-m-d');
            $rute = MasterRute::where('kota_asal', 'like', "%$request->from%")->where('kota_tujuan', 'like',  "%$request->to%")->first();
            if (!$rute) {
                return response()->json([
                    'success' => true,
                    'data' => $rute,
                    'message' => 'Rute tidak ditemukan'
                ], 404);
            }
            $jadwal = Jadwal::where('master_rute_id', $rute->id)
                ->whereDate('tanggal_berangkat', $date)
                ->whereRaw("available_seats >= {$request->seats}")
                ->orderBy('waktu_keberangkatan', 'asc')
                ->get([
                    "id",
                    "master_rute_id",
                    "master_mobil_id",
                    "master_supir_id",
                    "tanggal_berangkat",
                    "waktu_keberangkatan",
                    "available_seats"
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
                $item->carModel = $mobil->type;
                $item->carSeat = $mobil->jumlah_kursi;
                $item->departureTime = $item->waktu_keberangkatan;
                $item->departureDate = $item->tanggal_berangkat;
                $item->destinationDepartureDate = $item->tanggal_berangkat;
                $item->originDepartureDate = $item->tanggal_berangkat;
                $item->originCity = $rute->kota_asal;
                $item->destinationCity = $rute->kota_tujuan;
                $item->transitionCity = $item->originCity !== 'Lampung' && $item->destinationCity !== 'Lampung' ? 'Lampung': '';
                $item->price = $rute->harga;
                $item->facility = $mobil->fasilitas;
                $seatTaken = Kursi::where('jadwal_id', $item->id)->where('status', 'like', '%terisi%')->get('nomor_kursi');
                $item->seatTaken = $seatTaken->map(fn($item) => $item->nomor_kursi);
                $item->availableSeat = $item->available_seats;
                $item->syarat_dan_ketentuan = SyaratKetentuan::first('description')->description ?? null;
            });
            return response()->json([
                'success' => true,
                'data' => $jadwal,
                'message' => 'Berhasil get data'
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }


    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $requestData = $request->all();
            $createdData = [];

            foreach ($requestData as $data) {
                $validator = Validator::make($data, [
                    'master_rute_id' => 'required',
                    'master_mobil_id' => 'required',
                    'master_supir_id' => 'required',
                    'waktu_keberangkatan' => 'required',
                    'waktu_tiba' => 'required',
                    'tanggal_berangkat' => 'required',
                ]);

                if ($validator->fails()) {
                    throw new Exception($validator->errors()->first());
                }

                $existing = Jadwal::where('master_supir_id', $data['master_supir_id'])
                    ->where('tanggal_berangkat', $data['tanggal_berangkat'])
                    ->where('master_mobil_id', $data['master_mobil_id'])
                    ->where('waktu_keberangkatan', $data['waktu_keberangkatan'])
                    ->first();

                if ($existing) {
                    throw new Exception('Jadwal dengan data yang sama sudah ada untuk salah satu data dalam array');
                }

                $jadwal = new Jadwal();
                $jadwal->master_rute_id = $data['master_rute_id'];
                $jadwal->master_mobil_id = $data['master_mobil_id'];
                $jadwal->master_supir_id = $data['master_supir_id'];
                $jadwal->tanggal_berangkat = $data['tanggal_berangkat'];
                $jadwal->waktu_keberangkatan = $data['waktu_keberangkatan'];
                $jadwal->waktu_tiba= $data['waktu_tiba'];
                $jadwal->ketersediaan = $data['ketersediaan'] ?? 'Tersedia';
                $jadwal->save();

                $createdData[] = $jadwal;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $createdData,
                'message' => 'Berhasil menyimpan semua data'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }


    public function show(string $id)
    {
        try {
            $data = Jadwal::with('master_rute', 'master_mobil.kursi', 'master_supir')->find($id);
            if (!$data) {
                return response()->json(['message' => 'Jadwal not found'], 404);
            }

            $cabangJemput = MasterCabang::where('nama', 'like', '%' . $data->master_rute->kota_asal . '%')->first(['id', 'nama']);
            $cabangAntar = MasterCabang::where('nama', 'like', '%' . $data->master_rute->kota_tujuan . '%')->first(['id', 'nama']);
            $titikJemput = MasterTitikJemput::where('master_cabang_id', $cabangJemput->id)->get(['id', 'nama']);
            $titikAntar = MasterTitikJemput::where('master_cabang_id', $cabangAntar->id)->get(['id', 'nama']);
            $data = [
                'id' => $data->id,
                'mobil_id' => $data->master_mobil_id,
                'titik_jemput' => $titikJemput,
                'titik_antar' => $titikAntar,
            ];
            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Berhasil get data'
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function dropdownJadwal()
    {
        try {
            $rute = MasterRute::get(['id', 'kota_asal', 'kota_tujuan']);
            $rute = $rute->map(function ($item) {
                return [
                    'id' => $item->id,
                    'rute' => "$item->kota_asal - $item->kota_tujuan",
                ];
            });
            $supir = MasterSupir::get(['id', 'nama', 'no_telp']);
            $mobil = MasterMobil::where('status', 'not like', '%non%')->get(['id', 'type', 'nopol']);
            $mobil = $mobil->map(function ($item) {
                return [
                    'id' => $item->id,
                    'nama' => "$item->type - $item->nopol",
                ];
            });

            $data = [
                'rute' => $rute,
                'supir' => $supir,
                'mobil' => $mobil,
            ];
            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Berhasil get data'
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function edit(string $id)
    {
        try {
            $data = Jadwal::find($id);
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

    public function update(Request $request, string $id)
    {

        try {

            $validator = Validator::make($request->all(), [
                'master_rute_id' => 'required',
                'master_mobil_id' => 'required',
                'master_supir_id' => 'required',
                'waktu_keberangkatan' => 'required',
            ]);
            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            }

            $data = Jadwal::find($id);
            if (!$data) {
                return response()->json(['message' => 'Jadwal tidak ditemukan'], 404);
            }

            $data->master_rute_id = $request->master_rute_id;
            $data->master_mobil_id = $request->master_mobil_id;
            $data->master_supir_id = $request->master_supir_id;
            $data->waktu_keberangkatan = $request->waktu_keberangkatan;
            $data->ketersediaan = $request->ketersediaan ?? 'tersedia';
            $data->save();
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
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
