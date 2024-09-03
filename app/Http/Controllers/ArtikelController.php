<?php

namespace App\Http\Controllers;

use App\Helpers\FilterHelper;
use App\Models\artikel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ArtikelController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index', 'rekomendasi']]);
        $this->middleware('check.admin')->only(['store', 'update', 'destroy']);
    }

    public function index(Request $request)
    {
        try {
            $data = artikel::query();
            if ($request->has('limit')) {
                $pariwisata = FilterHelper::applyLimit($data, $request->limit);
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

    public function rekomendasi()
    {
        try {
            $data = Artikel::inRandomOrder()->limit(3)->get();
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
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    try {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'judul' => 'required',
            'image_url' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:500',
            'konten' => 'required',
        ]);

        if ($validator->fails()) {
            throw new Exception($validator->errors()->first());
        }

        $data = $request->all();

         // Proses upload gambar ke AWS S3
         if ($request->hasFile('image_url')) {
            $file = $request->file('image_url');
            $gambarPath = $file->store('superapps/artikel', 's3');
            $fullUrl = 'https://'. env('AWS_BUCKET').'.'.'s3'.'.'.env('AWS_DEFAULT_REGION').'.'.'amazonaws.com/'. $gambarPath;
            $data['image_url'] = $fullUrl;
        } else {
            $data['image_url'] = null;
        }

        $artikel = Artikel::create($data);

        return response()->json([
            'success' => true,
            'data' => $artikel,
            'message' => 'Berhasil menyimpan data'
        ]);
    } catch (Exception $e) {
        return response()->json(['message' => $e->getMessage()], 500);
    }
}

    /**
     * Display the specified resource.
     */
    public function show(Artikel $artikel)
    {
        try {
            if (!$artikel) {
                return response()->json(['message' => 'Artikel not found'], 404);
            }
            $data = artikel::find($artikel->id);
            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Berhasil get data'
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Artikel $artikel)
    {
        if (!$artikel) {
            return response()->json(['message' => 'Artikel not found'], 404);
        }
        try {
            $data = artikel::find($artikel->id);
            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Berhasil get data'
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, Artikel $artikel)
    {
        try {
            $validator = Validator::make($request->all(), [
                'judul' => 'required',
                'image_url' => 'required',
                'konten' => 'required',
            ]);
            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            }
            if (!$artikel) {
                return response()->json(['message' => 'Artikel not found'], 404);
            }

            // Proses upload gambar ke AWS S3
            if ($request->hasFile('image_url')) {
                $file = $request->file('image_url');
                $gambarPath = $file->store('superapps/artikel', 's3');
                $fullUrl = 'https://'. env('AWS_BUCKET').'.'.'s3'.'.'.env('AWS_DEFAULT_REGION').'.'.'amazonaws.com/'. $gambarPath;
                $data['image_url'] = $fullUrl;
            } else {
                $data['image_url'] = null;
            }

            $artikel->update($request->all());
            return response()->json([
                'success' => true,
                'data' => $artikel,
                'message' => 'Berhasil ubah data'
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(artikel $artikel)
    {
        try {
            if (!$artikel) {
                return response()->json(['message' => 'Artikel not found'], 404);
            }
            $artikel->delete();
            return response()->json([
                'success' => true,
                'data' => $artikel,
                'message' => 'Berhasil hapus data'
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
