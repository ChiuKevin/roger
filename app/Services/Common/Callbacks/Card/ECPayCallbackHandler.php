<?php

namespace App\Services\Common\Callbacks\Card;

use Exception;
use Illuminate\Http\Request;

class ECPayCallbackHandler implements CardCallbackHandler
{

    public function getResponse(): string
    {
        return '1|OK';
    }

    /**
     * 接收綠界回調，綁定卡片邏輯已於請求時處理。此處僅驗證參數正確性。
     *
     * @throws Exception
     */
    public function handle(Request $request): void
    {
        $request_data = $request->all();
        $data_raw = $request_data['Data'] ?? null;
        if (is_null($data_raw)) {
            throw new Exception(__('error.callback.missing_parameters'));
        }

        $decrypted_data = $this->aesDecrypt($data_raw, config('ecpay.hash_key'), config('ecpay.hash_iv'));
        if (!$decrypted_data) {
            throw new Exception(__('error.callback.invalid_signature'));
        }
    }

    private function aesDecrypt(string $encrypted_data, string $key, string $iv): false|string
    {
        $method = 'AES-128-CBC';
        $encrypted_data = base64_decode($encrypted_data);

        return openssl_decrypt($encrypted_data, $method, $key, OPENSSL_RAW_DATA, $iv);
    }
}
