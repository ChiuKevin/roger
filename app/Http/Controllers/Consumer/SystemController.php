<?php

namespace App\Http\Controllers\Consumer;

use App\Http\Controllers\Common\BaseSystemController;
use App\Services\Consumer\SystemService;

class SystemController extends BaseSystemController
{
    public function __construct(SystemService $systemService)
    {
        parent::__construct($systemService);
    }
}
