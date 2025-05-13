<?php

// namespace App\Http\Controllers\API\MasterRute;

// use App\Http\Controllers\Controller;
// use App\Models\MasterRute;
// use Exception;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Validator;

// class MasterRuteController extends Controller
// {
//     public function __construct()
//     {
//         $this->middleware('auth:api', ['except' => ['index', 'show', 'dropdown']]);
//         $this->middleware('check.admin')->only(['store', 'update', 'destroy']);
//     }
//     public function index(Request $request)
//     {
//         try {
//             $data = MasterRute::get();
//             return response()->json([
//                 'success' => true,
//                 'data' => $data,
//                 'message' => 'Berhasil get data'
//             ]);
//         } catch (Exception $e) {
//             return response()->json(['message' => $e->getMessage()], 500);
//         }
//     }
//     public function dropdown(){
//         try {
//             $data = MasterRute::select('kota_asal', 'kota_tujuan')->get();
//             $from = $data->pluck('kota_asal')->unique()->values()->all();
//             $to = $data->pluck('kota_tujuan')->unique()->values()->all();

//             return response()->json([
//                 'data' => [
//                     'from' => $from,
//                     'to' => $to,
//                 ]
//             ], 200);
//         } catch (Exception $e) {
//             return response()->json(['message' => $e->getMessage()], 500);
//         }
//     }

//     public function create()
//     {
//         //
//     }

//     public function store(Request $request)
//     {
//         try {
//             $validator = Validator::make($request->all(), [
//                 'kota_asal' => 'required',
//                 'kota_tujuan' => 'required',
//                 'harga' => 'required|numeric'
//             ]);

//             if ($validator->fails()) {
//                 throw new Exception($validator->errors()->first());
//             }

//             $existingData = MasterRute::where('kota_asal', $request->kota_asal)
//                 ->where('kota_tujuan', $request->kota_tujuan)
//                 ->first();

//             if ($existingData) {
//                 throw new Exception('Data dengan kota_asal dan kota_tujuan yang sama sudah ada.');
//             }
//             $data = $request->all();
//             if ($request->hasFile('image_url')) {
//                 $file = $request->file('image_url');
//                 $gambarPath = $file->store('superapps/rute', 's3');
//                 $fullUrl = 'https://'. env('AWS_BUCKET').'.'.'s3'.'.'.env('AWS_DEFAULT_REGION').'.'.'amazonaws.com/'. $gambarPath;
//                 $data['image_url'] = $fullUrl;
//             } else {
//                 $data['image_url'] = null;
//             }
//             $master_rute = new MasterRute();
//             $master_rute->kota_asal = $data['kota_asal'];
//             $master_rute->kota_tujuan = $data['kota_tujuan'];
//             $master_rute->harga = $data['harga'];
//             $master_rute->deskripsi = $data['deskripsi'];
//             $master_rute->image_url = $data['image_url'];
//             $master_rute->save();

//             return response()->json([
//                 'success' => true,
//                 'data' => $master_rute,
//                 'message' => 'Berhasil menambah data'
//             ]);
//         } catch (Exception $e) {
//             return response()->json(['message' => $e->getMessage()], 500);
//         }
//     }

//     public function show(string $id)
//     {
//         try {
//             $data = MasterRute::find($id);
//             if (!$data) {
//                 return response()->json('Data not found', 404);
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

//     public function edit(string $id)
//     {

//     }

//     public function update(Request $request, string $id)
//     {
//         try {
//             $validator = Validator::make($request->all(), [
//                 'kota_asal' => 'required',
//                 'kota_tujuan' => 'required',
//                 'harga' => 'required|numeric'
//             ]);

//             if ($validator->fails()) {
//                 throw new Exception($validator->errors()->first());
//             }

//             $where = ['id' => $id];
//             $collection = MasterRute::where($where)->first();
//             if (!$collection) {
//                 return response()->json([
//                     'success' => false,
//                     'data' => '',
//                     'message' => 'ID tidak ditemukan'
//                 ]);
//             }
//             $reqData = $request->all();
//             if ($request->hasFile('image_url')) {
//                 $file = $request->file('image_url');
//                 $gambarPath = $file->store('superapps/rute', 's3');
//                 $fullUrl = 'https://'. env('AWS_BUCKET').'.'.'s3'.'.'.env('AWS_DEFAULT_REGION').'.'.'amazonaws.com/'. $gambarPath;
//                 $reqData['image_url'] = $fullUrl;
//             } else {
//                 $reqData['image_url'] = null;
//             }

//             $data = MasterRute::find($id);
//             $data->kota_asal = $reqData['kota_asal'];
//             $data->kota_tujuan = $reqData['kota_tujuan'];
//             $data->harga = $reqData['harga'];
//             $data->deskripsi = $reqData['deskripsi'];
//             $data->image_url = $reqData['image_url'];
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

