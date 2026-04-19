<?php

namespace App\Http\Controllers\Pro;

use App\Http\Controllers\Common\BaseAuthController;
use App\Services\Pro\AuthService;

class AuthController extends BaseAuthController
{
    public function __construct(AuthService $authService)
    {
        parent::__construct($authService);
    }

}
