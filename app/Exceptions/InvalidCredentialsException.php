<?php

namespace App\Exceptions;

use App\Http\Helpers\ApiResponse;
use Exception;

class InvalidCredentialsException extends Exception
{
    public function render()
    {
        return ApiResponse::error('invalid credentials provided', 401);
    }
}
