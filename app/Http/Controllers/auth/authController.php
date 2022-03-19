<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\auth\loginRequest;
use App\Http\Requests\auth\registerRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class authController extends Controller
{
    public function register(registerRequest $request)
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'role_id' => $request->role_id,
                'password' => Hash::make($request->password)
            ]);

            return response()->json([
                'success' => true,
                'token' => $user->createToken('auth_token')->accessToken,
                'user' => $user
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function login(loginRequest $request)
    {
        try {
            $credential = $request->only(['email','password']);
            if (!Auth::attempt($credential))
            {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }else
            {
                $user = $request->user();
                $tokenResult = $user->createToken('Personal Access Token');

                return response()->json([
                    'success' => true,
                    'access_token' => $tokenResult->accessToken,
                    'token_type' => 'Bearer',
                    'expires_at' => Carbon::parse(
                        $tokenResult->token->expires_at
                    )->toDateTimeString()
                ], 200);
            }

        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage()
            ], 500);
        }

    }

    public function logout()
    {
        try {
            // logout from current device
            Auth::user()->token()->delete();

            return response()->json( [
                'success' => false,
                'error' =>'Successfully logged out']
                , 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage()
            ], 500);
        }

    }
}
