<?php

namespace App\Exceptions;

use App\Http\Helpers\ApiResponse;
use Exception;

class UserNotFoundException extends Exception
{
    public function render($request)
    {
        return ApiResponse::error('User not found', 404);
    }
}
