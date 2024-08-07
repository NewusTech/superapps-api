<?php

namespace App\Http\Controllers\API\Kursi;

use App\Http\Controllers\Controller;
use App\Models\Kursi;
use App\Models\MasterMobil;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KursiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('check.admin')->only(['update']);
    }
    public function index()
    {
        try {
            $data = MasterMobil::with('kursi')->get();
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

    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        //
    }

    public function getKursiByMobil($mobilId){
        try {
            $kursi = Kursi::where('master_mobil_id', $mobilId)->get(['id', 'status', 'nomor_kursi']);
            if(!$kursi){
                return response()->json([
                    'success' => false,
                    'message' => 'Data not found'
                ]);
            }
            return response()->json([
                'success' => true,
                'data' => $kursi,
                'message' => 'Berhasil get data'
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    public function updateStatus(Request $request, string $id){
        try {
            DB::transaction(function () use ($request) {
                foreach ($request->data as $item) {
                    $kursi = Kursi::findOrFail($item['id']);  // Throw exception if not found
                    $kursi->update(['status' => $item['status']]);
                }
            });
            return response()->json(['success' => true, 'message' => 'Berhasil update data']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }

    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        try {
            $where = ['id' => $id];

            $data = Kursi::where($where)->first();
            $data->update([
                'status' => 'Terisi'
            ]);

            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Berhasil update data'
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function destroy(string $id)
    {
        //
    }
}
