<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Common\BaseAuthController;
use App\Services\Web\AuthService;

class AuthController extends BaseAuthController
{
    public function __construct(AuthService $authService)
    {
        parent::__construct($authService);
    }

}
