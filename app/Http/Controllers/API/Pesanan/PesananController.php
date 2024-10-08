<?php

namespace App\Http\Controllers\API\Pesanan;

use App\Http\Controllers\Controller;
use App\Jobs\CancelOrder;
use App\Models\Pesanan;
use App\Models\Jadwal;
use App\Models\Kursi;
use App\Models\MasterMobil;
use App\Models\Penumpang;
use App\Services\OrderService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PesananController extends Controller
{
    protected $orderService;
    public function __construct(OrderService $orderService)
    {
        $this->middleware('auth:api');
        $this->middleware('check.admin')->only(['konfirmasiPesanan', 'destroy']);
        $this->orderService = $orderService;
    }

    public function index(Request $request)
    {
        try {
            $pesanan = Pesanan::with('pembayaran','jadwal', 'jadwal.master_rute', 'jadwal.master_mobil', 'jadwal.master_supir', 'user', 'pembayaran')
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
                    'kode_pembayaran'=> $pesanan->pembayaran->kode_pembayaran ?? null,
                    'nama_pemesan' => $pesanan->user->nama,
                    'rute' => $pesanan->jadwal->master_rute->kota_asal . ' - ' . $pesanan->jadwal->master_rute->kota_tujuan,
                    'jam_berangkat' => date('H:i', strtotime($pesanan->jadwal->waktu_keberangkatan)),
                    'tanggal_berangkat' => date('d-m-Y', strtotime($pesanan->jadwal->tanggal_berangkat)),
                    'mobil' => $pesanan->jadwal->master_mobil->type . ' - ' . $pesanan->jadwal->master_mobil->nopol,
                    'supir' => $pesanan->jadwal->master_supir->nama,
                    'harga' => $pesanan->pembayaran->amount ?? $pesanan->jadwal->master_rute->harga * $pesanan->penumpang->count(),
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
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function getAllHistoryPesanan(Request $request){
        try {
            $status = $request->input('status');
            $data = $this->orderService->getAllOrders($status);
            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Berhasil get data'
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function getDetailPesanan($orderCode){
        try {
            if (!$orderCode) {
                return response()->json([
                        'success' => false,
                        'message' => 'Pesanan tidak ditemukan'
                    ], 404);
            }
            $data = $this->orderService->getOrderDetails($orderCode);
            if(!$data) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pesanan tidak ditemukan',
                    'data' => $data
                ], 404);
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
    public function show($orderCode)
    {
        try {
            if (!$orderCode) {
                throw new Exception('Id tidak ditemukan');
            }
            $data = Pesanan::with(['penumpang.kursi','titikJemput', 'titikAntar', 'jadwal.master_rute', 'jadwal.master_mobil'])->where('kode_pesanan', $orderCode)->first();
            $totalHarga = $data->jadwal->master_rute->harga * $data->penumpang->count();

            if (!$data) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Pesanan tidak ditemukan'
                    ]
                );
            }
            $penumpang = $data->penumpang->map(function ($item) {
                return [
                    'nama' => $item->nama,
                    'nik' => $item->nik,
                    'email' => $item->email,
                    'no_telp'=> $item->no_telp,
                    'kursi' => $item->kursi->nomor_kursi
                ];
            });

            $data = [
                'mobil' => $data->jadwal->master_mobil->type . ' - ' . $data->jadwal->master_mobil->nopol,
                'rute' => $data->jadwal->master_rute->kota_asal . ' - ' . $data->jadwal->master_rute->kota_tujuan,
                'titik_jemput' => $data->titikJemput->nama,
                'titik_antar' => $data->titikAntar->nama,
                'jam_berangkat' => date('H:i', strtotime($data->jadwal->waktu_keberangkatan)),
                'total_harga' => $totalHarga,
                'penumpang' => $penumpang,
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

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'jadwal_id' => 'required',
                'titik_jemput_id' => 'required',
                'titik_antar_id' => 'required',
                'nama' => 'required',
                'no_telp' => 'required',
                'email' => 'required',
                'nik' => 'required',
                'penumpang' => 'required'
            ]);

            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            }
            $jadwal = Jadwal::find($request->jadwal_id);
            if (!$jadwal) {
                throw new Exception('Jadwal tidak ditemukan');
            }

            $kursiExist = Kursi::where('jadwal_id', $jadwal->id)->get('nomor_kursi');
            $kursiExist = $kursiExist->map(function ($kursi) {
                return $kursi->nomor_kursi;
            });

            DB::beginTransaction();
            $pesanan = new Pesanan();
            $pesanan->jadwal_id = $request->jadwal_id;
            $pesanan->master_titik_jemput_id = $request->titik_jemput_id;
            $pesanan->titik_antar_id = $request->titik_antar_id;
            $pesanan->nama = $request->nama;
            $pesanan->no_telp = $request->no_telp;
            $pesanan->email = $request->email;
            $pesanan->nik = $request->nik;
            $pesanan->user_id = auth()->user()->id;
            $pesanan->status = "Menunggu Pembayaran";

            if (!$pesanan->save()) {
                throw new Exception('Pesanan gagal dibuat');
            }

            foreach ($request->penumpang as $penumpang) {
                $kursi = Kursi::where('jadwal_id', $jadwal->id)->where('status','like' ,'%kosong%')->where('nomor_kursi', $penumpang['no_kursi'])->first();
                if (!$kursi) {
                    throw new Exception("Kursi " . $penumpang['no_kursi'] . " tidak tersedia", 409);
                }
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

            DB::commit();
            CancelOrder::dispatch($pesanan)->delay(now()->addMinutes(15));
            return response()->json([
                'success' => true,
                'data' => $pesanan,
                'message' => 'Pesanan berhasil dibuat'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function pesananByUserId(){
        try {
            $data = Pesanan::where('user_id', auth()->user()->id)->get();
            if(!$data){
                return response()->json([
                    'success' => false,
                    'data' => $data,
                    'message' => 'Pesanan tidak ditemukan'
                ], 404);
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
            return response()->json(['message' => $e->getMessage()], 500);
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
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
