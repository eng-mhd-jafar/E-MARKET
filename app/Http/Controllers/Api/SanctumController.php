<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Requests\UserCheckCodeRequest;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Resources\SanctumResource;
use App\Http\Helpers\ApiResponse;
use App\Models\User;
use App\Services\SanctumService;
use Illuminate\Support\Facades\Auth;

class SanctumController extends Controller
{
    public function __construct(protected SanctumService $sanctumService)
    {
    }

    public function register(UserRegisterRequest $request)
    {
        $user = $this->sanctumService->register($request->validated());
        return ApiResponse::success('The verification code has been sent to your email. Please check your email.');
    }

    public function CheckCode(UserCheckCodeRequest $request)
    {
        $token = $this->sanctumService->checkCode($request->validated());

        if ($token) {
            return ApiResponse::successWithData($token, 'Email verified successfully.');
        }
        return ApiResponse::error('Email verification failed.');
    }

    public function login(UserLoginRequest $request)
    {
        $result = $this->sanctumService->login($request->validated());

        return ApiResponse::successWithData(
            [
                'token' => $result['token'],
                'user' => new SanctumResource($result['user'])
            ]
            ,
            'Login successfully.',
            200
        );
    }

    public function logout()
    {
        $this->sanctumService->logout(Auth::user());
        return ApiResponse::success('Logout successfully.');
    }

    public function redirectToGoogle()
    {
        $url = Socialite::driver('google')->stateless()->redirect()->getTargetUrl();
        return ApiResponse::successWithData(['url' => $url], 'Google OAuth URL generated.');
    }

    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();
        $result = $this->sanctumService->loginWithGoogle($googleUser);

        if (!$result) {
            return ApiResponse::error(['message' => 'Unable to authenticate with Google'], 401);
        }

        return ApiResponse::successWithData([
            'token' => $result['token'],
            'user' => new SanctumResource($result['user'])
        ], 'Login with Google successful.');
    }

    public function index()
    {
        $UserWithDate = User::with("orders")->get();
        return ApiResponse::successWithData($UserWithDate, 'Users with orders retrieved successfully.');
    }

}
