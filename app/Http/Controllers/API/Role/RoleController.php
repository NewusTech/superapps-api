<?php

namespace App\Http\Controllers\API\Role;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Exception;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    // 🔹 GET /roles/{id}
    public function show($id)
    {
        try {
            $role = Role::with('permissions')->find($id);

            if (!$role) {
                return response()->json([
                    'success' => false,
                    'message' => 'Peran tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $role,
                'message' => 'Berhasil mendapatkan detail peran'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    // 🔹 GET /roles
    public function index()
    {
        try {
            $data = Role::with('permissions')->get();
            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Berhasil mendapatkan data peran'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    // 🔹 GET /permissions
    public function getAllPermission()
    {
        try {
            $permission = Permission::all();
            return response()->json([
                'success' => true,
                'data' => $permission,
                'message' => 'Berhasil mendapatkan data permission'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    // 🔹 POST /roles
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'permissions' => 'required|array',
                'permissions.*' => 'integer|exists:permissions,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $role = Role::create([
                'name' => $request->name,
                'guard_name' => 'api',
            ]);

            $role->syncPermissions($request->permissions);

            return response()->json([
                'success' => true,
                'data' => $role->load('permissions'),
                'message' => 'Peran berhasil ditambahkan'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    // 🔹 PUT /roles/{id}
    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'permissions' => 'nullable|array',
                'permissions.*' => 'integer|exists:permissions,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $role = Role::find($id);
            if (!$role) {
                return response()->json([
                    'success' => false,
                    'message' => 'Peran tidak ditemukan'
                ], 404);
            }

            $role->update(['name' => $request->name]);

            if ($request->has('permissions')) {
                $role->syncPermissions($request->permissions);
            }

            return response()->json([
                'success' => true,
                'data' => $role->load('permissions'),
                'message' => 'Peran berhasil diperbarui'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    // 🔹 DELETE /roles/{id}
    public function destroy($id)
    {
        try {
            $role = Role::find($id);
            if (!$role) {
                return response()->json([
                    'success' => false,
                    'message' => 'Peran tidak ditemukan'
                ], 404);
            }

            $role->delete();

            return response()->json([
                'success' => true,
                'message' => 'Peran berhasil dihapus'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
