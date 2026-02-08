<?php

namespace App\Exceptions;

use App\Http\Helpers\ApiResponse;
use Exception;

class ExpiredOtpException extends Exception
{
    public function render($request)
    {
        return ApiResponse::error('OTP has expired', 401);
    }
}
