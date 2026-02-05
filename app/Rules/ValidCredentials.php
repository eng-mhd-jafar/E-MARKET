<?php

namespace App\Rules;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Hash;

class ValidCredentials implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */

    protected string $email;
    public function __construct(string $email)
    {
        $this->email = $email;
    }
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $user = User::where('email', $this->email)->first();
        
        if (!$user || !Hash::check($value, $user->password)) {
            $fail('Invalid email or password.');
        }
        if ($user && is_null($user->email_verified_at)) {
            $fail('Your email is not verified yet.');
        }
    }
}
