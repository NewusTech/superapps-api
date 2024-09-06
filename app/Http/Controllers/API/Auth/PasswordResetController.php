<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class PasswordResetController extends Controller
{
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json([
                'success' => true,
                'message' => 'Reset password link sent on your email.'
            ], 200);
        }
        if ($status === Password::INVALID_USER) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid user'
            ], 400);
        }

        return response()->json([
            'success' => false,
            'message' => 'Unable to send reset link'
        ], 400);
    }
    public function reset(Request $request)
    {
        try {
            $request->validate([
                'token' => 'required',
                'email' => 'required|email',
                'password' => 'required|confirmed',
            ]);

            $response = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user, $password) {
                    $user->password = Hash::make($password);
                    $user->save();
                }
            );

            if ($response == Password::INVALID_TOKEN) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid token provided'
                ], 400);
            }

            if ($response == Password::INVALID_USER) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid user'
                ], 400);
            }

            if ($response == Password::PASSWORD_RESET) {
                return response()->json([
                    'success' => true,
                    'message' => 'Password has been reset successfully'
                ], 200);
            }

            return response()->json([
                'success' => false,
                'message' => 'Password reset failed'
            ], 500);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
