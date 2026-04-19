<?php

namespace App\Services\Consumer;

use App\Models\UserCard;
use App\Services\Common\Providers\Card\CardProvider;
use App\Services\Common\Providers\Card\CardProviderFactory;
use App\Services\Service;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserCardService extends Service
{
    public function listCards(): JsonResponse
    {
        $user = auth()->user();
        return $this->success($user->cards(app('region'))->get());
    }

    public function addCard(): JsonResponse
    {
        $response = $this->getProvider()->add();

        if ($response['success']) {
            return $this->success($response['content']);
        } else
            return $this->error($response['content']);
    }

    public function bindCard(array $data): JsonResponse
    {
        $response = $this->getProvider()->bindCard($data);

        if ($response['success']) {
            return $this->success($response['content']);
        } else
            return $this->error($response['content']);
    }

    public function queryCards(): JsonResponse
    {
        $response = $this->getProvider()->query();

        if ($response['success']) {
            return $this->success($response['content']);
        } else
            return $this->error($response['content']);
    }

    public function updateCard(array $data, string $card_id): JsonResponse
    {
        try {
            $user = auth()->user();

            $card = UserCard::where([
                'id'      => $card_id,
                'user_id' => $user->id,
                'region'  => app('region'),
            ])->firstOrFail();

            DB::beginTransaction();

            if ($data['is_default']) {
                if ($card->is_default) {
                    return $this->success();
                }

                UserCard::where([
                    'user_id'    => $user->id,
                    'region'     => app('region'),
                    'is_default' => 1
                ])->update(['is_default' => 0]);

                $card->is_default = 1;
                $card->save();

                DB::commit();
            } else {
                if ($card->is_default) {
                    $card->is_default = 0;
                    $card->save();

                    DB::commit();
                }
            }
            return $this->success();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to update card', [
                'id'    => $card_id,
                'data'  => $data,
                'error' => $e->getMessage(),
            ]);
            return $this->error(__('error.update', ['attribute' => 'card']), 500);
        }
    }

    public function deleteCard(string $card_id): JsonResponse
    {
        $card = UserCard::findOrFail($card_id);
        $response = $this->getProvider()->delete($card->card_token);

        if ($response['success']) {
            $card->delete();
            return $this->success();
        } else
            return $this->error($response['content']);
    }

    private function getProvider(): CardProvider
    {
        return CardProviderFactory::getProvider(app('region'));
    }
}