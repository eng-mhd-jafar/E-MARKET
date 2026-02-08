<?php

namespace App\Exceptions;

use App\Http\Helpers\ApiResponse;
use Exception;

class RegistrationFailedException extends Exception
{
    public function render($request)
    {
        return ApiResponse::error([
            'message' => 'Registration failed. Please try again.'
        ], 500);
    }
}
