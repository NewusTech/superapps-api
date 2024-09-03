<?php

namespace App\Http\Controllers\API\Perjalanan;

use App\Helpers\FilterHelper;
use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Pesanan;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class PerjalananController extends Controller
{
    public function index(Request $request){
        try {
            $perjalanan = Jadwal::query()->with('master_rute', 'master_mobil', 'master_supir')
            ->whereHas('pemesanan');
            if ($request->has('search')) {
                FilterHelper::applySearch($perjalanan, $request->search, ['master_rute.kota_asal', 'master_rute.kota_tujuan', 'master_mobil.type', 'master_supir.nama']);
            }
            if ($request->has('startDate') && $request->has('endDate')) {
                $startDate = date('Y-m-d 00:00:00', strtotime($request->startDate));
                $endDate = date('Y-m-d 23:59:59', strtotime($request->endDate));
                $perjalanan = $perjalanan->whereBetween('tanggal_berangkat', [$startDate, $endDate]);
            }
            $perjalanan = $perjalanan->get();
            if (!$perjalanan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data not found'
                ], 404);
            }

            $perjalanan = $perjalanan->map(function ($item) {
                return [
                    'id' => $item->id,
                    'supir' => $item->master_supir->nama,
                    'rute' => $item->master_rute->kota_asal . ' - ' . $item->master_rute->kota_tujuan,
                    'jam_berangkat' => $item->waktu_keberangkatan,
                    'tanggal_berangkat' => $item->tanggal_berangkat,
                    'mobil' => $item->master_mobil->type . ' - ' . $item->master_mobil->nopol,
                ];
            });
            return response()->json([
                'success' => true,
                'data' => $perjalanan
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function downloadSuratJalan($id){
        try {
            $jadwal = Jadwal::with('pemesanan.penumpang','master_rute', 'master_mobil', 'master_supir')->find($id);
            return response()->json([
                'success' => true,
                'data' => $jadwal,
                'message' => 'Berhasil get Data'
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function suratJalan($jadwalId){
        try {
            $perjalanan = Jadwal::query()->with('pemesanan.penumpang.kursi', 'pemesanan.titikJemput','master_rute', 'master_mobil', 'master_supir')
            ->whereHas('pemesanan.penumpang.kursi')->where('id', $jadwalId)->first();
            $perjalanan->tanggal_berangkat = Carbon::parse($perjalanan->tanggal_berangkat)->format('d-m-Y');
            $perjalanan->waktu_keberangkatan= Carbon::parse($perjalanan->waktu_keberangkatan)->format('H:i');
            $pdf = Pdf::loadView('surat-jalan', ['data' => $perjalanan])->setPaper('a4', 'potrait');
            // return view('surat-jalan', ['data' => $perjalanan]);
            return $pdf->stream("Surat-Jalan{$jadwalId}.pdf");
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

}
