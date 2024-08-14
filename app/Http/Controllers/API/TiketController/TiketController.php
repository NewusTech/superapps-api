<?php

namespace App\Http\Controllers\API\TiketController;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Penumpang;
use App\Models\Pesanan;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TiketController extends Controller
{
    public function template($paymentCode)
    {
        try {
            $pesanan = Pembayaran::where('kode_pembayaran', $paymentCode)->first();
            $penumpang = Penumpang::with('pesanan.jadwal.master_rute', 'pesanan.jadwal.master_mobil', 'kursi')->where('pesanan_id', $pesanan->pesanan_id)->get();

            $data = [];
            $penumpang->map(function ($penumpang) use (&$data) {
                $data[] = [
                    'nama' => $penumpang->nama,
                    'email' => $penumpang->email,
                    'nik' => $penumpang->nik,
                    'kursi' => $penumpang->kursi->nomor_kursi,
                    'no_telp' => $penumpang->no_telp,
                    'mobil' => $penumpang->pesanan->jadwal->master_mobil->type,
                    'keberangkatan' => $penumpang->pesanan->jadwal->master_rute->kota_asal . ' - ' . $penumpang->pesanan->jadwal->tanggal_berangkat,
                    'tiba' => $penumpang->pesanan->jadwal->master_rute->kota_tujuan . ' - ' . $penumpang->pesanan->jadwal->tanggal_berangkat,
                ];
            });

            $qrcode = QrCode::size(208)->margin(0)->generate($paymentCode);
            return view('tiket', compact('data', 'qrcode'));
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function download($paymentCode)
    {
        try {
            $pesanan = Pembayaran::where('kode_pembayaran', $paymentCode)->first();
            $penumpang = Penumpang::with('pesanan.jadwal.master_rute', 'pesanan.jadwal.master_mobil', 'kursi')->where('pesanan_id', $pesanan->pesanan_id)->get();

            $data = [];
            $penumpang->map(function ($penumpang) use (&$data) {
                $data[] = [
                    'nama' => $penumpang->nama,
                    'email' => $penumpang->email,
                    'nik' => $penumpang->nik,
                    'kursi' => $penumpang->kursi->nomor_kursi,
                    'no_telp' => $penumpang->no_telp,
                    'mobil' => $penumpang->pesanan->jadwal->master_mobil->type,
                    'keberangkatan' => $penumpang->pesanan->jadwal->master_rute->kota_asal . ' - ' . $penumpang->pesanan->jadwal->tanggal_berangkat,
                    'tiba' => $penumpang->pesanan->jadwal->master_rute->kota_tujuan . ' - ' . $penumpang->pesanan->jadwal->tanggal_berangkat,
                ];
            });
            $qrcode = base64_encode(QrCode::format('png')->size(208)->margin(0)->generate($paymentCode));
            $pdf = FacadePdf::loadView('tiket', ['data' => $data, 'qrcode' => $qrcode]);
            return $pdf->download("{$paymentCode}.pdf");
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
