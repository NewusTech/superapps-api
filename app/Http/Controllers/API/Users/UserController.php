<?php

namespace App\Http\Controllers\API\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

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
    }
}
