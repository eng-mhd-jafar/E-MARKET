<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserCheckCodeRequest;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Resources\UserResource;
use App\Http\Responces\ApiResponse;
use App\Services\AuthService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuthController extends Controller
{
    public function __construct(protected AuthService $authService) { }

    public function register(UserRegisterRequest $request)
    {
        $user = $this->authService->register($request->validated());
        return ApiResponse::successWithData((new UserResource($user))->resource,'The verification code has been sent to your email. Please check your email.');
    }

    public function CheckCode(UserCheckCodeRequest $request)
    {
        $token = $this->authService->checkCode($request->validated());
        
        if ($token) {
            return ApiResponse::successWithData($token, 'Email verified successfully.');
        }
        return ApiResponse::error('Email verification failed.');
    }

    public function login(UserLoginRequest $request)
    {
        $result = $this->authService->login($request->validated());
        if (!$result) {
            return ApiResponse::error(['message' => 'user not found'], 404);
        }
        if (isset($result['error']) && $result['error'] === 'email_not_verified') {
            return ApiResponse::error('The email is not confirmed', 401);
        }
        if (isset($result['error']) && $result['error'] === 'wrong_password') {
            return ApiResponse::error('The email is not confirmed', 401);
        }
        return ApiResponse::successWithData(['token' => $result['token'], 'user' => new UserResource($result['user'])], 'Login successfully.', 200);
    }

    public function logout()
    {
        $this->authService->logout(Auth::user());
        return ApiResponse::success('Logout successfully.');
    }
}
