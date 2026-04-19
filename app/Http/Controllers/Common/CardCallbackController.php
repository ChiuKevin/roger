<?php

namespace App\Http\Controllers\Common;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Common\CardCallbackService;

class CardCallbackController extends Controller
{
    protected CardCallbackService $cardCallbackService;

    public function __construct(CardCallbackService $cardCallbackService)
    {
        $this->cardCallbackService = $cardCallbackService;
    }

    /**
     * Handle card callback
     *
     * @param Request $request
     * @param string $provider_id
     * @return string
     * @throws Exception
     */
    public function handleCallback(Request $request, string $provider_id): string
    {
        return $this->cardCallbackService->handleCallback($request, (int)$provider_id);
    }
}