//     public function destroy(string $id)
//     {
//         try {
//             $where = ['id' => $id];
//             $collection = MasterRute::where($where)->first();
//             if (!$collection) {
//                 return response()->json([
//                     'success' => false,
//                     'data' => '',
//                     'message' => 'ID tidak ditemukan'
//                 ]);
//             }
//             $data = MasterRute::find($id);
//             if (!$data) {
//                 return response()->json([
//                     'success' => false,
//                     'message' => 'Data tidak ditemukan.'
//                 ], 404);
//             }

//             $hasJadwal = $data->jadwal()->exists();
//             if ($hasJadwal) {
//                 return response()->json([
//                     'success' => false,
//                     'message' => 'Data ini sedang digunakan pada jadwal. Hapus jadwal terlebih dahulu.',
//                     'data' => $data
//                 ], 409);
//             }

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


namespace App\Http\Controllers\API\MasterRute;

use App\Http\Controllers\Controller;
use App\Models\MasterRute;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MasterRuteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index', 'show', 'dropdown']]);
        $this->middleware('check.admin')->only(['store', 'update', 'destroy']);
    }

    public function index(Request $request)
    {
        try {
            $query = MasterRute::with(['asal', 'tujuan']);
    
            // 🔍 Filter berdasarkan kota_asal
            if ($request->filled('kota_asal')) {
                $query->where('kota_asal', $request->kota_asal);
            }
    
            // 🔍 Filter berdasarkan kota_tujuan
            if ($request->filled('kota_tujuan')) {
                $query->where('kota_tujuan', $request->kota_tujuan);
            }
    
            // 🔍 Search by nama kota asal/tujuan (opsional, requires relasi)
            if ($request->filled('search')) {
                $search = $request->search;
                $query->whereHas('asal', function ($q) use ($search) {
                    $q->where('nama', 'like', '%' . $search . '%');
                })->orWhereHas('tujuan', function ($q) use ($search) {
                    $q->where('nama', 'like', '%' . $search . '%');
                });
            }
    
            // 📄 Pagination
            $perPage = $request->get('per_page', 10);
            $data = $query->paginate($perPage);
    
            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Berhasil mengambil data rute'
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    

    public function dropdown()
    {
        try {
            $data = MasterRute::select('kota_asal', 'kota_tujuan')->get();
            $from = $data->pluck('kota_asal')->unique()->values();
            $to = $data->pluck('kota_tujuan')->unique()->values();

            return response()->json([
                'data' => [
                    'from' => $from,
                    'to' => $to,
                ]
            ], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'kota_asal' => 'required|integer|exists:master_cabang,id',
                'kota_tujuan' => 'required|integer|exists:master_cabang,id',
                'harga' => 'required|numeric',
                'deskripsi' => 'nullable|string',
                'image_url' => 'nullable|file|image|max:2048'
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors()->first()], 422);
            }

            $existing = MasterRute::where('kota_asal', $request->kota_asal)
                ->where('kota_tujuan', $request->kota_tujuan)
                ->first();

            if ($existing) {
                return response()->json(['message' => 'Rute dengan kota asal dan tujuan yang sama sudah ada.'], 409);
            }

            $data = $request->only(['kota_asal', 'kota_tujuan', 'harga', 'deskripsi']);

            if ($request->hasFile('image_url')) {
                $path = $request->file('image_url')->store('superapps/rute', 's3');
                $data['image_url'] = 'https://' . env('AWS_BUCKET') . '.s3.' . env('AWS_DEFAULT_REGION') . '.amazonaws.com/' . $path;
            }

            $rute = MasterRute::create($data);

            return response()->json([
                'success' => true,
                'data' => $rute,
                'message' => 'Rute berhasil ditambahkan'
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $data = MasterRute::find($id);
            if (!$data) {
                return response()->json(['message' => 'Data tidak ditemukan'], 404);
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

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'kota_asal' => 'required|integer|exists:master_cabang,id',
                'kota_tujuan' => 'required|integer|exists:master_cabang,id',
                'harga' => 'required|numeric',
                'deskripsi' => 'nullable|string',
                'image_url' => 'nullable|file|image|max:2048'
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors()->first()], 422);
            }

            $rute = MasterRute::find($id);
            if (!$rute) {
                return response()->json(['message' => 'Data tidak ditemukan'], 404);
            }

            $data = $request->only(['kota_asal', 'kota_tujuan', 'harga', 'deskripsi']);

            if ($request->hasFile('image_url')) {
                $path = $request->file('image_url')->store('superapps/rute', 's3');
                $data['image_url'] = 'https://' . env('AWS_BUCKET') . '.s3.' . env('AWS_DEFAULT_REGION') . '.amazonaws.com/' . $path;
            }

            $rute->update($data);

            return response()->json([
                'success' => true,
                'data' => $rute,
                'message' => 'Rute berhasil diperbarui'
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $rute = MasterRute::find($id);
            if (!$rute) {
                return response()->json(['message' => 'Data tidak ditemukan'], 404);
            }

            if ($rute->jadwal()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data ini sedang digunakan pada jadwal. Hapus jadwal terlebih dahulu.',
                    'data' => $rute
                ], 409);
            }

            $rute->delete();

            return response()->json([
                'success' => true,
                'message' => 'Rute berhasil dihapus',
                'data' => $rute
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
