<?php

namespace App\Http\Controllers\API\TiketController;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\PembayaranRental;
use App\Models\Penumpang;
use App\Models\Pesanan;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use stdClass;

class TiketController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['template', 'invoice', 'eTiket', 'rentalTiket', 'rentalInvoice']]);
    }
    public function template($paymentCode)
    {
        try {
            $pesanan = Pembayaran::where('kode_pembayaran', $paymentCode)->where('status', 'Sukses')->first();
            if (!$pesanan) {
                return response()->json(["message" => "Pembayaran tidak ditemukan"], 404);
            }
            $penumpang = Penumpang::with('pesanan.jadwal.master_rute', 'pesanan.jadwal.master_mobil', 'kursi')->where('pesanan_id', $pesanan->pesanan_id)->get();
            if (!$pesanan || !$penumpang) {
                return response()->json([
                    'success' => true,
                    'data' => $pesanan,
                    'message' => 'Data tidak ditemukan'
                ], 404);
            }
            $data = [];
            $penumpang->map(function ($penumpang) use (&$data) {
                $data[] = [
                    'kode' => $penumpang->pesanan->kode_pesanan,
                    'nama' => $penumpang->nama,
                    'email' => $penumpang->email,
                    'nik' => $penumpang->nik,
                    'kursi' => $penumpang->kursi->nomor_kursi,
                    'no_telp' => $penumpang->no_telp,
                    'mobil' => $penumpang->pesanan->jadwal->master_mobil->type,
                    'jam' => Carbon::parse($penumpang->pesanan->jadwal->waktu_keberangkatan)->format('H:i'),
                    'hari' => Carbon::parse($penumpang->pesanan->jadwal->tanggal_berangkat)->translatedFormat('l'),
                    'keberangkatan' => $penumpang->pesanan->jadwal->master_rute->kota_asal . ', ' . $penumpang->pesanan->jadwal->tanggal_berangkat,
                    'tiba' => $penumpang->pesanan->jadwal->master_rute->kota_tujuan . ', ' . $penumpang->pesanan->jadwal->tanggal_berangkat,
                ];
            });

            $qrcode = base64_encode(QrCode::format('png')->size(110)->margin(0)->generate($paymentCode));
            $pdf = FacadePdf::loadView('tiket', ['data' => $data, 'qrcode' => $qrcode])->setPaper([0, 0, 226.77, 641.89],'landscape');
            return $pdf->stream("ORDER-$paymentCode.pdf");
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    public function download($paymentCode)
    {
        try {
            $pesanan = Pembayaran::where('kode_pembayaran', $paymentCode)->where('status', 'Sukses')->first();
            if (!$pesanan) {
                return response()->json(["message" => "Pembayaran tidak ditemukan"], 404);
            }
            $penumpang = Penumpang::with('pesanan.jadwal.master_rute', 'pesanan.jadwal.master_mobil', 'kursi')->where('pesanan_id', $pesanan->pesanan_id)->get();

            $data = [];
            $penumpang->map(function ($penumpang) use (&$data) {
                $data[] = [
                    'kode' => $penumpang->pesanan->kode_pesanan,
                    'nama' => $penumpang->nama,
                    'email' => $penumpang->email,
                    'nik' => $penumpang->nik,
                    'kursi' => $penumpang->kursi->nomor_kursi,
                    'no_telp' => $penumpang->no_telp,
                    'mobil' => $penumpang->pesanan->jadwal->master_mobil->type,
                    'jam' => Carbon::parse($penumpang->pesanan->jadwal->waktu_keberangkatan)->format('H:i'),
                    'hari' => Carbon::parse($penumpang->pesanan->jadwal->tanggal_berangkat)->translatedFormat('l'),
                    'keberangkatan' => $penumpang->pesanan->jadwal->master_rute->kota_asal . ', ' . $penumpang->pesanan->jadwal->tanggal_berangkat,
                    'tiba' => $penumpang->pesanan->jadwal->master_rute->kota_tujuan . ', ' . $penumpang->pesanan->jadwal->tanggal_berangkat,
                ];
            });
            $qrcode = base64_encode(QrCode::format('png')->size(208)->margin(0)->generate($paymentCode));
            $pdf = FacadePdf::loadView('tiket', ['data' => $data, 'qrcode' => $qrcode]);
            $role = auth()->user()->roles[0]->name;
            if (str_contains($role, 'Admin')) {
                return response()->json(
                    [
                        'success' => true,
                        'data' => ['link' => "https://backend-superapps.newus.id/tiket/$paymentCode"],
                        'message' => 'Berhasil get data'
                    ]
                );
            }
            return $pdf->download("ORDER-$paymentCode.pdf");
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function invoice($paymentCode)
    {
        try {
            $data = new stdClass();

            $pembayaran = Pembayaran::where('kode_pembayaran', $paymentCode)->where('status', 'Sukses')->first();
            if (!$pembayaran) {
                return response()->json(["message" => "Pembayaran tidak ditemukan"], 404);
            }
            $pesanan = Pesanan::with('penumpang', 'metode', 'jadwal.master_rute', 'penumpang.kursi')
                ->where('id', $pembayaran->pesanan_id)
                ->first();

            if (!$pesanan || !$pembayaran) {
                return response()->json([
                    'success' => true,
                    'data' => $pesanan,
                    'message' => 'Pesanan tidak ditemukan'
                ], 404);
            }

            $data->pesanan = new stdClass();
            $data->pesanan->nama = $pesanan->nama;
            $data->pesanan->invoice = $pesanan->pembayaran->kode_pembayaran;
            $data->pesanan->metode_pembayaran = $pesanan->metode->metode;
            $data->pesanan->no_telp = $pesanan->no_telp;
            $data->penumpang = [];
            $pesanan->penumpang->map(function ($penumpang) use (&$data) {
                $penumpangData = new stdClass();
                $penumpangData->nama = $penumpang->nama;
                $penumpangData->nik = $penumpang->nik;
                $penumpangData->email = $penumpang->email;
                $penumpangData->no_telp = $penumpang->no_telp;
                $penumpangData->kursi = $penumpang->kursi->nomor_kursi;
                $data->penumpang[] = $penumpangData;
            });

            $data->pembayaran = new stdClass();
            $data->pembayaran->jumlah_tiket = $pesanan->penumpang->count();
            $data->pembayaran->metode = $pesanan->metode->metode;
            $data->pembayaran->jam = Carbon::parse($pembayaran->created_at)->format('H:i');
            $data->pembayaran->tanggal = Carbon::parse($pembayaran->created_at)->format('d-m-Y');
            $data->pembayaran->harga_tiket = $pembayaran->amount / $data->pembayaran->jumlah_tiket;
            $data->pembayaran->total_harga = $pembayaran->amount;
            $pdf = FacadePdf::loadView('invoice', ['data' => $data]);
            return $pdf->stream("INVOICE-$paymentCode.pdf");
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    public function invoiceDownload($paymentCode)
    {
        try {
            $data = new stdClass();

            $pembayaran = Pembayaran::where('kode_pembayaran', $paymentCode)->where('status', 'Sukses')->first();
            if (!$pembayaran) {
                return response()->json(["message" => "Pembayaran tidak ditemukan"], 404);
            }
            $pesanan = Pesanan::with('penumpang', 'metode', 'jadwal.master_rute', 'penumpang.kursi')
                ->where('id', $pembayaran->pesanan_id)
                ->first();
            $data->pesanan = new stdClass();
            $data->pesanan->nama = $pesanan->nama;
            $data->pesanan->no_telp = $pesanan->no_telp;
            $data->pesanan->invoice = $pesanan->pembayaran->kode_pembayaran;
            $data->pesanan->metode_pembayaran = $pesanan->metode->metode;
            $data->penumpang = [];
            $pesanan->penumpang->map(function ($penumpang) use (&$data) {
                $penumpangData = new stdClass();
                $penumpangData->nama = $penumpang->nama;
                $penumpangData->nik = $penumpang->nik;
                $penumpangData->email = $penumpang->email;
                $penumpangData->no_telp = $penumpang->no_telp;
                $penumpangData->kursi = $penumpang->kursi->nomor_kursi;
                $data->penumpang[] = $penumpangData;
            });

            $data->pembayaran = new stdClass();
            $data->pembayaran->jumlah_tiket = $pesanan->penumpang->count();
            $data->pembayaran->harga_tiket = $pesanan->jadwal->master_rute->harga;
            $data->pembayaran->total_harga = $data->pembayaran->harga_tiket * $data->pembayaran->jumlah_tiket;
            $pdf = FacadePdf::loadView('invoice', ['data' => $data]);
            $role = auth()->user()->roles[0]->name;
            if (str_contains($role, 'Admin')) {
                return response()->json(
                    [
                        'success' => true,
                        'data' => ['link' => "https://backend-superapps.newus.id/invoice/$paymentCode"],
                        'message' => 'Berhasil get data'
                    ]
                );
            }
            return $pdf->download("INVOICE-$paymentCode.pdf");
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function eTiket($paymentCode)
    {
        try {
            $pembayaran = Pembayaran::where('kode_pembayaran', $paymentCode)->where('status', 'Sukses')->first();
            if (!$pembayaran) {
                return response()->json(['message' => 'E-Tiket tidak ditemukan'], 404);
            }
            $pesanan = Pesanan::with('titikJemput', 'titikAntar', 'jadwal.master_rute', 'penumpang.kursi')->where('id', $pembayaran->pesanan_id)->first();
            $pesanan->hari = Carbon::parse($pesanan->created_at)->translatedFormat('l');
            $pesanan->waktu_pemesanan = Carbon::parse($pesanan->created_at)->translatedFormat('d/m/Y H:i');
            $pesanan->hari_keberangkatan = Carbon::parse($pesanan->jadwal->tanggal_berangkat)->translatedFormat('l');
            $pesanan->jam = Carbon::parse($pesanan->jadwal->waktu_keberangkatan)->translatedFormat('H:i');
            $qrcode = base64_encode(QrCode::format('png')->size(208)->margin(0)->generate("https://backend-superapps.newus.id/tiket/$paymentCode"));
            $pdf = FacadePdf::loadView('e-tiket', ['data' => $pesanan, 'qrcode' => $qrcode]);
            return $pdf->stream("$paymentCode.pdf");
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    public function eTiketDownload($paymentCode)
    {
        try {
            $pembayaran = Pembayaran::where('kode_pembayaran', $paymentCode)->where('status', 'Sukses')->first();
            if (!$pembayaran) {
                return response()->json(['message' => 'E-Tiket tidak ditemukan'], 404);
            }
            $pesanan = Pesanan::with('titikJemput', 'titikAntar', 'jadwal.master_rute', 'penumpang.kursi')->where('id', $pembayaran->pesanan_id)->first();
            $qrcode = base64_encode(QrCode::format('png')->size(208)->margin(0)->generate($pesanan->kode_pesanan));

            $pdf = FacadePdf::loadView('e-tiket', ['data' => $pesanan, 'qrcode' => $qrcode]);

            $role = auth()->user()->roles[0]->name;
            if (str_contains($role, 'Admin')) {
                return response()->json(['link' => "https://backend-superapps.newus.id/e-tiket/$paymentCode"]);
            }
            return $pdf->download("$paymentCode.pdf");
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function rentalTiket($paymentCode){
        try {
            $pembayaran = PembayaranRental::with('rental.mobil')->where('kode_pembayaran', $paymentCode)->where('status', 'Sukses')->first();
            if (!$pembayaran) {
                return response()->json(['message' => 'E-Tiket tidak ditemukan'], 404);
            }
            $pembayaran->rental->waktu_pemesanan = Carbon::parse($pembayaran->rental->created_at)->translatedFormat('l, d/m/Y, H:i') . ' WIB';
            $pembayaran->rental->tanggal_mulai_sewa = Carbon::parse($pembayaran->rental->tanggal_mulai_sewa)->translatedFormat('d F Y');
            $pembayaran->rental->jam_keberangkatan = Carbon::parse($pembayaran->rental->jam_keberangkatan)->translatedFormat('H:i');
            $pdf = FacadePdf::loadView('rental/e-tiket', ['data' => $pembayaran]);
            return $pdf->stream("E-TIKET-$paymentCode.pdf");
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function rentalInvoice($paymentCode){
        try {
            $pembayaran = PembayaranRental::with('rental.mobil', 'rental.metode')->where('kode_pembayaran', $paymentCode)->where('status', 'Sukses')->first();
            if (!$pembayaran) {
                return response()->json(['message' => 'E-Tiket tidak ditemukan'], 404);
            }
            $pembayaran->waktu_pembayaran = Carbon::parse($pembayaran->created_at)->translatedFormat('H:i') . ' WIB';
            $pembayaran->tanggal_pembayaran = Carbon::parse($pembayaran->created_at)->translatedFormat('l, d F Y');
            $pembayaran->rental->jam_keberangkatan = Carbon::parse($pembayaran->rental->jam_keberangkatan)->translatedFormat('H:i');
            $pembayaran->rental->tanggal_mulai_sewa = Carbon::parse($pembayaran->rental->tanggal_mulai_sewa)->translatedFormat('d F');
            $pembayaran->rental->tanggal_akhir_sewa = Carbon::parse($pembayaran->rental->tanggal_akhir_sewa)->translatedFormat('d F Y');
            $pdf = FacadePdf::loadView('rental/invoice', ['data' => $pembayaran]);
            return $pdf->stream("INVOICE-$paymentCode.pdf");
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
