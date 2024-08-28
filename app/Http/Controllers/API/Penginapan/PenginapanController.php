<?php

namespace App\Http\Controllers\API\Penginapan;

use App\Http\Controllers\Controller;
use App\Models\Penginapan;
use Exception;
use Illuminate\Http\Request;

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
}
