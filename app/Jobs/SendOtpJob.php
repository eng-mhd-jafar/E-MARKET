<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Mail\VerificationCodeEmail;
use Illuminate\Support\Facades\Mail;


class SendOtpJob implements ShouldQueue
{
    use Queueable;

    protected $user;
    protected $otp;

    public function __construct(User $user, string $otp)
    {
        $this->user = $user;
        $this->otp = $otp;
    }

    public function handle(): void
    {
        Mail::to($this->user->email)->send(new VerificationCodeEmail($this->otp));
    }
}
