<?php

namespace App\Exceptions;

use App\Http\Helpers\ApiResponse;
use Exception;

class GoogleLoginFailedException extends Exception
{
    public function render()
    {
        return ApiResponse::error('Google login failed. Please try again.', 500);
    }
}
