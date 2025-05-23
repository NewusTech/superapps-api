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
                return response()->json(['message' => 'Unauthorized'], 401);
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
            return response()->json(['message' => $e->getMessage()], 500);
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
            ]);

            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            }

            $user = new User([
                'nama' => $request->nama,
                'email' => strtolower($request->email),
                'master_cabang_id' => $request->master_cabang_id,
                'password' => Hash::make($request->password),
                'no_telp' => $request->no_telp,
                'role_id' => $roleCustomer->id,
                'image_url' => 'https://newus-bucket.s3.ap-southeast-2.amazonaws.com/superapps/assets/user.png',
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
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function changePassword(Request $request){
        try {
            $user = auth()->user();
            $user = User::findOrFail($user->id);
            $request->validate([
                'password' => 'required|string|min:6',
                'new_password' => 'required|string|min:6',
                'confirm_password' => 'required|string|min:6',
            ]);

            if (!$user) {
                throw new Exception('User not found');
            }

            if (!Hash::check($request->password, $user->password)) {
                throw new Exception('Password not match');
            }

            if ($request->new_password !== $request->confirm_password) {
                throw new Exception('Confirm Password not match');
            }

            $user->update([
                'password' => Hash::make($request->new_password),
            ]);
            return response()->json([
                'success' => true,
                'data' => $user,
                'message' => 'Berhasil change password'
            ]);

        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    public function forgotPassword(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email|max:255',
            ]);

            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            }

            $user = User::where('email', $request->email)->first();
            if (!$user) {
                throw new Exception('Email not found');
            }

            $user->sendPasswordResetNotification($user->email);
            return response()->json([
                'success' => true,
                'data' => $user,
                'message' => 'Berhasil forgot password'
            ]);
    } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
