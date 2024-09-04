<?php

namespace App\Http\Controllers\API\Pembayaran;

use App\Http\Controllers\Controller;
use App\Models\MetodePembayaran;
use App\Models\Pembayaran;
use App\Models\PembayaranRental;
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
        $this->middleware('auth:api', ['except' => ['handleMidtransNotification']]);
        $this->middleware('check.admin')->only(['update', 'destroy', 'index', 'storeMetodePembayaran', 'deleteMetodePembayaran', 'updateStatusPembayaran']);
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

    public function uploadBuktiPembayaran(Request $request, $paymentCode)
    {
        try {
            $pembayaran = Pembayaran::where('kode_pembayaran', $paymentCode)->first();
            if  (!$pembayaran) throw new Exception('Pembayaran tidak ditemukan', 404);

            $validator = Validator::make($request->all(), [
                'bukti' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            if ($validator->fails()) return response()->json($validator->errors(), 422);

            $data = $request->all();
            if ($request->hasFile('bukti')) {
                $file = $request->file('bukti');
                $gambarPath = $file->store('superapps/pembayaran/travel', 's3');
                $fullUrl = 'https://' . env('AWS_BUCKET') . '.' . 's3' . '.' . env('AWS_DEFAULT_REGION') . '.' . 'amazonaws.com/' . $gambarPath;
                $data['bukti'] = $fullUrl;
            } else {
                $data['bukti'] = null;
            }

            $pembayaran->bukti_url = $data['bukti'];
            $pembayaran->save();

            return response()->json([
                'success' => true,
                'data' => $pembayaran,
                'message' => 'Berhasil update data'
            ]);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    public function updateStatusPembayaran($orderCode)
    {
        try {
            $pesanan = Pesanan::where('kode_pesanan', $orderCode)->first();
            $pembayaran = Pembayaran::where('pesanan_id', $pesanan->id)->first();

            if (!$pesanan) {
                return response()->json(['message' => 'Pembayaran Tidak ditemukan'], 404);
            }

            $pembayaran->update([
                'status' => 'Sukses'
            ]);
            $pesanan->update([
                'status' => 'Sukses'
            ]);
            return response()->json([
                'success' => true,
                'data' => $pembayaran,
                'message' => 'Berhasil update data'
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    public function handleMidtransNotification(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'order_id' => 'required',
                'transaction_status' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $data = $request->all();
            $paymentCode = $data['order_id'];
            $status = $data['transaction_status'];
            // Extract the payment code from the order id
            $paymentIdParts = explode("-", $paymentCode);
            $formattedPaymentCode = implode("-", array_slice($paymentIdParts, 0, 3));

            $auth = base64_encode($this->getMidtransEnv()['PRODUCTION']['server_key']);
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => "Basic $auth"
            ])->get("https://api.midtrans.com/v2/{$paymentCode}/status");

            if ($response->failed()) {
                return response()->json(['message' => json_decode($response->body())], 500);
            }

            $response = json_decode($response->body());
            $status = $response->transaction_status;

            $pembayaran = Pembayaran::where('kode_pembayaran', $formattedPaymentCode)->first();
            $pembayaranRental = PembayaranRental::where('kode_pembayaran', $formattedPaymentCode)->first(); // Find pembayaran_rental
            $statusMapping = [
                'capture' => 'Sukses',
                'settlement' => 'Sukses',
                'pending' => 'Menunggu pembayaran',
                'deny' => 'Gagal',
                'cancel' => 'Gagal',
                'expire' => 'Gagal',
                'failure' => 'Gagal',
            ];
            $convertedStatus = $statusMapping[$status] ?? 'Gagal';

            if ($pembayaran) {
                $pesanan = Pesanan::where('id', $pembayaran->pesanan_id)->first();
                $pembayaran->update([
                    'status' => $convertedStatus,
                ]);
                $pesanan->update([
                    'status' => $convertedStatus,
                ]);
                $this->paymentService->handleCancelPayment($pembayaran, $pesanan);
            }

            if ($pembayaranRental) {
                $pembayaranRental->update([
                    'status' => $convertedStatus,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Berhasil update data',
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    public function getStatusPembayaran($paymentCode)
    {
        try {
            $data = Pembayaran::with('pesanan.metode')->where('kode_pembayaran', $paymentCode)
                ->get(['id', 'kode_pembayaran', 'status', 'pesanan_id', 'updated_at', 'amount'])->first();
            if (!$data) {
                throw new Exception('Pembayaran tidak ditemukan');
            }
            $data = [
                'kode_pembayaran' => $data->kode_pembayaran,
                'metode' => $data->pesanan->metode->metode,
                'status' => $data->status,
                'tanggal' => $data->updated_at->format('d-m-Y'),
                'jam' => $data->updated_at->format('H:i:s'),
                'harga' => $data->amount
            ];
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

            if ($data->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Metode pembayaran tidak ditemukan'
                ], 404);
            }

            $response = [
                'payment_gateway' => [],
                'bank_transfer' => [],
                'cash' => null
            ];

            foreach ($data as $item) {
                $metodeData = [
                    'id' => $item->id,
                    'nama' => $item->metode,
                    'keterangan' => $item->keterangan,
                    'no_rek' => $item->no_rek,
                    'bank' => $item->bank,
                    'kode' => $item->kode,
                    'img' => $item->img
                ];

                switch ($item->kode) {
                    case 1:
                        $response['payment_gateway'][] = $metodeData;
                        break;
                    case 2:
                        $response['bank_transfer'][] = $metodeData;
                        break;
                    case 3:
                        $response['cash'] = $metodeData;
                        break;
                }
            }
            return response()->json([
                'success' => true,
                'data' => $response,
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
                'keterangan' => 'required',
                'kode' => 'required|numeric',
            ]);
            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $data = MetodePembayaran::create($request->all());
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
