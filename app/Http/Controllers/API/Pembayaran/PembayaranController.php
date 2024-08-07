<?php

namespace App\Http\Controllers\API\Pembayaran;

use App\Http\Controllers\Controller;
use App\Models\MetodePembayaran;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use App\Models\User;
use App\Services\PaymentService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class PembayaranController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->middleware('auth:api');
        $this->middleware('check.admin')->only(['update', 'destroy', 'index', 'storeMetodePembayaran', 'deleteMetodePembayaran']);
        $this->paymentService = $paymentService;
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
            return response()->json(['message' => $e->getMessage()], 500);
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
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    public function prosesPembayaran(Request $request)
    {
        try {
            return $this->paymentService->processPayment($request);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
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

            $generatedCode = (string) Pembayaran::generateUniqueKodeBayar();
            $orderCode = "TEST-{$generatedCode}";
            $params = array(
                'transaction_details' => array(
                    'order_id' => $orderCode,
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
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function getStatusPembayaran($paymentCode){
        try {
            $data = Pembayaran::with('pesanan.metode')->where('kode_pembayaran', $paymentCode)
            ->get(['id', 'kode_pembayaran', 'status', 'pesanan_id','updated_at'])->first();
            if (!$data) {
                throw new Exception('Pembayaran tidak ditemukan');
            }
            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Berhasil get data'
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
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
            return response()->json(['message' => $e->getMessage()], 500);
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
            return response()->json(['message' => $e->getMessage()], 500);
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
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
