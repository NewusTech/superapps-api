<?php

namespace App\Http\Controllers\API\Pariwisata;

use App\Helpers\FilterHelper;
use App\Http\Controllers\Controller;
use App\Models\Pariwisata;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PariwisataController extends Controller
{
    public function index(Request $request)
    {
        try {
            $pariwisata = Pariwisata::query();
            if ($request->has('search')) {
                FilterHelper::applySearch($pariwisata, $request->search, ['judul', 'slug', 'lokasi', 'sub_judul']);
            }
            $pariwisata = $pariwisata->get();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil get data',
                'data' => $pariwisata
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function show($slug)
    {
        try {
            $pariwisata = Pariwisata::where('slug', $slug)->first();
            if (!$pariwisata) {
                return response()->json(['message' => 'Pariwisata tidak ditemukan'], 404);
            }
            return response()->json([
                'success' => true,
                'message' => 'Berhasil get data',
                'data' => $pariwisata
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'judul' => 'required',
                'image_url' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:500',
                'lokasi' => 'required',
                'sub_judul' => 'required',
                'konten' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([ 'message' => $validator->errors()->first()], 400);
            }
            $data = $request->all();
            if ($request->hasFile('image_url')) {
                $file = $request->file('image_url');
                $gambarPath = $file->store('superapps/pariwisata', 's3');
                $fullUrl = 'https://'. env('AWS_BUCKET').'.'.'s3'.'.'.env('AWS_DEFAULT_REGION').'.'.'amazonaws.com/'. $gambarPath;
                $data['image_url'] = $fullUrl;
            } else {
                $data['image_url'] = null;
            }
            $pariwisata = Pariwisata::create($data);
            return response()->json([
                'success' => true,
                'message' => 'Berhasil get data',
                'data' => $pariwisata
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function update (Request $request, $id){
        try {
            $pariwisata = Pariwisata::find($id);
            if (!$pariwisata) return response()->json(['message' => 'Pariwisata tidak ditemukan'], 404);

            $pariwisata->update($request->all());
            return response()->json([
                'success' => true,
                'message' => 'Berhasil get data',
                'data' => $pariwisata
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id){
        try {
            $pariwisata = Pariwisata::find($id);
            if (!$pariwisata) return response()->json(['message' => 'Pariwisata tidak ditemukan'], 404);
            $pariwisata->delete();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil get data',
                'data' => $pariwisata
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
