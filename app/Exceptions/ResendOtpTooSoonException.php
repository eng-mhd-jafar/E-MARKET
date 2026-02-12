<?php

namespace App\Exceptions;

use App\Http\Helpers\ApiResponse;
use Exception;
use Illuminate\Http\Request;

class ResendOtpTooSoonException extends Exception
{

    protected $secondsRemaining;

    public function __construct($secondsRemaining = 60)
    {
        parent::__construct("Too many attempts.");
        $this->secondsRemaining = $secondsRemaining;
    }

    public function render(Request $request)
    {
        return ApiResponse::errorWithData('يرجى الانتظار قبل طلب الرمز مرة أخرى', [
            'seconds_remaining' => (int) $this->secondsRemaining,
        ], 429);
    }
}