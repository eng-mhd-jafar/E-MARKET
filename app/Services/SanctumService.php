<?php
namespace App\Services;

use App\Core\Domain\Interfaces\SanctumRepositoryInterface;
use App\Exceptions\ExpiredOtpException;
use App\Exceptions\GoogleLoginFailedException;
use App\Exceptions\InvalidOtpException;
use App\Exceptions\RegistrationFailedException;
use App\Exceptions\UserNotFoundException;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationCodeEmail;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Illuminate\Support\Facades\Log;


class SanctumService
{
    public function __construct(protected SanctumRepositoryInterface $sanctumRepository)
    {
    }
    public function register(array $data)
    {
        try {
            DB::beginTransaction();
            $data['OTP'] = rand(1000, 9999);
            $data['verification_code_expires_at'] = now()->addMinutes(5);
            $user = $this->sanctumRepository->create($data);
            Mail::to($user->email)->send(new VerificationCodeEmail($data['OTP']));
            DB::commit();
            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Registration failed: ' . $e->getMessage());
            throw new RegistrationFailedException();
        }
    }

    public function checkCode(array $data)
    {
        $user = $this->sanctumRepository->findUserByEmail($data['email']);
        if (!$user) {
            throw new UserNotFoundException();
        }
        if ($user->OTP != $data['code']) {
            throw new InvalidOtpException();
        }
        if ($user->verification_code_expires_at && now()->greaterThan($user->verification_code_expires_at)) {
            throw new ExpiredOtpException();
        }
        $this->sanctumRepository->markEmailAsVerified($user);
        return [
            'token' => $this->GenerateToken($user),
        ];
    }

    public function login(array $data)
    {
        $user = $this->sanctumRepository->findUserByEmail($data['email']);
        return [
            'token' => $this->GenerateToken($user),
            'user' => $user
        ];
    }

    public function logout($user)
    {
        $this->sanctumRepository->deleteUserTokens($user);
    }

    public function loginWithGoogle(SocialiteUser $googleUser)
    {
        try {
            DB::beginTransaction();
            $user = $this->sanctumRepository->findUserByEmail($googleUser->getEmail());
            if (!$user) {
                $user = $this->sanctumRepository->create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'email_verified_at' => now(),
                    'password' => str()->random(16),
                ]);
            }
            $token = $this->GenerateToken($user);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Google login failed: ' . $e->getMessage());
            throw new GoogleLoginFailedException();
        }
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
