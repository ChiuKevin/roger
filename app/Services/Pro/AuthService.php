<?php

namespace App\Services\Pro;

use App\Services\Common\BaseAuthService;

class AuthService extends BaseAuthService
{
    public function __construct(UserService $userService)
    {
        parent::__construct($userService);
    }

}
