<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTFactory;
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

            $credentials = request(['email', 'password']);
            $user = User::where('email', $credentials['email'])->first();

            if (!$user || !Auth::guard('api')->attempt($credentials)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            $role = Role::where('id', $user->role_id)->pluck('name')->first();
            $customClaims = [
                'id' => $user->id,
                'role' => $role
            ];

            $factory = JWTFactory::customClaims($customClaims);
            $payload = $factory->make();
            $token = JWTAuth::encode($payload)->get();

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
        $roleCustomer = Role::where('name', 'Customer')->first();
        try {
            $validator = Validator::make($request->all(), [
                'nama' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6',
                'no_telp' => 'required|numeric|min:8',
                'role_id' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            }

            $user = new User([
                'nama' => $request->nama,
                'email' => $request->email,
                'master_cabang_id' => $request->master_cabang_id,
                'password' => Hash::make($request->password),
                'no_telp' => $request->no_telp,
                'role_id' => $request->role_id ?? $roleCustomer->id
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
