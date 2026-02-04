<?php
namespace App\Services;

use App\Core\Domain\Interfaces\SanctumRepositoryInterface;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationCodeEmail;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Contracts\User as SocialiteUser;


class SanctumService
{
    public function __construct(protected SanctumRepositoryInterface $sanctumRepository){}
    
    public function register(array $data)
    {
        try {
            DB::beginTransaction();
            $data['OTP'] = rand(1000, 9999);
            $data['verification_code_expires_at'] = now()->addMinutes(5);
            $user = $this->sanctumRepository->create($data);
            Mail::to($user->email)->send(new VerificationCodeEmail($data['OTP']));
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return null;
        }
        return $user;
    }

    public function checkCode(array $data)
    {
        $user = $this->sanctumRepository->findUserByEmail($data['email']);
        if ($user && $user->OTP == $data['code']) {
            if ($user->verification_code_expires_at && now()->greaterThan($user->verification_code_expires_at)) {
                return null;
            }
            $this->sanctumRepository->markEmailAsVerified($user);
            return [
                'token' => $this->GenerateToken($user),
            ];
        }
        return null;
    }

    public function login(array $data)
    {
        $user = $this->sanctumRepository->findUserByEmail($data['email']);
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
        $this->sanctumRepository->deleteUserTokens($user);
    }

    public function loginWithGoogle(SocialiteUser $googleUser)
    {
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
