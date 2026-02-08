<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\JwtRegisterRequest;
use App\Http\Requests\JwtLoginRequest;
use App\Http\Resources\SanctumResource;
use App\Http\Helpers\ApiResponse;
use App\Services\JwtAuthService;

class JwtAuthController extends Controller
{
    public function __construct(protected JwtAuthService $jwtAuthService)
    {
    }

    public function register(JwtRegisterRequest $request)
    {
        $result = $this->jwtAuthService->register($request->validated());

        $responseData = [
            'access_token' => $result['access_token'],
            'refresh_token' => $result['refresh_token'],
            'token_type' => $result['token_type'],
            'expires_in' => $result['expires_in'],
            'refresh_expires_in' => $result['refresh_expires_in'],
            'user' => new SanctumResource($result['user'])
        ];
        return ApiResponse::successWithData($responseData, 'User registered successfully', 201);
    }

    public function login(JwtLoginRequest $request)
    {
        $result = $this->jwtAuthService->login($request->only('email', 'password'));

        if (!$result) {
            return ApiResponse::unauthorized('Invalid credentials');
        }

        $responseData = [
            'access_token' => $result['access_token'],
            'refresh_token' => $result['refresh_token'],
            'token_type' => $result['token_type'],
            'expires_in' => $result['expires_in'],
            'refresh_expires_in' => $result['refresh_expires_in'],
            'user' => new SanctumResource($result['user'])
        ];
        return ApiResponse::successWithData($responseData, 'Login successfully');
    }

    public function me()
    {
        $user = $this->jwtAuthService->getCurrentUser();

        if (!$user) {
            return ApiResponse::unauthorized('User not authenticated');
        }
        return ApiResponse::successWithData(['user' => new SanctumResource($user)], 'User data retrieved successfully');
    }

    public function logout()
    {
        $this->jwtAuthService->logout();
        return ApiResponse::success('Successfully logged out');
    }

    public function refresh()
    {
        try {
            $result = $this->jwtAuthService->refreshToken();

            $responseData = [
                'access_token' => $result['access_token'],
                'refresh_token' => $result['refresh_token'],
                'token_type' => $result['token_type'],
                'expires_in' => $result['expires_in'],
                'refresh_expires_in' => $result['refresh_expires_in'],
                'user' => new SanctumResource($result['user'])
            ];
            return ApiResponse::successWithData($responseData, 'Token refreshed successfully');

        } catch (\Exception $e) {
            return ApiResponse::unauthorized('Unable to refresh token');
        }
    }
}
