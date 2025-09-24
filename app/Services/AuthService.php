<?php
namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationCodeEmail;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use phpDocumentor\Reflection\Types\This;

class AuthService
{
    protected $userRepository;
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    public function register(array $data)
    {
        $data['OTP'] = rand(1000, 9999);
        $data['verification_code_expires_at'] = now()->addMinutes(5);
        $user = $this->userRepository->create($data);
        Mail::to($user->email)->send(new VerificationCodeEmail($data['OTP']));
        return $user;
    }

    public function checkCode(array $data)
    {
        $user = $this->userRepository->findByEmail($data['email']);
        if ($user && $user->OTP == $data['code']) {
            if ($user->verification_code_expires_at && now()->greaterThan($user->verification_code_expires_at)) {
                return null; // الكود منتهي الصلاحية
            }
            $this->userRepository->markEmailAsVerified($user);
            $token = $this->GenerateToken($user);
            return [
                'token' => $token,
            ];
        }
        return null;
    }

    public function login(array $data)
    {
        $user = $this->userRepository->findByEmail($data['email']);
        if (!$user)
            return null;
        if (is_null($user->email_verified_at))
            return ['error' => 'email_not_verified'];
        if (!Hash::check($data['password'], $user->password))
            return ['error' => 'wrong_password'];
        $token = $this->GenerateToken($user);
        return [
            'token' => $token,
            'user' => $user
        ];
    }
    public function logout($user)
    {
        $this->userRepository->deleteUserTokens($user);
    }

    public function GenerateToken(User $user)
    {
        return $user->createToken('api_token')->plainTextToken;
    }
}