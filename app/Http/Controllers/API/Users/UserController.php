<?php

namespace App\Http\Controllers\API\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

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
            $authUser = auth()->user();
            $user = User::findOrFail($authUser->id);
            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }
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
            $user = User::findOrFail($user->id);
            $data = $request->all();

            if ($request->hasFile('image_url')) {
                $file = $request->file('image_url');
                $gambarPath = $file->store('superapps/profile', 's3');
                $fullUrl = 'https://' . env('AWS_BUCKET') . '.' . 's3' . '.' . env('AWS_DEFAULT_REGION') . '.' . 'amazonaws.com/' . $gambarPath;
                $data['image_url'] = $fullUrl;
            } else {
                $data['image_url'] = null;
            }

            $user->update($data);
            return response()->json([
                'success' => true,
                'data' => $user,
                'message' => 'Berhasil update profile photo'
            ]);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

}
