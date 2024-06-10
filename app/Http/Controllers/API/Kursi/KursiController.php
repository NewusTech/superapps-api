<?php

namespace App\Http\Controllers\API\Kursi;

use App\Http\Controllers\Controller;
use App\Models\Kursi;
use App\Models\MasterMobil;
use Exception;
use Illuminate\Http\Request;

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
            return response()->json(['error' => $e->getMessage()], 500);
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
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy(string $id)
    {
        //
    }
}
