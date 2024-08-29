<?php

namespace App\Services;

use App\Models\Pembayaran;
use App\Models\Penumpang;
use Barryvdh\DomPDF\Facade\Pdf;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\ImagickEscposImage;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\CupsPrintConnector;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PrinterService
{
    private $pdfPath;
    private $imagePath;

    public function __construct()
    {
        $this->pdfPath = public_path('asset/tiket.pdf');
        $this->imagePath = public_path('assets/tiket');
    }
    public function generatePdf($paymentCode)
    {
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
                'keberangkatan' => $penumpang->pesanan->jadwal->master_rute->kota_asal . ' - ' . $penumpang->pesanan->jadwal->tanggal_berangkat,
                'tiba' => $penumpang->pesanan->jadwal->master_rute->kota_tujuan . ' - ' . $penumpang->pesanan->jadwal->tanggal_berangkat,
            ];
        });

        $qrcode = base64_encode(QrCode::format('png')->size(208)->margin(0)->generate($paymentCode));
        $pdf = Pdf::loadView('tiket', ['data' => $data, 'qrcode' => $qrcode])->setPaper([0, 0, 226.77, 641.89], 'landscape');
        $pdfDir = public_path('assets/tiket');
        if (!file_exists($pdfDir)) {
            mkdir($pdfDir, 0755, true);
        }

        $filePath = $pdfDir . "/ORDER-$paymentCode.pdf";
        $pdf->save($filePath);
        return $filePath;
    }

    public function convertPdfToImage($pdfPath, $imagePath)
    {
        $command = "pdftoppm -r 195 -jpeg $pdfPath $imagePath";
        shell_exec($command);
        shell_exec("convert $imagePath-1.jpg -rotate -90 $imagePath-1.jpg");

        return "$imagePath-1.jpg";
    }

    public function print($paymentCode)
    {
        try {
            $pdfPath = $this->generatePdf($paymentCode);

            $imgPath = $this->convertPdfToImage($pdfPath, public_path("assets/tiket/$paymentCode"));

            $connector = new CupsPrintConnector('TP806L');
            $printer = new Printer($connector);
            $image = EscposImage::load($imgPath, false);

            $printer->bitImage($image);
            $printer->cut();
            $printer->close();

            unlink($pdfPath);
            unlink($imgPath);

            return response()->json(['message' => 'Success'], 200);
        } catch (\Throwable $th) {
            echo "An error occurred: " . $th->getMessage();
        }
    }
}
