<?php

namespace App\Http\Controllers\API\Role;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        try {
            $data = Role::with('permissions')->get();
            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Berhasil get data'
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getAllPermission()
    {
        try {
            $permission = Permission::all();

            return response()->json([
                'success' => true,
                'data' => $permission,
                'message' => 'Berhasil get data'
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'permissions' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 401);
            }

            $role = new Role();
            $role->name = $request->name;
            $role->guard_name = 'web';

            $role->syncPermissions($request->permissions);
            $role->save();

            return response()->json([
                'success' => true,
                'data' => $role,
                'message' => 'Berhasil tambah data'
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 401);
            }

            $data = Role::find($id);
            if (!$data) {
                return response()->json([
                    'success' => false,
                    'message' => 'ID tidak ditemukan'
                ], 404);
            }

            $data->update($request->all());
            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Berhasil update data'
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $data = Role::find($id);
            if (!$data) {
                return response()->json([
                    'success' => false,
                    'message' => 'ID tidak ditemukan'
                ], 404);
            }
            $data->delete();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil delete data'
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
