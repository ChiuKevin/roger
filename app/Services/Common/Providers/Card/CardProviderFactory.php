<?php

namespace App\Services\Common\Providers\Card;

class CardProviderFactory
{
    public static function getProvider(string $region): CardProvider
    {
        return match ($region) {
            'tw' => new ECPayProvider(),
            default => new BBMSLProvider(),
        };
    }
}
