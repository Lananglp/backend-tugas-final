<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('authToken')->plainTextToken;

            return response()->json([
                'status' => 'success',
                'message' => 'Login successful', 
                'user' => $user, 
                'token' => $token
            ]);
        }

        return response()->json([
            'status' => 'failed',
            'message' => 'The provided credentials do not match our records.'
        ], 401);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'You has been logout.'
        ]);
    }

    public function register(Request $request)
    {
        // Validation
        $validatedData = $request->validate([
            'username' => 'required|unique:users',
            'password' => 'required|min:8',
        ]);

        // Create user
        $user = new User();
        $user->username = $validatedData['username'];
        $user->password = Hash::make($validatedData['password']);
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'User registered successfully'
        ], 201);
    }
}
