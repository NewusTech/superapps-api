<?php

namespace App\Http\Controllers;

use App\Models\artikel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ArtikelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $data = artikel::all();
            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Berhasil get data'
            ]);
        } catch (Exception $e) {

            return response()->json(['error' => $e->getMessage()], 500);
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
            return response()->json(['error' => $e->getMessage()], 500);
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
            $validator = Validator::make($request->all(), [
                'judul' => 'required',
                'image_url' => 'required',
                'konten' => 'required',
            ]);
            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            }
            $data = artikel::create($request->all());
            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Berhasil get data'
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
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
            return response()->json(['error' => $e->getMessage()], 500);
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
            return response()->json(['error' => $e->getMessage()], 500);
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
            $artikel->update($request->all());
            return response()->json([
                'success' => true,
                'data' => $artikel,
                'message' => 'Berhasil ubah data'
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
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
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
