<?php

// namespace App\Http\Controllers\API\MasterTitikJemput;

// use App\Http\Controllers\Controller;
// use App\Models\MasterCabang;
// use App\Models\MasterTitikJemput;
// use Exception;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Validator;

// class MasterTitikJemputController extends Controller
// {
//     public function __construct()
//     {
//         $this->middleware('auth:api', ['except' => ['index', 'show']]);
//         $this->middleware('check.admin')->only(['store', 'update', 'destroy']);
//     }

//     public function index(Request $request)
//     {
//         try {
//             $data = MasterTitikJemput::with('master_cabang');
//             if ($request->cabang) {
//                 $cabang = MasterCabang::where('nama', 'like', "%{$request->cabang}%")->first();
//                 $data->where('master_cabang_id', $cabang->id);
//             }
//             $data = $data->get();
//             return response()->json([
//                 'success' => true,
//                 'data' => $data,
//                 'message' => 'Berhasil get data'
//             ]);
//         } catch (Exception $e) {
//             return response()->json(['message' => $e->getMessage()], 500);
//         }
//     }

//     public function store(Request $request)
//     {
//         try {
//             $validator = Validator::make($request->all(), [
//                 'nama' => 'required',
//                 'master_cabang_id' => 'required',
//             ]);

//             if ($validator->fails()) {
//                 throw new Exception($validator->errors()->first());
//             }

//             $existingData = MasterTitikJemput::where('nama', $request->nama)->first();

//             if ($existingData) {
//                 throw new Exception('Data dengan nama yang sama sudah ada.');
//             }

//             $data = new MasterTitikJemput();
//             $data->nama = $request->nama;
//             $data->master_cabang_id = $request->master_cabang_id;
//             $data->save();

//             return response()->json([
//                 'success' => true,
//                 'data' => $data,
//                 'message' => 'Berhasil menambah data'
//             ]);
//         } catch (Exception $e) {
//             return response()->json(['message' => $e->getMessage()], 500);
//         }
//     }

//     public function update(Request $request, string $id)
//     {
//         try {
//             $validator = Validator::make($request->all(), [
//                 'nama' => 'required',
//                 'master_cabang_id' => 'required',
//             ]);

//             if ($validator->fails()) {
//                 throw new Exception($validator->errors()->first());
//             }

//             $where = ['id' => $id];
//             $collection = MasterTitikJemput::where($where)->first();
//             if (!$collection) {
//                 return response()->json([
//                     'success' => false,
//                     'data' => '',
//                     'message' => 'ID tidak ditemukan'
//                 ]);
//             }

//             $data = MasterTitikJemput::find($id);
//             $data->nama = $request->nama;
//             $data->master_cabang_id = $request->master_cabang_id;
//             $data->save();

//             return response()->json([
//                 'success' => true,
//                 'data' => $data,
//                 'message' => 'Berhasil update data'
//             ]);
//         } catch (Exception $e) {
//             return response()->json(['message' => $e->getMessage()], 500);
//         }
//     }

//     public function show(string $id)
//     {
//         try {
//             $data = MasterTitikJemput::find($id);
//             if (!$data) {
//                 return response()->json(['message' => 'Jadwal not found'], 404);
//             }
//             return response()->json([
//                 'success' => true,
//                 'data' => $data,
//                 'message' => 'Berhasil get data'
//             ]);
//         } catch (Exception $e) {
//             return response()->json(['message' => $e->getMessage()], 500);
//         }
//     }

//     public function destroy(string $id)
//     {
//         try {

//             $where = ['id' => $id];
//             $collection = MasterTitikJemput::where($where)->first();
//             if (!$collection) {
//                 return response()->json([
//                     'success' => false,
//                     'data' => '',
//                     'message' => 'ID tidak ditemukan'
//                 ]);
//             }
//             $data = MasterTitikJemput::find($id);
//             $data->delete();

//             return response()->json([
//                 'success' => true,
//                 'data' => $data,
//                 'message' => 'Berhasil delete data'
//             ]);
//         } catch (Exception $e) {
//             return response()->json(['message' => $e->getMessage()], 500);
//         }
//     }
// }

namespace App\Http\Controllers\API\MasterTitikJemput;

use App\Http\Controllers\Controller;
use App\Models\MasterCabang;
use App\Models\MasterTitikJemput;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MasterTitikJemputController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index', 'show']]);
        $this->middleware('check.admin')->only(['store', 'update', 'destroy']);
    }

    public function index(Request $request)
    {
        try {
            $query = MasterTitikJemput::with('master_cabang');

            if ($request->filled('cabang')) {
                $cabang = MasterCabang::where('nama', 'like', "%{$request->cabang}%")->first();
                if ($cabang) {
                    $query->where('master_cabang_id', $cabang->id);
                } else {
                    return response()->json([
                        'success' => true,
                        'data' => [],
                        'message' => 'Cabang tidak ditemukan'
                    ]);
                }
            }

            $data = $query->get();
            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Berhasil mengambil data'
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function show(string $id)
    {
        try {
            $data = MasterTitikJemput::find($id);
            if (!$data) {
                return response()->json(['message' => 'Titik jemput tidak ditemukan'], 404);
            }
            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Berhasil mengambil data'
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $this->validateRequest($request);

            $exists = MasterTitikJemput::where('nama', $request->nama)
                ->where('master_cabang_id', $request->master_cabang_id)
                ->exists();

            if ($exists) {
                throw new Exception('Titik jemput dengan nama dan cabang yang sama sudah ada.');
            }

            $data = $this->fillAndSave(new MasterTitikJemput(), $request);

            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Berhasil menambahkan data'
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $this->validateRequest($request);

            $data = MasterTitikJemput::find($id);
            if (!$data) {
                return response()->json([
                    'success' => false,
                    'message' => 'ID tidak ditemukan'
                ]);
            }

            $updated = $this->fillAndSave($data, $request);

            return response()->json([
                'success' => true,
                'data' => $updated,
                'message' => 'Berhasil memperbarui data'
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            $data = MasterTitikJemput::find($id);
            if (!$data) {
                return response()->json([
                    'success' => false,
                    'message' => 'ID tidak ditemukan'
                ]);
            }

            $data->delete();

            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Berhasil menghapus data'
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    private function validateRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'master_cabang_id' => 'required|integer|exists:master_cabang,id',
        ]);

        if ($validator->fails()) {
            throw new Exception($validator->errors()->first());
        }
    }

    private function fillAndSave(MasterTitikJemput $model, Request $request)
    {
        $model->nama = $request->nama;
        $model->master_cabang_id = $request->master_cabang_id;
        $model->save();
        return $model;
    }
}
