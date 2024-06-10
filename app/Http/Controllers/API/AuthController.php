<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'refresh', 'logout']]);
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);
            $credentials = $request->only('email', 'password');

            $token = Auth::guard('api')->attempt($credentials);
            if (!$token) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized',
                ], 401);
            }

            $user = Auth::guard('api')->user();
            $user['token'] = $token;
            $user['type'] = 'bearer';
            return response()->json([
                'success' => true,
                'data' => $user,
                'message' => 'Berhasil login'
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function register(Request $request)
    {
        try {
            $request->validate([
                'nama' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6',
            ]);
            $user = new User([
                'nama' => $request->nama,
                'email' => $request->email,
                'master_cabang_id' => $request->master_cabang_id,
                'password' => Hash::make($request->password),
            ]);
            $user->save();
            $customerRole = Role::where('name', 'Customer')->first();
            $user->assignRole($customerRole);
            return response()->json([
                'success' => true,
                'data' => $user,
                'message' => 'Berhasil register'
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
