<?php

namespace App\Exceptions;

use App\Http\Helpers\ApiResponse;
use Exception;

class FailedAttemptsExceededException extends Exception
{
    public function render()
    {
        return ApiResponse::error('You have exceeded the maximum number of failed attempts. please click resend code', 429);
    }
}
