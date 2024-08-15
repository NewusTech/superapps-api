<?php

namespace App\Http\Controllers\API\Pariwisata;

use App\Http\Controllers\Controller;
use App\Models\Pariwisata;
use Exception;
use Illuminate\Http\Request;

class PariwisataController extends Controller
{
    public function index()
    {
        try {
            $pariwisata = Pariwisata::all();
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
            $pariwisata = Pariwisata::create($request->all());
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
