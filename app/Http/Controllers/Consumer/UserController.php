<?php

namespace App\Http\Controllers\Consumer;

use App\Http\Controllers\Common\BaseUserController;
use App\Services\Consumer\UserService;

class UserController extends BaseUserController
{
    public function __construct(UserService $userService)
    {
        parent::__construct($userService);
    }

}
