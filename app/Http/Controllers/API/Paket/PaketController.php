<?php

namespace App\Http\Controllers\API\Paket;

use App\Http\Controllers\Controller;
use App\Models\MetodePembayaran;
use App\Models\Paket;
use App\Models\PembayaranPaket;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Milon\Barcode\DNS1D;

class PaketController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['downloadLabel']]);
        $this->middleware('check.admin')->only(['update', 'destroy']);
    }
    public function index()
    {
        try {
            $data = Paket::all();
            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Berhasil get data'
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nama_pengirim' => 'required',
                'alamat_pengirim' => 'required',
                'tanggal_dikirim' => 'required',
                'total_berat' => 'required|numeric',
                'no_telp_pengirim' => 'required',
                'tujuan' => 'required',
                'jenis_paket' => 'required',
                'biaya' => 'required|numeric',
                'nama_penerima' => 'required',
                'alamat_penerima' => 'required',
                'no_telp_penerima' => 'required',
                'tanggal_diterima' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $paket = new Paket();
            $paket->nama_pengirim = $request->nama_pengirim;
            $paket->nama_penerima = $request->nama_penerima;
            $paket->alamat_pengirim = $request->alamat_pengirim;
            $paket->no_telp_penerima = $request->no_telp_penerima;
            $paket->no_telp_pengirim = $request->no_telp_pengirim;
            $paket->tujuan = $request->tujuan;
            $paket->alamat_penerima = $request->alamat_penerima;
            $paket->tanggal_dikirim = $request->tanggal_dikirim;
            $paket->tanggal_diterima = $request->tanggal_diterima;
            $paket->jenis_paket = $request->jenis_paket;
            $paket->biaya = $request->biaya;
            $paket->total_berat = $request->total_berat;
            $paket->save();

            return response()->json([
                'success' => true,
                'data' => $paket,
                'message' => 'Berhasil created'
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function prosesPembayaranPaket(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'paket_id' => 'required',
                'metode_id' => 'required'
            ]);
            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }
            $metode = MetodePembayaran::where('kode', $request->metode_id)->first();
            if ($metode?->kode == 1) {
                return response()->json(['message' => 'Metode pembayaran tidak ditemukan'], 404);
            }
            $pembayaran = PembayaranPaket::where('paket_id', $request->paket_id)->first();
            if ($pembayaran) {
                return response()->json(['message' => 'Pembayaran sudah dilakukan'], 404);
            }

            $paket = Paket::find($request->paket_id);
            if (!$paket) {
                throw new Exception('Paket not found', 404);
            }
            $pembayaran = PembayaranPaket::create([
                'kode_paket' => PembayaranPaket::generateUniqueKodePaket(),
                'paket_id' => $request->paket_id,
                'metode_id' => $request->metode_id,
                'status' => 'Menunggu Pembayaran',
            ]);

            return response()->json([
                'success' => true,
                'data' => $pembayaran->load('paket', 'metode'),
                'message' => 'Berhasil created'
            ]);
        } catch (\Throwable $th) {
            return response()->json(['message' => "Unexpected error: {$th->getMessage()}"], 500);
        }
    }

    public function show(string $resi)
    {
        try {
            $data = Paket::where('resi', $resi)->first();
            if (!$data) {
                throw new Exception('Data not found');
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

    public function edit(string $id)
    {
        //
    }

    public function updateStatusPembayaran($resi)
    {
        try {
            $paket = Paket::where('resi', $resi)->with('pembayaran')->first();
            if (!$paket) {
                throw new Exception('Data not found');
            }
            $paket->pembayaran->status = 'Sukses';
            $paket->pembayaran->save();
        } catch (Exception $th) {
            return response()->json(['message' => "Unexpected error: {$th->getMessage()}"], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nama_pengirim' => 'required',
                'nama_penerima' => 'required',
                'alamat_pengirim' => 'required',
                'alamat_penerima' => 'required',
                'tanggal_dikirim' => 'required',
                'jenis_paket' => 'required',
                'status' => 'required',
                'biaya' => 'required|numeric',
                'total_berat' => 'required|numeric',
            ]);
            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            }

            $where = ['id' => $id];
            $collection = Paket::where($where)->first();
            if (!$collection) {
                return response()->json([
                    'success' => false,
                    'data' => '',
                    'message' => 'ID tidak ditemukan'
                ]);
            }

            $data = Paket::find($id);
            $data->nama_pengirim = $request->nama_pengirim;
            $data->nama_penerima = $request->nama_penerima;
            $data->alamat_pengirim = $request->alamat_pengirim;
            $data->alamat_penerima = $request->alamat_penerima;
            $data->tanggal_dikirim = $request->tanggal_dikirim;
            $data->tanggal_diterima = $request->tanggal_diterima;
            $data->jenis_paket = $request->jenis_paket;
            $data->status = $request->status;
            $data->biaya = $request->biaya;
            $data->total_berat = $request->total_berat;
            $data->save();

            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Berhasil update data'
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    public function downloadLabel($resi)
    {
        try {
            $paket = Paket::with('pembayaran')->where('resi', $resi)->with('pembayaran')->first();
            if (!$paket) {
                throw new Exception('Data not found');
            }
            $barcode = new DNS1D();
            $barcodeImage = $barcode->getBarcodePNG($resi, 'C128', 1.925, 53);
            $pdf = Pdf::loadView('label-paket', ['paket' => $paket , 'barcode' => $barcodeImage]);
            $pdf->setPaper([0, 0, 288, 432], 'potrait');
            return $pdf->stream("$resi.pdf");
        } catch (Exception $e) {
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $where = ['id' => $id];
            $collection = Paket::where($where)->first();
            if (!$collection) {
                return response()->json([
                    'success' => false,
                    'data' => '',
                    'message' => 'ID tidak ditemukan'
                ]);
            }
            $data = Paket::find($id);
            $data->delete();

            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Berhasil delete data'
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
