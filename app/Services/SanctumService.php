<?php
namespace App\Services;

use App\Core\Domain\Interfaces\SanctumRepositoryInterface;
use App\Exceptions\ExpiredOtpException;
use App\Exceptions\FailedAttemptsExceededException;
use App\Exceptions\GoogleLoginFailedException;
use App\Exceptions\InvalidOtpException;
use App\Exceptions\RegistrationFailedException;
use App\Exceptions\ResendOtpTooSoonException;
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
            $data['last_otp_at'] = now();
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

    public function verifyOTP(array $data)
    {
        $user = $this->sanctumRepository->findUserByEmail($data['email']);
        if (!$user) {
            throw new UserNotFoundException();
        }
        if ($user->failed_attempts == 3) {
            throw new FailedAttemptsExceededException();
        }
        if ($user->verification_code_expires_at && now()->greaterThan($user->verification_code_expires_at)) {
            throw new ExpiredOtpException();
        }
        if ($user->OTP != $data['OTP']) {
            $user['failed_attempts'] = $user->failed_attempts + 1;
            $this->sanctumRepository->update($user);
            throw new InvalidOtpException();
        }
        $user['failed_attempts'] = 0;
        $user['email_verified_at'] = now();
        $user['OTP'] = null;
        $user['verification_code_expires_at'] = null;
        $this->sanctumRepository->update($user);
        return [
            'token' => $this->generateToken($user),
        ];
    }

    public function reSendOTP(array $data)
    {
        $user = $this->sanctumRepository->findUserByEmail($data['email']);
        if (!$user) {
            throw new UserNotFoundException();
        }
        if ($user->last_otp_at && now()->diffInSeconds($user->last_otp_at) < 60) {
            throw new ResendOtpTooSoonException();
        }
        $user['OTP'] = rand(1000, 9999);
        $user['verification_code_expires_at'] = now()->addMinutes(5);
        $user['last_otp_at'] = now();
        $user['failed_attempts'] = 0;
        $this->sanctumRepository->update($user);
        Mail::to($user->email)->send(new VerificationCodeEmail($user['OTP']));
    }

    public function login(array $data)
    {
        $user = $this->sanctumRepository->findUserByEmail($data['email']);
        return [
            'token' => $this->generateToken($user),
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
            $token = $this->generateToken($user);
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

    public function generateToken(User $user)
    {
        return $user->createToken('api_token')->plainTextToken;
    }
}
