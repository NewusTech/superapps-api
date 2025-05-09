<?php
namespace App\Http\Controllers\API\DatabaseKonsumen;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Rental;
use App\Models\Paket;
use Illuminate\Http\Request;

class KonsumenController extends Controller
{
    public function getAllTransaksi(Request $request)
    {
        $pesanan = Pesanan::with(['penumpang', 'pembayaran'])
            ->get()
            ->map(function ($item) {
                return [
                    'jenis' => 'Travel',
                    'kode' => $item->kode_pesanan,
                    'nama' => $item->nama,
                    'no_telp' => $item->no_telp,
                    'email' => $item->email,
                    'tanggal' => $item->created_at->format('Y-m-d'),
                    'jumlah_pesanan' => $item->penumpang->count(),
                    'harga' => $item->pembayaran->amount ?? 0,
                    'status_pembayaran' => $item->pembayaran->status ?? '-',
                ];
            });

        $rental = Rental::with('pembayaran')
            ->get()
            ->map(function ($item) {
                return [
                    'jenis' => 'Rental',
                    'kode' => $item->kode_pesanan,
                    'nama' => $item->nama,
                    'no_telp' => $item->no_telp,
                    'email' => $item->email,
                    'tanggal' => $item->created_at->format('Y-m-d'),
                    'jumlah_pesanan' => 1,
                    'harga' => $item->pembayaran->nominal ?? 0,
                    'status_pembayaran' => $item->pembayaran->status ?? '-',
                ];
            });

        $paket = Paket::with('pembayaran')
            ->get()
            ->map(function ($item) {
                return [
                    'jenis' => 'Paket',
                    'kode' => $item->resi,
                    'nama' => $item->nama_pengirim,
                    'no_telp' => $item->no_telp_pengirim,
                    'email' => '-',
                    'tanggal' => $item->created_at->format('Y-m-d'),
                    'jumlah_pesanan' => $item->jumlah_barang,
                    'harga' => $item->pembayaran->biaya ?? ($item->biaya ?? 0),
                    'status_pembayaran' => $item->pembayaran->status ?? '-',
                ];
            });

        $data = $pesanan->merge($rental)->merge($paket)->sortByDesc('tanggal')->values();

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }
    public function getDetailTransaksi($kode)
    {
        $pesanan = Pesanan::with(['penumpang.kursi', 'jadwal.master_mobil', 'jadwal.master_rute', 'jadwal.master_supir', 'titikJemput', 'titikAntar', 'pembayaran'])
            ->where('kode_pesanan', $kode)
            ->first();

        if ($pesanan) {
            return response()->json([
                'success' => true,
                'data' => [
                    'jenis' => 'Travel',
                    'kode' => $pesanan->kode_pesanan,
                    'nama_pemesan' => $pesanan->nama,
                    'no_telp' => $pesanan->no_telp,
                    'email' => $pesanan->email,
                    'tanggal' => $pesanan->created_at->format('Y-m-d'),
                    'titik_jemput' => $pesanan->titikJemput->nama ?? '-',
                    'titik_antar' => $pesanan->titikAntar->nama ?? '-',
                    'rute' => optional($pesanan->jadwal->master_rute)->kota_asal . ' - ' . optional($pesanan->jadwal->master_rute)->kota_tujuan,
                    'mobil' => optional($pesanan->jadwal->master_mobil)->type . ' - ' . optional($pesanan->jadwal->master_mobil)->nopol,
                    'supir' => optional($pesanan->jadwal->master_supir)->nama ?? '-',
                    'status' => $pesanan->status,
                    'status_pembayaran' => $pesanan->pembayaran->status ?? '-',
                    'total_harga' => $pesanan->pembayaran->amount ?? 0,
                    'penumpang' => $pesanan->penumpang->map(function ($p) {
                        return [
                            'nama' => $p->nama,
                            'nik' => $p->nik,
                            'email' => $p->email,
                            'no_telp' => $p->no_telp,
                            'kursi' => $p->kursi->nomor_kursi ?? '-',
                        ];
                    }),
                ],
            ]);
        }

        $rental = Rental::with(['mobil', 'pembayaran'])->where('kode_pesanan', $kode)->first();
        if ($rental) {
            return response()->json([
                'success' => true,
                'data' => [
                    'jenis' => 'Rental',
                    'kode' => $rental->kode_pesanan,
                    'nama' => $rental->nama,
                    'mobil' => $rental->mobil->type ?? '-',
                    'tanggal_mulai' => $rental->tanggal_mulai_sewa,
                    'tanggal_akhir' => $rental->tanggal_akhir_sewa,
                    'alamat' => $rental->alamat_keberangkatan,
                    'durasi' => $rental->durasi_sewa,
                    'status_pembayaran' => $rental->pembayaran->status ?? '-',
                ],
            ]);
        }

        $paket = Paket::with('pembayaran')->where('resi', $kode)->first();
        if ($paket) {
            return response()->json([
                'success' => true,
                'data' => [
                    'jenis' => 'Paket',
                    'kode' => $paket->resi,
                    'nama_pengirim' => $paket->nama_pengirim,
                    'nama_penerima' => $paket->nama_penerima,
                    'jumlah_barang' => $paket->jumlah_barang,
                    'jenis_paket' => $paket->jenis_paket,
                    'alamat_pengirim' => $paket->alamat_pengirim,
                    'alamat_penerima' => $paket->alamat_penerima,
                    'status_pembayaran' => $paket->pembayaran->status ?? '-',
                ],
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Transaksi tidak ditemukan',
        ], 404);
    }
}
