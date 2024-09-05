<?php

namespace App\Services;

use App\Models\MetodePembayaran;
use App\Models\PembayaranRental;
use App\Models\Pesanan;
use App\Models\Rental;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class RentalPaymentService
{
    protected $midtransEnv;
    public function __construct()
    {
        $this->midtransEnv = $this->getMidtransEnv();
    }
    private function handleImageUpload($request)
    {
        if ($request['image_ktp'] && $request['image_swafoto']) {
            $ktp = $request['image_ktp'];
            $swafoto = $request['image_swafoto'];
            $gambarPathKtp = $ktp->store('superapps/rental', 's3');
            $gambarPathSwafoto = $swafoto->store('superapps/rental', 's3');
            $fullUrlKtp = 'https://' . env('AWS_BUCKET') . '.' . 's3' . '.' . env('AWS_DEFAULT_REGION') . '.' . 'amazonaws.com/' . $gambarPathKtp;
            $fullUrlswafoto = 'https://' . env('AWS_BUCKET') . '.' . 's3' . '.' . env('AWS_DEFAULT_REGION') . '.' . 'amazonaws.com/' . $gambarPathSwafoto;
            $request['image_ktp'] = $fullUrlKtp;
            $request['image_swafoto'] = $fullUrlswafoto;
        }
        return $request;
    }
    public function processRentalPayment($request)
    {
        try {
            DB::beginTransaction();

            $metode = MetodePembayaran::where('id', $request['metode_id'])->first('kode');
            if (!$metode) {
                throw new Exception('Metode pembayaran tidak ditemukan');
            }

            $rentalExists = Rental::whereHas('pembayaran', function ($query) {
                $query->where('status', 'not like', "%Gagal%");
            })
                ->where(function ($query) use ($request) {
                    $query->where('tanggal_mulai_sewa', '<=', Carbon::parse($request['tanggal_akhir_sewa']))
                        ->where('tanggal_akhir_sewa', '>=', Carbon::parse($request['tanggal_mulai_sewa']));
                })->exists();

            if ($rentalExists) {
                throw new Exception('Tanggal tersebut sudah dibooking.', 409);
            }
            $request['user_id'] = auth()->user()->id;
            $request = $this->handleImageUpload($request);

            $rental = Rental::create($request);

            $pembayaran = new PembayaranRental();
            $pembayaran->rental_id = $rental->id;

            switch ($metode->kode) {
                case 1:
                    $response = $this->handleGatewayPayment($rental, $pembayaran);
                    break;
                case 2:
                    $response = $this->handleTransferPayment($pembayaran);
                    break;
                default:
                    throw new Exception('Metode pembayaran tidak valid');
            }
            DB::commit();
            return $response;
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['message' => "Payment error: {$e->getMessage()}"], 500);
        }
    }

    public function uploadRentalPaymentProof($request, $paymentCode)
    {
        $pembayaran = PembayaranRental::where('kode_pembayaran', $paymentCode)->first();
        if (!$pembayaran) throw new Exception('Pembayaran tidak ditemukan', 404);

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
        $pembayaran->status = 'Menunggu Verifikasi';
        if ($pembayaran->save()) {
            return $pembayaran;
        } else {
            throw new Exception('Gagal menyimpan bukti pembayaran', 500);
        }
    }

    public function updatePaymentStatus($paymentCode)
    {
        $pembayaran = PembayaranRental::where('kode_pembayaran', $paymentCode)->first();
        $pembayaran->update([
            'status' => 'Sukses',
        ]);
        return $pembayaran;
    }

    public function confirmPayment($request, $pembayaran){
        $pembayaran = PembayaranRental::where('kode_pembayaran', $pembayaran)->first();

        if (!$pembayaran) throw new Exception('Pembayaran tidak ditemukan', 404);

        $pembayaran->update($request->all());
        return $pembayaran;
    }

    protected function handleGatewayPayment($rental, $pembayaran)
    {
        $biayaTambahan = MetodePembayaran::where('id', $rental->metode_id)->first('biaya_tambahan');
        $pembayaran->nominal = $this->countNominalBiayaRental($rental, $pembayaran) + $biayaTambahan->biaya_tambahan;
        $pembayaran->kode_pembayaran = PembayaranRental::generateUniqueKodeBayar();

        $params = [
            'transaction_details' => [
                'order_id' => $pembayaran->kode_pembayaran,
                'gross_amount' => $pembayaran->nominal,
                'payment_link_id' => str(rand(1000, 9999)) . time()
            ],
            'customer_details' => [
                'first_name' => $rental->nama,
                'phone' => $rental->no_telp ?? '081234567890',
                'email' => $rental->email
            ],
            'item_details' => [
                [
                    "name" => "Rental",
                    "price" => $pembayaran->nominal,
                    "quantity" => 1,
                ]
            ],
            'usage_limit' => 1
        ];

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
        $pembayaran->status = 'Menunggu pembayaran';
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
        $pembayaran->status = 'Menunggu Pembayaran';
        $pembayaran->kode_pembayaran = PembayaranRental::generateUniqueKodeBayar();
        $pembayaran->nominal = $this->countNominalBiayaRental($pembayaran->rental, $pembayaran);
        $metode = MetodePembayaran::where('id', $pembayaran->rental->metode_id)->first();
        $data = [
            'kode_pembayaran' => $pembayaran->kode_pembayaran,
            'harga' => $pembayaran->nominal,
            'metode' => $metode->metode,
            'bank' => $metode->bank,
            'nomor_rekening' => $metode->no_rek,
            'kode' => 2
        ];
        $pembayaran->save();

        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => 'Instruksi transfer telah dikirim'
        ]);
    }

    protected function countNominalBiayaRental($rental, $pembayaran)
    {
        $nominal = $rental->durasi_sewa * ($rental->mobil->biaya_sewa + ($rental->all_in ? $rental->mobil->biaya_all_in : 0));
        return $nominal;
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
