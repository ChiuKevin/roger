<?php

namespace App\Services\Common\Callbacks\Card;

use App\Models\UserCard;
use Exception;

class CardCallbackHandlerFactory
{
    /**
     * @param int $provider_id
     * @return CardCallbackHandler
     * @throws Exception
     */
    public static function getHandler(int $provider_id): CardCallbackHandler
    {
        return match ($provider_id) {
            UserCard::PROVIDER_ECPAY => new ECPayCallbackHandler(),
            UserCard::PROVIDER_BBMSL => new BBMSLCallbackHandler(),
            default => throw new Exception("No handler found for provider_id: $provider_id")
        };
    }
}
