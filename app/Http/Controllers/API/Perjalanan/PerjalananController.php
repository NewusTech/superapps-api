<?php

namespace App\Http\Controllers\API\Perjalanan;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\MasterMobil;
use App\Models\Pesanan;
use Exception;
use Illuminate\Http\Request;

class PerjalananController extends Controller
{
    public function index(){
        try {
            $perjalanan = Jadwal::with('master_rute', 'master_mobil', 'master_supir')->get();
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
                    'tanggal_berangkat' => $item->tanggal_keberangkatan,
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

}
