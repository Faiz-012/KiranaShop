<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
        public function login(Request $request)
        {
            $request->validate([
                'email'    => 'required|email',
                'password' => 'required',
            ]);

            if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid credentials!'
                ], 401);
            }

            $user = Auth::user();

            $token = $user->createToken('kirana-token')->plainTextToken;

            // Step 5 — Token return karo
            return response()->json([
                'success' => true,
                'message' => 'Login successful!',
                'token'   => $token,
                'user'    => $user,
            ]);
        }

        function logout(Request $request){
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'message' => 'Logged out successfully'
            ],200);
        }
}
