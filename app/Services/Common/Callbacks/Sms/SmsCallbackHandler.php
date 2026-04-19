<?php

namespace App\Services\Common\Callbacks\Sms;

use Illuminate\Http\Request;

interface SmsCallbackHandler
{
    public function getResponse(): string;

    public function handle(Request $request): void;
}
