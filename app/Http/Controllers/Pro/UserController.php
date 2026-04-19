<?php

namespace App\Http\Controllers\Pro;

use App\Http\Controllers\Common\BaseUserController;
use App\Services\Pro\UserService;

class UserController extends BaseUserController
{
    public function __construct(UserService $userService)
    {
        parent::__construct($userService);
    }

}
