<?php

namespace App\Http\Controllers\Pro;

use App\Http\Controllers\Common\BaseSystemController;
use App\Services\Pro\SystemService;

class SystemController extends BaseSystemController
{
    public function __construct(SystemService $systemService)
    {
        parent::__construct($systemService);
    }
}
