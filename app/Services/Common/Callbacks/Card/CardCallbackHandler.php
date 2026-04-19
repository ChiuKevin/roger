<?php

namespace App\Services\Common\Callbacks\Card;

use Illuminate\Http\Request;

interface CardCallbackHandler
{
    public function getResponse(): string;

    public function handle(Request $request): void;
}
