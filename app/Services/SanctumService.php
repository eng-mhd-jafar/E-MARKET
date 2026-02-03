<?php
namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationCodeEmail;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Contracts\User as SocialiteUser;


class SanctumService
{
    protected $userRepository;
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    public function register(array $data)
    {
        try {
            DB::beginTransaction();
            $data['OTP'] = rand(1000, 9999);
            $data['verification_code_expires_at'] = now()->addMinutes(5);
            $user = $this->userRepository->create($data);
            Mail::to($user->email)->send(new VerificationCodeEmail($data['OTP']));
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
        return $user;
    }

    public function checkCode(array $data)
    {
        $user = $this->userRepository->findByEmail($data['email']);
        if ($user && $user->OTP == $data['code']) {
            if ($user->verification_code_expires_at && now()->greaterThan($user->verification_code_expires_at)) {
                return null;
            }
            $this->userRepository->markEmailAsVerified($user);
            return [
                'token' => $this->GenerateToken($user),
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

    public function loginWithGoogle(SocialiteUser $googleUser)
    {
        $user = $this->userRepository->findByEmail($googleUser->getEmail());

        if (!$user) {
            $user = $this->userRepository->create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'email_verified_at' => now(),
                'password' => str()->random(16),
            ]);
        }

        $token = $this->GenerateToken($user);

        return [
            'token' => $token,
            'user' => $user
        ];
    }

    public function GenerateToken(User $user)
    {
        return $user->createToken('api_token')->plainTextToken;
    }


}
