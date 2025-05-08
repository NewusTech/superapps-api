<?php

namespace App\Http\Controllers\API\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    // 🔹 GET: Semua pengguna
    public function getAllUsers()
    {
        try {
            // Ambil semua user KECUALI Super Admin (misal role_id = 1)
            $users = User::where('role_id', '!=', 1)
                ->with('roles') // Jika pakai spatie/permission dan pakai relasi roles
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $users,
                'message' => 'Berhasil get semua pengguna',
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    // 🔹 GET: Detail satu user login
    public function index()
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        return response()->json([
            'success' => true,
            'data' => $user,
            'message' => 'Berhasil get user'
        ]);
    }

    // 🔹 PUT: Update profile sendiri
    public function updateProfile(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nama' => 'required',
                'nik' => 'required',
                'alamat' => 'required',
                'no_telp' => 'required|min:8|max:13',
            ]);
            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors()->first()], 422);
            }

            $user = auth()->user();
            $user->update($request->all());

            return response()->json([
                'success' => true,
                'data' => $user,
                'message' => 'Berhasil update user'
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    // 🔹 PATCH: Update foto profile
    public function profilePhotoUpdate(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'image_url' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            }

            $user = auth()->user();

            if ($request->hasFile('image_url')) {
                $file = $request->file('image_url');
                $gambarPath = $file->store('superapps/profile', 's3');
                $fullUrl = 'https://' . env('AWS_BUCKET') . '.s3.' . env('AWS_DEFAULT_REGION') . '.amazonaws.com/' . $gambarPath;
                $user->image_url = $fullUrl;
                $user->save();
            }

            return response()->json([
                'success' => true,
                'data' => $user,
                'message' => 'Berhasil update profile photo'
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    // 🔹 POST: Tambah pengguna baru
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nama' => 'required|string',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6',
                'role_id' => 'nullable|integer',
            ]);

            if ($request->filled('role_id')) {
                $user->syncRoles($request->role_id); // role_id bisa berupa nama atau ID tergantung setup
            }

            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors()->first()], 422);
            }

            $user = User::create([
                'nama' => $request->nama,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => $request->role_id,
                'master_cabang_id' => $request->master_cabang_id,
                'no_telp' => $request->no_telp,
                'nik' => $request->nik,
                'alamat' => $request->alamat,
                'kota' => $request->kota,
            ]);

            return response()->json([
                'success' => true,
                'data' => $user,
                'message' => 'Pengguna berhasil ditambahkan'
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    // 🔹 PUT: Update pengguna by ID
    public function update(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            // 🔒 Blokir update jika Super Admin
            if ($user->role_id == 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pengguna Super Admin tidak dapat diubah.'
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'nama' => 'required|string',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'role_id' => 'nullable|integer',
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors()->first()], 422);
            }

            $user->update([
                'nama' => $request->nama,
                'email' => $request->email,
                'role_id' => $request->role_id,
                'master_cabang_id' => $request->master_cabang_id,
                'no_telp' => $request->no_telp,
                'nik' => $request->nik,
                'alamat' => $request->alamat,
                'kota' => $request->kota,
            ]);

            return response()->json([
                'success' => true,
                'data' => $user,
                'message' => 'Pengguna berhasil diperbarui'
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }


    // 🔹 DELETE: Hapus pengguna
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);

            // 🔒 Blokir hapus jika Super Admin
            if ($user->role_id == 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pengguna Super Admin tidak dapat dihapus.'
                ], 403);
            }

            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'Pengguna berhasil dihapus'
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

}
