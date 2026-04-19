<?php

namespace App\Http\Controllers\Consumer;

use App\Http\Controllers\Controller;
use App\Services\Consumer\UserCardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserCardController extends Controller
{
    protected UserCardService $userCardService;

    public function __construct(UserCardService $userCardService)
    {
        $this->userCardService = $userCardService;
    }

    /**
     * Show Current user's credit cards.
     *
     * @return JsonResponse
     * */
    public function index(): JsonResponse
    {
        return $this->userCardService->listCards();
    }

    /**
     * Create a new credit card.
     *
     * ECPay - 返回 blade 頁面，綁定結果會於畫面上顯示。
     *
     * BBMSL - 返回第三方頁面，綁定結果以回調為主。
     *
     * 用戶填寫卡號提交後，app 前端可給自訂提示詞，並決定如何關閉此頁面。
     *
     * @return JsonResponse
     */
    public function store(): JsonResponse
    {
        return $this->userCardService->addCard();
    }

    /**
     * Bind card api for ECPay.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function bindCard(Request $request): JsonResponse
    {
        $data = $request->validate([
            'BindCardPayToken' => 'required|string',
        ]);

        return $this->userCardService->bindCard($data);
    }

    /**
     * Query current user's cards.
     *
     * @return JsonResponse
     */
    public function queryCards(): JsonResponse
    {
        return $this->userCardService->queryCards();
    }

    /**
     * Set default user card.
     *
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $data = $request->validate([
            'is_default' => 'required|boolean',
        ]);

        return $this->userCardService->updateCard($data, $id);
    }

    /**
     * Delete current user's card.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        return $this->userCardService->deleteCard($id);
    }
}