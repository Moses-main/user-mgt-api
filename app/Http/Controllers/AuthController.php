<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

use Laravel\Sanctum\HasApiTokens;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function login(Request $request)
    {
        // Validate the user credentials
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);
    
        // Check if the user exists
        $user = User::where('email', $request->email)->first();
    
        // Check if user is found and password is correct
        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
    
        // Generate a token for the user (using Sanctum for API authentication)
        $token = $user->createToken('authToken')->plainTextToken;
    
        // Return the token as a response
        return response()->json(['message' => 'User logged in successfully', 'token' => $token], 200);
    }
        
    /**
     * Display the specified resource.
     */
    public function logout(Request $request)
    {
         // Revoke the user's current token
         $request->user()->currentAccessToken()->delete();

         // Respond with a logout message
         return response()->json(['message' => 'Logged out successfully']);
    }

    }
