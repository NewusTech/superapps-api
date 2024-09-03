<?php

namespace App\Http\Controllers\API\Penumpang;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Penumpang;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PenumpangController extends Controller
{

    public function index()
    {
        try {
            $penumpang = Penumpang::all();
            return response()->json([
                'message' => 'success',
                'data' => $penumpang
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'failed',
                'data' => $th->getMessage()
            ]);
        }
    }

    public function create()
    {

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
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
