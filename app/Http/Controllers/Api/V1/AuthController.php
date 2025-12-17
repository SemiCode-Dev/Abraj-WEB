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
        if (!isset($data['user'])) {
            return response()->json($data, 401);
        }
        $token = $data['user']->createToken('auth-token')->plainTextToken;
        $data['token'] = $token;
        return response()->json($data, 200);
    }

    public function register(RegisterRequest $request)
    {
        $data = $this->authService->register($request->validated());
         if (!isset($data['user'])) {
            return response()->json($data, 400);
        }
        $token = $data['user']->createToken('auth-token')->plainTextToken;
        $data['token'] = $token;
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
