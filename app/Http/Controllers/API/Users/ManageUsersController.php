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
            $role = Role::where('name', 'Customer')->first();
            $users = User::where('role_id', '!=', $role->id)->get();
            return response()->json([
                'success' => true,
                'data' => $users,
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
                'nama' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required',
                'role_id' => 'required|numeric',
                'cabang_id' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            }

            $user = new User();
            $user->nama = $request->nama;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->role_id = $request->role_id;
            $user->master_cabang_id = $request->cabang_id;
            $user->save();

            return response()->json([
                'success' => true,
                'data' => $user,
                'message' => 'Berhasil menambah data'
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
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
                'cabang_id' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            }

            $user = User::find($id);

            $where = ['id' => $id];
            $existingUser = User::where($where)->first();
            if (!$existingUser) {
                return response()->json([
                    'success' => false,
                    'data' => '',
                    'message' => 'ID tidak ditemukan'
                ]);
            }

            $user->update($request->all());
            return response()->json([
                'success' => true,
                'data' => $user,
                'message' => 'Berhasil get data'
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
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
                    'message' => 'ID tidak ditemukan'
                ]);
            }

            $user->delete();
            return response()->json([
                'success' => true,
                'data' => $user,
                'message' => 'Berhasil get data'
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
