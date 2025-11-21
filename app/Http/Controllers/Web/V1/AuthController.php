<?php

namespace App\Http\Controllers\Web\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\V1\LoginRequest;
use App\Http\Requests\Web\V1\RegisterRequest;
use App\Services\Api\V1\AuthService;

class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

   
    public function login(LoginRequest $request)
    {
        return response()->json(
            $this->authService->login($request->validated())
        );
    }

    public function register(RegisterRequest $request)
    {
        return response()->json(
            $this->authService->register($request->validated())
        );
    }
}
