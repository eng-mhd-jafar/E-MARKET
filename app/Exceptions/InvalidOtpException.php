<?php

namespace App\Exceptions;

use App\Http\Helpers\ApiResponse;
use Exception;

class InvalidOtpException extends Exception
{
    public function render($request)
    {
        return ApiResponse::error('Invalid OTP provided', 401);
    }
}
