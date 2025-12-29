<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\Api\V1\AuthService;
use App\Http\Requests\Web\V1\LoginRequest;
use App\Http\Requests\Web\V1\RegisterRequest;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    public function __construct(protected AuthService $authService)
    {
    }

   
    public function login(LoginRequest $request)
    {
        $data = $this->authService->login($request->validated());

        if ($data['status'] === 'error') {
            return response()->json($data, 401);
        }

        $user = $data['data'];
        $token = $user->createToken('auth-token')->plainTextToken;
        
        // Add token to the data object
        // We cast to array if it's a model to append token, 
        // or just add it to top level data if preferred. 
        // Based on user request "send data in attribute data", keeping structure consistent.
        // Let's add token to the Top Level response or inside Data?
        // The previous code did $data['token'] = $token. 
        // Let's put it inside 'data' to be cleaner? Or keep as sibling?
        // Standard Laravel resource often has data key.
        // Let's add it to the 'data' array so the client finds everything in 'data'.
        
        // Converting user model to array to add token
        $userData = $user->toArray();
        $userData['token'] = $token;
        $data['data'] = $userData;

        return response()->json($data, 200);
    }

    public function register(RegisterRequest $request)
    {
        $data = $this->authService->register($request->validated());

        if ($data['status'] === 'error') {
             return response()->json($data, 400);
        }

        // The service now returns both 'data' (user) and 'token'
        $user = $data['data'] ?? null;
        $token = $data['token'] ?? null;
        
        if (!$user) {
             return response()->json(['status' => 'error', 'message' => 'User creation failed'], 500);
        }

        // Return user data with token included in 'data' attribute for consistency
        $userData = ($user instanceof \Illuminate\Database\Eloquent\Model) ? $user->toArray() : (array)$user;
        $userData['token'] = $token;
        $data['data'] = $userData;

        return response()->json($data, 201);
    }

    public function logout()
    {
        $data = $this->authService->logout(auth()->user());
        return response()->json($data, 200);
    }

    public function sendOtp(Request $request)
    {
        $request->validate(['identifier' => 'required|string']); // accepts email or phone
        $data = $this->authService->sendOtp($request->identifier);

        if ($data['status'] === 'error') {
            // Check for user not found
            if (str_contains($data['message'], 'Invalid email or phone number')) {
                return response()->json($data, 404);
            }
            return response()->json($data, 400);
        }
        return response()->json($data, 200);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'identifier' => 'required|string',
            'token' => 'required|string'
        ]);

        $result = $this->authService->verifyOtp($request->identifier, $request->token);

        if ($result['status'] === 'error') {
            if (str_contains($result['message'], 'Invalid email or phone number')) {
                return response()->json($result, 404);
            }
            if (str_contains($result['message'], 'Too many attempts')) {
                return response()->json($result, 429);
            }
             return response()->json($result, 401);
        }

        return response()->json($result, 200);
    }

    public function resetPassword(Request $request)
    {
        // Allow email or phone
         $request->validate([
            'email' => 'required|string', // Changed to string to allow phone numbers if client sends phone in 'email' field
            'token' => 'required|string',
            'password' => 'required|string|min:8|confirmed'
        ]);

        $data = $this->authService->resetPassword($request->email, $request->token, $request->password);

        if ($data['status'] === 'error') {
            return response()->json($data, 404);
        }
        return response()->json($data, 200);
    }


}
