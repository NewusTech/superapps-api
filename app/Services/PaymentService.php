<?php

namespace App\Services;

use App\Models\MetodePembayaran;
use App\Models\Pesanan;
use App\Models\Pembayaran;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Exception;

class PaymentService
{
    protected $midtransEnv;

    public function __construct()
    {
        $this->midtransEnv = $this->getMidtransEnv();
    }

    public function processPayment($request)
    {

        $validator = Validator::make($request->all(), [
            'orderCode' => 'required',
            'metode_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        $pesanan = Pesanan::with(['jadwal.master_rute', 'penumpang'])->where('kode_pesanan', $request->orderCode)->first();
        if (!$pesanan) {
            throw new Exception('Pesanan tidak ditemukan');
        }
        $kodePembayaran = MetodePembayaran::where('id', $request->metode_id)->first();
        $user = User::where('id', $pesanan->user_id)->get(['id', 'nama', 'no_telp', 'email'])->first();

        return response()->json([
            'user' => $user,
            'kodePembayaran' => $kodePembayaran
        ],200);

        $pembayaran = new Pembayaran();
        $pembayaran->pesanan_id = $pesanan->id;
        $pembayaran->amount = $pesanan->jadwal->master_rute->harga * $pesanan->penumpang->count();
        $pembayaran->kode_pembayaran = Pembayaran::generateUniqueKodeBayar();

        switch ($kodePembayaran) {
            case 1:
                return $this->handleGatewayPayment($user, $pembayaran, $pesanan);

            case 2:
                return $this->handleTransferPayment($pembayaran);

            case 3:
                return $this->handleCashPayment($pembayaran);

            default:
                throw new Exception('Metode pembayaran tidak valid');
        }
    }

    protected function handleGatewayPayment($user, $pembayaran, $pesanan)
    {
        $generatedCode = (string) Pembayaran::generateUniqueKodeBayar();
        $params = array(
            'transaction_details' => array(
                'order_id' => $generatedCode,
                'gross_amount' => $pembayaran->amount,
                'payment_link_id' => str(rand(1000, 9999)) . time()
            ),
            'customer_details' => array(
                'first_name' => $user->nama,
                'phone' => $user->no_telp,
                'email' => $user->email
            ),
            'item_details' => array(
                array(
                    "name" => $pesanan->jadwal->master_rute->kota_asal . ' - ' . $pesanan->jadwal->master_rute->kota_tujuan,
                    "price" => $pesanan->jadwal->master_rute->harga,
                    "quantity" =>  $pesanan->penumpang->count(),
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
        $pembayaran->save();

        return response()->json([
            'success' => true,
            'data' => $response,
            'message' => 'Berhasil post data'
        ]);
    }

    protected function handleTransferPayment($pembayaran)
    {
        $pembayaran->status = 'pending_transfer';
        $pembayaran->save();

        return response()->json([
            'success' => true,
            'data' => $pembayaran,
            'message' => 'Instruksi transfer telah dikirim'
        ]);
    }

    protected function handleCashPayment($pembayaran)
    {
        $pembayaran->status = 'paid_cash';
        $pembayaran->save();

        return response()->json([
            'success' => true,
            'data' => $pembayaran,
            'message' => 'Pembayaran cash berhasil dicatat'
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
