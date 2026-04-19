<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Common\BaseUserController;
use App\Services\Web\UserService;

class UserController extends BaseUserController
{
    public function __construct(UserService $userService)
    {
        parent::__construct($userService);
    }

}
