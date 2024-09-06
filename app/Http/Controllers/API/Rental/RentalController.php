<?php

namespace App\Http\Controllers\API\Rental;

use App\Http\Controllers\Controller;
use App\Http\Requests\Rental\StoreRentalRequest;
use App\Models\MobilRental;
use App\Models\Rental;
use App\Services\RentalPaymentService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RentalController extends Controller
{
    protected $paymentService;
    public function __construct(RentalPaymentService $paymentService)
    {
        $this->middleware('auth:api', ['except' => ['index', 'show', 'getBookedDates']]);
        $this->middleware('check.admin')->only(['store', 'update', 'destroy', 'confirmPayment']);
        $this->paymentService = $paymentService;
    }
    public function index()
    {
        try {
            $data = Rental::get();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil get data',
                'data' => $data
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    private function applySearchFilter($query, $request, $fields)
    {
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($query) use ($fields, $search) {
                foreach ($fields as $field) {
                    $query->orWhere($field, 'like', "%$search%");
                }
            });
        }
    }
    private function applyFilters($query, $request)
    {
        if (str_contains(auth()->user()->roles->first()->name, 'Customer')) {
            $query->where('user_id', Auth::user()->id);
        }

        if ($request->has('status')) {
            $query->whereHas('pembayaran', function ($q) use ($request) {
                $q->whereRaw('LOWER(status) LIKE ?', ["%{$request->status}%"]);
            });
        }

        $fields = ['area', 'nama', 'alamat'];
        $this->applySearchFilter($query, $request, $fields);

        if ($request->has('startDate') && $request->has('endDate')) {
            $startDate = date('Y-m-d 00:00:00', strtotime($request->startDate));
            $endDate = date('Y-m-d 23:59:59', strtotime($request->endDate));
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        return $query;
    }

    public function riwayat(Request $request)
    {
        try {
            $data = Rental::with('pembayaran', 'mobil');
            $data = $this->applyFilters($data, $request);
            $data = $data->get();
            $response = $data->map(function ($rental) {
                return [
                    'nama' => $rental->nama,
                    'durasi_sewa' => $rental->durasi_sewa,
                    'created_at' => $rental->created_at,
                    'expired_at' => Carbon::parse($rental->expired_at),
                    'kode_pembayaran' => $rental->pembayaran->kode_pembayaran,
                    'mobil_type' => $rental->mobil->type,
                    'area' => $rental->area,
                    'tanggal_awal_sewa' => $rental->tanggal_mulai_sewa,
                    'tanggal_akhir_sewa' => $rental->tanggal_akhir_sewa,
                    'harga' => (int)$rental->pembayaran->nominal,
                    'status' => $rental->pembayaran->status,
                ];
            });
            return response()->json([
                'success' => true,
                'message' => 'Berhasil get data',
                'data' => $response
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function getBookedDates(Request $request)
    {
        try {
            $rental = Rental::whereHas('pembayaran', function ($query) {
                $query->where('status', 'not like', "%Gagal%");
            })->where('mobil_rental_id', $request->mobil_id)->get(['tanggal_mulai_sewa', 'tanggal_akhir_sewa']);

            $bookedDates = []; // collection booked dates

            $rental->map(function ($rental) use (&$bookedDates) {
                $startDate = Carbon::parse($rental->tanggal_mulai_sewa);
                $endDate = Carbon::parse($rental->tanggal_akhir_sewa);

                while ($startDate <= $endDate) {
                    $bookedDates[] = $startDate->toDateString(); // collect booked dates
                    $startDate->addDay();
                }
            });

            $bookedDates = array_unique($bookedDates); // remove duplicate data
            $bookedDates = array_values($bookedDates); // reindex array
            sort($bookedDates); // sort array

            return response()->json([
                'success' => true,
                'message' => 'Berhasil get data',
                'data' => $bookedDates,
            ]);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }
    public function detailRental($paymentCode)
    {
        try {
            $data = Rental::with('pembayaran', 'metode', 'mobil')->whereHas('pembayaran', function ($q) use ($paymentCode) {
                $q->where('kode_pembayaran', $paymentCode);
            })->first();
            $norek = explode(':', $data->metode->keterangan);
            $response =  [
                'created_at' => $data->created_at,
                'kode_pembayaran' => $data->pembayaran->kode_pembayaran,
                'mobil_type' => $data->mobil->type,
                'metode' => $data->metode->metode,
                'jam_keberangkatan' => $data->jam_keberangkatan,
                'nama' => $data->nama,
                'nik' => $data->nik,
                'alamat' => $data->alamat,
                'no_telp' => $data->no_telp,
                'email' => $data->email,
                'all_in' => $data->all_in,
                'no_rek' => $data->metode->no_rek ?? null,
                'link_tiket' => "https://backend-superapps.newus.id/rental/e-tiket/{$data->pembayaran?->kode_pembayaran}",
                'link_invoice' => "https://backend-superapps.newus.id/rental/invoice/{$data->pembayaran?->kode_pembayaran}",
                'nominal' => $data->pembayaran->nominal,
                'payment_link' => $data->pembayaran->payment_link,
                'bukti_url' => $data->pembayaran?->bukti_url ?? null,
                'expired_at' => Carbon::parse($data->expired_at) ?? null,
                'area' => $data->area,
                'tanggal_awal_sewa' => $data->tanggal_mulai_sewa,
                'tanggal_akhir_sewa' => $data->tanggal_akhir_sewa,
                'status' => $data->pembayaran->status,
                'durasi_sewa' => $data->durasi_sewa,
                'alamat_keberangkatan' => $data->alamat_keberangkatan,
            ];
            return response()->json([
                'success' => true,
                'message' => 'Berhasil get data',
                'data' => $response
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }



    public function create()
    {
        try {
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }


    public function processPayment(StoreRentalRequest $request)
    {
        try {
            $validatedData = $request->validated();
            return $this->paymentService->processRentalPayment($validatedData);
        } catch (Exception $th) {
            return response()->json(['message' => "Unxecepted error: {$th->getMessage()}"], 500);
        }
    }

    public function updateStatusPembayaran($paymentCode)
    {
        try {
            $pembayaran = $this->paymentService->updatePaymentStatus($paymentCode);
            return response()->json(
                [
                    'success' => true,
                    'message' => 'Berhasil update data',
                    'data' => $pembayaran
                ]
            );
        } catch (Exception $th) {
            return response()->json(['message' => "Unxecepted error: {$th->getMessage()}"], 500);
        }
    }

    public function uploadBuktiPembayaran(Request $request, $paymentCode)
    {
        try {
            $validator = Validator::make($request->all(), ['bukti' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048']);
            if ($validator->fails()) return response()->json($validator->errors(), 422);

            $pembayaran = $this->paymentService->uploadRentalPaymentProof($request, $paymentCode);

            return response()->json([
                'success' => true,
                'message' => 'Berhasil update data',
                'data' => $pembayaran
            ]);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    public function confirmPayment(Request $request, $paymentCode)
    {
        try {
            $pembayaran = $this->paymentService->confirmPayment($request, $paymentCode);
            return response()->json([
                'success' => true,
                'message' => 'Berhasil update data',
                'data' => $pembayaran
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }
    public function show(string $id)
    {
        //
    }


    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
