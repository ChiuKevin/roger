<?php

namespace App\Http\Controllers\Consumer;

use App\Http\Controllers\Common\BaseAuthController;
use App\Services\Consumer\AuthService;

class AuthController extends BaseAuthController
{
    public function __construct(AuthService $authService)
    {
        parent::__construct($authService);
    }

}
