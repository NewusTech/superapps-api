<?php

namespace App\Http\Controllers\API\Penginapan;

use App\Http\Controllers\Controller;
use App\Models\Fasilitas;
use App\Models\Image;
use App\Models\Kebijakan;
use App\Models\Penginapan;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenginapanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('check.admin')->only(['update', 'destroy']);
    }

    public function index(Request $request)
    {
        try {
            $data = Penginapan::query()->with([
                'image:id,image',
            ]);

            if ($request->has('tipe')) {
                $data = $data->where('tipe', 'like', "%{$request->tipe}%");
            }

            $data = $data->get();
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
        try {
            $fasilitas = Fasilitas::get(['id', 'nama']);
            $kebijakan = Kebijakan::get(['id', 'title', 'deskripsi']);
            $data = compact('fasilitas', 'kebijakan');
            return response()->json([
                'success' => true,
                'message' => 'Berhasil get data',
                'data' => $data,
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    public function store(Request $request)
    {
        try {
            $data = $request->all();

            $fieldsToEncode = ['kebijakan', 'fasilitas', 'jumlah_kamar', 'luas_ruangan', 'rating', 'harga'];
            foreach ($fieldsToEncode as $field) {
                if (isset($data[$field])) {
                    $data[$field] = json_decode($data[$field], true); // Mengubah menjadi array
                }
            }

            if ($request->hasFile('images')) {
                $imageUrls = [];
                $files = $request->file('images');
                foreach ($files as $file) {
                    $gambarPath = $file->store('superapps/penginapan', 's3');
                    $fullUrl = 'https://' . env('AWS_BUCKET') . '.s3.' . env('AWS_DEFAULT_REGION') . '.amazonaws.com/' . $gambarPath;
                    $imageUrls[] = $fullUrl;
                }
                $data['images'] = $imageUrls;
            } else {
                $data['images'] = [];
            }
            DB::beginTransaction();
            $penginapan = new Penginapan();
            $penginapan->title = $data['title'];
            $penginapan->lokasi = $data['lokasi'];
            $penginapan->jumlah_kamar = $data['jumlah_kamar'];
            $penginapan->luas_ruangan = $data['luas_ruangan'];
            $penginapan->tipe = $data['tipe'];
            $penginapan->deskripsi = $data['deskripsi'];
            $penginapan->rating = $data['rating'];
            $penginapan->harga = $data['harga'];
            $penginapan->save();

            if ($penginapan) {
                $this->storeFasilitas($penginapan, $data);
                $this->storeKebijakan($penginapan, $data);
                $this->storeImage($penginapan, $data);
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Berhasil create data'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    private function storeFasilitas($penginapan, $data)
    {
        $penginapan->fasilitas()->attach($data['fasilitas']);
    }
    private function storeKebijakan($penginapan, $data)
    {
        $penginapan->kebijakan()->attach($data['kebijakan']);
    }

    private function storeImage($penginapan, $data)
    {
        foreach ($data['images'] as $imageUrl) {
            $image = Image::create(['image' => $imageUrl]);
            $penginapan->image()->attach($image->id);
        }
    }
}
