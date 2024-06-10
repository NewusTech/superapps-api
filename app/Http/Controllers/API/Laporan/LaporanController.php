<?php

namespace App\Http\Controllers\API\Laporan;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function laporanPesananPerRute()
    {
        try {
            $pesanan = Pesanan::with('jadwal', 'jadwal.master_rute', 'jadwal.master_mobil', 'jadwal.master_supir')->where('status', 'Sukses')->get();

            $laporan = [];
            $totalPenumpang = 0;
            $totalHarga = 0;
            foreach ($pesanan as $item) {
                $rute = $item->jadwal->master_rute->kota_asal . ' - ' . $item->jadwal->master_rute->kota_tujuan;
                $jamBerangkat = $item->jadwal->waktu_keberangkatan;

                if (!isset($laporan[$rute])) {
                    $laporan[$rute] = [];
                }

                if (!isset($laporan[$rute][$jamBerangkat])) {
                    $laporan[$rute][$jamBerangkat] = [
                        'jumlah_mobil' => 0,
                        'jumlah_penumpang' => 0,
                        'jumlah_harga' => 0
                    ];
                }

                $laporan[$rute][$jamBerangkat]['jumlah_mobil'] += 1;
                $laporan[$rute][$jamBerangkat]['jumlah_penumpang'] += 1;
                $laporan[$rute][$jamBerangkat]['jumlah_harga'] += $item->jadwal->master_rute->harga;

                $totalPenumpang += 1;
                $totalHarga += $item->jadwal->master_rute->harga;
            }

            $formattedLaporan = [];
            foreach ($laporan as $rute => $jadwal) {
                foreach ($jadwal as $jamBerangkat => $data) {
                    $formattedLaporan[] = [
                        'rute' => $rute,
                        'jam_berangkat' => $jamBerangkat,
                        'jumlah_mobil' => $data['jumlah_mobil'],
                        'jumlah_penumpang' => $data['jumlah_penumpang'],
                        'jumlah_harga' => $data['jumlah_harga']
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'total_penumpang' => $totalPenumpang,
                    'total_harga' => $totalHarga,
                    'laporan' => $formattedLaporan
                ],
                'message' => 'Berhasil get data'
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function laporanPesananPerMobil()
    {
        try {
            $pesanan = Pesanan::with('jadwal', 'jadwal.master_rute', 'jadwal.master_mobil', 'jadwal.master_supir')->where('status', 'Sukses')->get();

            $laporan = [];
            $totalPenumpang = 0;
            $totalHarga = 0;

            foreach ($pesanan as $item) {
                $mobil = $item->jadwal->master_mobil->type;
                $rute = $item->jadwal->master_rute->kota_asal . ' - ' . $item->jadwal->master_rute->kota_tujuan;

                if (!isset($laporan[$mobil])) {
                    $laporan[$mobil] = [];
                }

                if (!isset($laporan[$mobil][$rute])) {
                    $laporan[$mobil][$rute] = [
                        'jumlah_penumpang' => 0,
                        'jumlah_harga' => 0
                    ];
                }

                $laporan[$mobil][$rute]['jumlah_penumpang'] += 1;
                $laporan[$mobil][$rute]['jumlah_harga'] += $item->jadwal->master_rute->harga;

                $totalPenumpang += 1;
                $totalHarga += $item->jadwal->master_rute->harga;
            }

            $formattedLaporan = [];
            foreach ($laporan as $mobil => $ruteData) {
                foreach ($ruteData as $rute => $data) {
                    $formattedLaporan[] = [
                        'mobil' => $mobil,
                        'rute' => $rute,
                        'jumlah_penumpang' => $data['jumlah_penumpang'],
                        'jumlah_harga' => $data['jumlah_harga']
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'total_penumpang' => $totalPenumpang,
                    'total_harga' => $totalHarga,
                    'laporan' => $formattedLaporan,
                    'message' => 'Berhasil get data'
                ]
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function listPenumpang(Request $request)
    {
        try {
            $startDate = $request->start_date;
            $endDate = $request->end_date;

            $query = Pesanan::with('jadwal', 'jadwal.master_rute', 'jadwal.master_mobil', 'jadwal.master_supir')->where('status', 'Sukses');

            if ($startDate && $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }

            $pesanan = $query->get();

            $formattedLaporan = [];
            $totalPenumpang = 0;
            $totalHarga = 0;

            foreach ($pesanan as $item) {
                $data = [
                    'nama' => $item->nama,
                    'tanggal' => $item->created_at->format('d-m-Y'),
                    'nomor_telepon' => $item->no_telp,
                    'kota_asal' => $item->jadwal->master_rute->kota_asal,
                    'kota_tujuan' => $item->jadwal->master_rute->kota_tujuan,
                    'jumlah_pesan' => 1,
                    'harga' => $item->jadwal->master_rute->harga
                ];

                $formattedLaporan[] = $data;

                $totalPenumpang += 1;
                $totalHarga += $item->jadwal->master_rute->harga;
            }

            $summary = [
                'total_penumpang' => $totalPenumpang,
                'total_harga' => $totalHarga
            ];

            return response()->json([
                'success' => true,
                'summary' => $summary,
                'data' => $formattedLaporan,
                'message' => 'Berhasil get data'
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
