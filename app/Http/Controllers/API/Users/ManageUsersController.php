<?php

namespace App\Http\Controllers\API\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class ManageUsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        try {
            $superAdmin = Role::where('name', 'Super Admin')->first();
    
            $users = User::with('roles')
                ->where('role_id', '!=', $superAdmin->id)
                ->orderBy('created_at', 'desc')
                ->get();
    
            return response()->json([
                'success' => true,
                'data' => $users,
                'message' => 'Berhasil get data pengguna',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    

    public function show($id)
    {
        try {
            $user = User::with('roles')->find($id);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pengguna tidak ditemukan.',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $user,
                'message' => 'Berhasil mengambil data pengguna.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $rules = [
                'nama' => 'required|string|max:255',
                'email' => 'required|email|unique:users',
                'password' => 'nullable|string|min:6',
                'role_id' => 'required|numeric',
                'no_telp' => 'nullable|string|max:20',
            ];

            $role = Role::find((int) $request->role_id);
            if (!$role) {
                throw new Exception("Role dengan ID {$request->role_id} tidak ditemukan.");
            }

            if (in_array($role->name, ['Admin', 'Super Admin'])) {
                $rules['cabang_id'] = 'required|numeric';
            }

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            }
            $password = $request->password ?? '12345678'; // ✅ default

            $user = new User([
                'nama' => $request->nama,
                'email' => strtolower($request->email),
                'password' => Hash::make($password),
                'is_default_password' => true,
                'no_telp' => $request->no_telp,
                'role_id' => $role->id,
                'image_url' => 'https://newus-bucket.s3.ap-southeast-2.amazonaws.com/superapps/assets/user.png',
                'master_cabang_id' => in_array($role->name, ['Admin', 'Super Admin']) ? $request->cabang_id : null,
            ]);

            $user->save();

            if ($role->guard_name !== 'web') {
                $existingWebRole = Role::where('name', $role->name)->where('guard_name', 'web')->first();
                if (!$existingWebRole) {
                    Role::create([
                        'name' => $role->name,
                        'guard_name' => 'web',
                    ]);
                }
            }

            $user->assignRole($role->name);

            return response()->json([
                'success' => true,
                'data' => $user,
                'message' => 'Berhasil menambah pengguna.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nama' => 'required',
                'email' => 'required|email',
                'password' => 'required',
                'role_id' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            }

            $user = User::find($id);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'data' => '',
                    'message' => 'ID tidak ditemukan',
                ]);
            }

            $user->nama = $request->nama;
            $user->email = $request->email;
            $user->role_id = $request->role_id;
            $user->no_telp = $request->no_telp;

            if ($request->password !== null) {
                $user->password = Hash::make($request->password);
                $user->is_default_password = false;
            }

            $user->save();

            return response()->json([
                'success' => true,
                'data' => $user,
                'message' => 'Berhasil mengupdate data',
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'data' => '',
                    'message' => 'ID tidak ditemukan',
                ]);
            }

            $user->delete();
            return response()->json([
                'success' => true,
                'data' => $user,
                'message' => 'Berhasil menghapus data',
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
