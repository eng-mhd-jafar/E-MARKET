<?php

namespace App\Exceptions;

use App\Http\Helpers\ApiResponse;
use Exception;

class ResendOtpTooSoonException extends Exception
{
    public function render()
    {
        return ApiResponse::error('please wait until ' . now()->addSeconds(60)->format('H:i:s') . ' to request a new OTP.', 429);
    }
}