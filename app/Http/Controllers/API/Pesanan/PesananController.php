<?php

namespace App\Http\Controllers\API\Pesanan;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Jadwal;
use App\Models\Kursi;
use App\Models\MasterMobil;
use App\Models\Penumpang;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PesananController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('check.admin')->only(['konfirmasiPesanan', 'destroy']);
    }

    public function index(Request $request)
    {
        try {
            $pesanan = Pesanan::with('jadwal', 'jadwal.master_rute', 'jadwal.master_mobil', 'jadwal.master_supir', 'user')
                ->orderBy('created_at', 'desc');
            if ($request->status) {
                $pesanan = $pesanan->where('status', 'like',"%$request->status%");
            }

            if ($request->startDate && $request->endDate) {
                $startDate = date('Y-m-d 00:00:00', strtotime($request->startDate));
                $endDate = date('Y-m-d 23:59:59', strtotime($request->endDate));
                $pesanan = $pesanan->whereBetween('created_at', [$startDate, $endDate]);
            }

            if ($request->filled('search')) {
                $searchTerm = $request->search;
                $pesanan = $pesanan->where(function ($query) use ($searchTerm) {
                        $query->whereHas('user', function ($query) use ($searchTerm) {
                            $query->where('nama', 'like', "%{$searchTerm}%");
                        })
                        ->orWhereHas('jadwal.master_rute', function ($query) use ($searchTerm) {
                            $query->where('kota_asal', 'like', "%{$searchTerm}%")
                            ->orWhere('kota_tujuan', 'like', "%{$searchTerm}%");
                        })
                        ->orWhereHas('jadwal.master_mobil', function ($query) use ($searchTerm) {
                            $query->where('type', 'like', "%{$searchTerm}%");
                        })
                        ->orWhereHas('jadwal.master_supir', function ($query) use ($searchTerm) {
                            $query->where('nama', 'like', "%{$searchTerm}%");
                        });
                });
            }

            $pesanan = $pesanan->get();
            $total_uang = 0;
            $data = $pesanan->map(function ($pesanan) use (&$total_uang) {
                $total_uang += $pesanan->jadwal->master_rute->harga;
                return [
                    'kode_pesanan' => $pesanan->kode_pesanan,
                    'nama_pemesan' => $pesanan->user->nama,
                    'rute' => $pesanan->jadwal->master_rute->kota_asal . ' - ' . $pesanan->jadwal->master_rute->kota_tujuan,
                    'jam_berangkat' => date('H:i', strtotime($pesanan->jadwal->waktu_keberangkatan)),
                    'tanggal_berangkat' => date('d-m-Y', strtotime($pesanan->jadwal->tanggal_berangkat)),
                    'mobil' => $pesanan->jadwal->master_mobil->type . ' - ' . $pesanan->jadwal->master_mobil->nopol,
                    'supir' => $pesanan->jadwal->master_supir->nama,
                    'harga' => $pesanan->jadwal->master_rute->harga,
                    'status' => $pesanan->status
                ];
            });
            $total_pesanan = $data->count();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil get data',
                'data' => $data,
                'total_pesanan' => $total_pesanan,
                'total_uang' => $total_uang
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($orderCode)
    {
        try {
            if (!$orderCode) {
                throw new Exception('Id tidak ditemukan');
            }
            $data = Pesanan::with('penumpang')->where('kode_pesanan', $orderCode)->first();
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
                'jadwal_id' => 'required',
                'metode_id' => 'required',
                'titik_jemput_id' => 'required',
                'titik_antar_id' => 'required',
                'penumpang' => 'required'
            ]);

            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            }

            $existJadwal = Jadwal::find($request->jadwal_id);
            if (!$existJadwal) {
                throw new Exception('Jadwal tidak ditemukan');
            }
            $mobilByJadwal = $existJadwal->master_mobil_id;
            $kursiExist = Kursi::where('master_mobil_id', $mobilByJadwal)->get('nomor_kursi');
            $kursiExist = $kursiExist->map(function ($kursi) {
                return $kursi->nomor_kursi;
            });

            $pesanan = new Pesanan();
            $pesanan->jadwal_id = $request->jadwal_id;
            $pesanan->master_titik_jemput_id = $request->titik_jemput_id;
            $pesanan->titik_antar_id = $request->titik_antar_id;
            $pesanan->metode_id = $request->metode_id;
            $pesanan->biaya_tambahan = str_contains($request->metode_id, 'Payment') ? 4000 : 0;
            $pesanan->user_id = auth()->user()->id;
            $pesanan->status = "Menunggu";
            if (!$pesanan->save()) {
                throw new Exception('Pesanan gagal dibuat');
            }

            foreach ($request->penumpang as $penumpang) {
                if (in_array($penumpang['no_kursi'], $kursiExist->toArray())) {
                    throw new Exception("Kursi " . $penumpang['no_kursi'] . " tidak tersedia");
                }
                $kursi = Kursi::create([
                    'master_mobil_id' => $mobilByJadwal,
                    'nomor_kursi' => $penumpang['no_kursi']
                ]);

                Penumpang::create([
                    'nama' => $penumpang['nama'],
                    'nik' => $penumpang['nik'],
                    'email' => $penumpang['email'],
                    'kursi_id' => $kursi->id,
                    'pesanan_id' => $pesanan->id,
                    'no_telp' => $penumpang['no_telp'],
                    'status' => 'terisi'
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => $pesanan,
                'message' => 'Pesanan berhasil dibuat'
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
