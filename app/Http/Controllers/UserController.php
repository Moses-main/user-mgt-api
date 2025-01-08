<?php
namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * Display a listing of all users.
     */
    public function index()
    {
        return response()->json(User::all());
    }

    /**
     * Register a new user.
     */
    public function register(Request $request)
    {
        // Validate the incoming data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Return validation errors if any
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Create the new user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'User registered successfully', 'user' => $user], 201);
    }

    /**
     * Store a newly created user resource in storage (for creating a user).
     */
    public function store(Request $request)
    {
        // Validate the incoming data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
        ]);

        // Create the new user
        $user = User::create($validated);

        // Generate a token for the user (Optional, if you need to return a token)
        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json(['message' => 'User created successfully', 'user' => $user, 'token' => $token], 201);
    }

    /**
     * Display a specified user resource.
     */
    public function show(string $id)
    {
        // Find the user by ID
        $user = User::find($id);
        
        // If user not found, return an error
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Return the user data
        return response()->json($user);
    }

    /**
     * Update the specified user resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Find the user by ID
        $user = User::find($id);
        
        // If user not found, return an error
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Validate the incoming data for update
        $validated = $request->validate([
            'name' => 'string|max:255',
            'email' => 'email|unique:users,email,' . $id,
        ]);

        // Update the user data
        $user->update($validated);

        return response()->json(['message' => 'User updated successfully']);
    }

    /**
     * Remove the specified user resource from storage.
     */
    public function destroy(string $id)
    {
        // Find the user by ID
        $user = User::find($id);
        
        // If user not found, return an error
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Delete the user
        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }

    /**
     * Get the profile of the authenticated user.
     */
    public function profile(Request $request)
    {

         // Check for the Authorization header
    $token = $request->header('Authorization');
    if (!$token) {
        return response()->json([
            'message' => 'Access token is missing. Please login to get an access token.',
            'status' => false
        ], 401);
    }

    // Ensure the user is authenticated
    if (!$request->user()) {
        return response()->json([
            'message' => 'Unauthorized. Invalid or expired token.',
            'status' => false
        ], 401);
    }
    // Return the authenticated user's profile
        return response()->json([
            'user' => $request->user(),
            'status' =>true
        ],200) ;
    }

    /**
     * Handle user login.
     */
    public function login(Request $request)
{
    try {
      // Validate the incoming data
      $validator = Validator::make($request->all(), [
        // 'name' => 'required|string|max:255',
        'email' => 'required|email',
        'password' => 'required|min:8',
    ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(), 'status'=>false, 
                'message' => 'validation error']
                , 401);
        }

        // Step 2: Check if the user exists
        $user = User::where('email', $request->email)->first();

        // Step 3: Check password
        if (!Hash::check($request->password, $user->password)) {
        }

        // Step 4: Generate token
        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json(['message' => 'Login successful', 'token' => $token]);
    } catch (\Throwable $th) {
        //throw $th;
        return response()->json(['message' => $th->getMessage(), 'status' => false],
         500);
    }
    
}
        /**
     * Handle user logout.
     */
    public function logout(Request $request)
    {
        // Revoke the user's current token
        $request->user()->currentAccessToken()->delete();

        // Respond with a logout message
        return response()->json(['message' => 'Logged out successfully', 'status' => true], 200);
    }
}
