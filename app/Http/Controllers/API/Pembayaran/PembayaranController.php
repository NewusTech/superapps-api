<?php

namespace App\Http\Controllers\API\Pembayaran;

use App\Http\Controllers\Controller;
use App\Models\MetodePembayaran;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class PembayaranController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('check.admin')->only(['update', 'destroy', 'index', 'storeMetodePembayaran', 'deleteMetodePembayaran']);
    }
    private function getMidtransEnv()
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

    public function showPembayaran(Request $request)
    {
        try {
            if (!$request->id) {
                throw new Exception('Id tidak ditemukan');
            }
            $data = Pembayaran::where('pesanan_id', $request->id)->get();
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
        $midtransEnv = $this->getMidtransEnv();
        try {
            $validator = Validator::make($request->all(), [
                'orderCode' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $pesanan = Pesanan::with('jadwal.master_rute')->where('kode_pesanan', $request->orderCode)->first();
            $user = User::where('id', $pesanan->user_id)->get(['nama', 'no_telp', 'email'])->first();
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
                    // 'gross_amount' => 1, // ini tes beneran tapi boongan
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
                        // "price" => 1, // Ini testing beneran tapi boongan
                        "quantity" => 1,
                    )
                ),
                'usage_limit' => 1
            );

            // return response()->json($midtransEnv, 200);
            // dd($midtransEnv['PRODUCTION']['server_key']);
            $auth = base64_encode($midtransEnv['PRODUCTION']['server_key']);

            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => "Basic $auth"
            ])->post($midtransEnv['PRODUCTION']['url'], $params);

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

    public function testProsesPembayaran(Request $request)
    {
        $midtransEnv = $this->getMidtransEnv();
        try {
            $validator = Validator::make($request->all(), [
                'orderCode' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $pesanan = Pesanan::with('jadwal.master_rute')->where('kode_pesanan', $request->orderCode)->first();
            $user = User::where('id', $pesanan->user_id)->get(['nama', 'no_telp', 'email'])->first();
            if (!$pesanan) {
                throw new Exception('Pesanan tidak ditemukan');
            }

            $params = array(
                'transaction_details' => array(
                    'order_id' => 'TEST' . '-' . \App\Models\Pembayaran::generateUniqueKodeBayar(),
                    'gross_amount' => $pesanan->jadwal->master_rute->harga,
                    'payment_link_id' => 'TEST' . '-' . str(rand(1000, 9999)) . time()
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
                        "quantity" => 1,
                    )
                ),
                'usage_limit' => 1
            );

            $auth = base64_encode($midtransEnv['SANDBOX']['server_key']);

            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => "Basic $auth"
            ])->post($midtransEnv['SANDBOX']['url'], $params);

            if ($response->failed()) {
                throw new Exception($response->body());
            }

            $response = json_decode($response->body());
            return response()->json([
                'success' => true,
                'data' => $response,
                'message' => 'Berhasil post data'
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getMetodePembayaran()
    {
        try {
            $data = MetodePembayaran::get();
            $data->nama = $data->map(function ($item) {
                return $item->metode;
            });
            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Berhasil get data'
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function storeMetodePembayaran(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'metode' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }
            $data = new MetodePembayaran();
            $data->metode = $request->metode;
            $data->save();
            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Berhasil get data'
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function deleteMetodePembayaran($id)
    {
        try {
            $data = MetodePembayaran::find($id);
            if (!$data) {
                return response()->json(['message' => 'Metode pembayaran not found'], 404);
            }
            $data->delete();
            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Metode pembayaran deleted successfully'
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
