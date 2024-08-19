<?php

namespace App\Services;

use App\Models\MetodePembayaran;
use App\Models\PembayaranRental;
use App\Models\Pesanan;
use App\Models\Rental;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class RentalPaymentService
{
    protected $midtransEnv;
    public function __construct()
    {
        $this->midtransEnv = $this->getMidtransEnv();
    }

    public function processRentalPayment($request)
    {
        try {
            DB::beginTransaction();
            $metode = MetodePembayaran::where('id', $request['metode_id'])->first('kode');
            if (!$metode) {
                throw new Exception('Metode pembayaran tidak ditemukan');
            }
            // dd($request);
            $rental = Rental::create($request);
            $rental->all_in = $request['all_in'] ?? 0;
            $pembayaran = new PembayaranRental();
            $pembayaran->rental_id = $rental->id;

            match ($metode->kode) {
                1 => $this->handleGatewayPayment($rental, $pembayaran),
                2 => $this->handleTransferPayment($pembayaran),
            };

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['message' => "Payment error: {$e->getMessage()}"], 500);
        }
    }

    protected function handleGatewayPayment($rental, $pembayaran)
    {
        $biayaTambahan = MetodePembayaran::where('id', $rental->metode_id)->first('biaya_tambahan');
        $rental->all_in == 1 ?
        $pembayaran->nominal = $rental->durasi_sewa * $rental->mobil->biaya_sewa + $biayaTambahan->biaya_tambahan :
        $pembayaran->nominal = $rental->durasi_sewa * $rental->mobil->biaya_sewa;
        dd($pembayaran->nominal);
        $params = array(
            'transaction_details' => array(
                'order_id' => $pembayaran->kode_pembayaran,
                'gross_amount' => $pembayaran->nominal,
                'payment_link_id' => str(rand(1000, 9999)) . time()
            ),
            'customer_details' => array(
                'first_name' => $rental->nama,
                'phone' => $rental->no_telp ?? '081234567890',
                'email' => $rental->email
            ),
            'item_details' => array(
                array(
                    "name" => "Rental",
                    "price" => $pembayaran->nominal,
                    "quantity" => 1,
                )
            ),
            'usage_limit' => 1
        );


        $auth = base64_encode($this->midtransEnv['PRODUCTION']['server_key']);

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => "Basic $auth"
        ])->post($this->midtransEnv['PRODUCTION']['url'], $params);

        if ($response->failed()) {
            return response()->json(['message' => json_decode($response->body())], 500);
        }

        $response = json_decode($response->body());
        $pembayaran->payment_link = $response->payment_url;
        $response->kode = 1;
        $pembayaran->save();

        return response()->json([
            'success' => true,
            'data' => $response,
            'message' => 'Berhasil post data'
        ]);
    }

    protected function handleTransferPayment($pembayaran)
    {
        $metodeId = Pesanan::where('id', $pembayaran->pesanan_id)->first()->metode_id;
        $metode = MetodePembayaran::where('id', $metodeId)->first();
        $rekening = explode('-', $metode->metode);
        $norek = explode(':', $metode->keterangan);
        $data = [
            'kode_pembayaran' => $pembayaran->kode_pembayaran,
            'harga' => $pembayaran->amount,
            'metode' => $rekening[0],
            'bank' => $rekening[1],
            'nomor_rekening' => trim($norek[1]),
            'kode' => 2
        ];
        $pembayaran->status = 'Menunggu Pembayaran';
        $pembayaran->save();

        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => 'Instruksi transfer telah dikirim'
        ]);
    }

    protected function getMidtransEnv()
    {
        return [
            'SANDBOX' => [
                'client_key' => env('MIDTRANS_SANDBOX_CLIENT_KEY'),
                'server_key' => env('MIDTRANS_SANDBOX_SERVER_KEY'),
                'url' => env('MIDTRANS_SANDBOX_TRANSACTION_API_URL'),
            ],
            'PRODUCTION' => [
                'client_key' => env('MIDTRANS_PRODUCTION_CLIENT_KEY'),
                'server_key' => env('MIDTRANS_PRODUCTION_SERVER_KEY'),
                'url' => env('MIDTRANS_PRODUCTION_TRANSACTION_API_URL'),
            ],

        ];
    }
}
