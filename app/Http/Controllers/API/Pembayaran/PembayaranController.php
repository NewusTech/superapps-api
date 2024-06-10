<?php

namespace App\Http\Controllers\API\Pembayaran;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class PembayaranController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('check.admin')->only(['update', 'destroy']);
    }
    public function index()
    {
        try {
            $data = Pembayaran::all();
            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Berhasil get data'
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function prosesPembayaran(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id_pesanan' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $pesanan = Pesanan::with('jadwal.master_rute')->where('id', $request->id_pesanan)->first();

            if (!$pesanan) {
                throw new Exception('Pesanan tidak ditemukan');
            }

            // create pembayaran
            $pembayaran = new Pembayaran();
            $pembayaran->pesanan_id = $pesanan->id;
            $pembayaran->kode_pembayaran = Pembayaran::generateUniqueKodeBayar();

            $params = array(
                'transaction_details' => array(
                    'order_id' => $pembayaran->kode_pembayaran,
                    'gross_amount' => $pesanan->jadwal->master_rute->harga,
                    'payment_link_id' => str(rand(1000, 9999)) . time()
                ),
                "expiry" => array(
                    "start_time" => now(),
                    "duration" => 1,
                    "unit" => "days"
                ),
                'customer_details' => array(
                    'first_name' => $pesanan->nama,
                    'phone' => $pesanan->no_telp,
                ),
                "item_details" => array(
                    "id" => $pesanan->id,
                    "name" => $pesanan->jadwal->master_rute->kota_asal . ' - ' . $pesanan->jadwal->master_rute->kota_tujuan,
                    "price" => $pesanan->jadwal->master_rute->harga,
                    "quantity" => 1,
                ),
            );

            $auth = base64_encode(env('MIDTRANS_SERVER_KEY'));

            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => "Basic $auth"
            ])->post(env('MIDTRANS_TRANSACTION_API_URL'), $params);

            if ($response->failed()) {
                throw new Exception($response->body());
            }

            $response = json_decode($response->body());
            $pembayaran->save();
            return response()->json([
                'success' => true,
                'data' => $response,
                'message' => 'Berhasil post data'
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
