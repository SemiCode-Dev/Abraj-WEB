<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\Api\V1\AuthService;
use App\Http\Requests\Web\V1\LoginRequest;
use App\Http\Requests\Web\V1\RegisterRequest;

class AuthController extends Controller
{
     protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

   
    public function login(LoginRequest $request)
    {
        $data = $this->authService->login($request->validated());
        $token = $data['user']->createToken('auth-token')->plainTextToken;
        $data['token'] = $token;
        return response()->json($data, 200);
    }

    public function register(RegisterRequest $request)
    {
        $data = $this->authService->register($request->validated());
        $token = $data['user']->createToken('auth-token')->plainTextToken;
        $data['token'] = $token;
        return response()->json($data, 201);
    }
}
