<?php

namespace App\Exceptions;

use App\Http\Helpers\ApiResponse;
use Exception;

class EmailNotVerifiedException extends Exception
{
    public function render($request)
    {
        return ApiResponse::error('The email is not confirmed', 401);
    }
}
