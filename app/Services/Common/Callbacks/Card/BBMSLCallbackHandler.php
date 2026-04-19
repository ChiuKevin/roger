<?php

namespace App\Services\Common\Callbacks\Card;

use App\Models\UserCard;
use App\Traits\CardBrandTrait;
use Exception;
use Illuminate\Http\Request;

class BBMSLCallbackHandler implements CardCallbackHandler
{
    use CardBrandTrait;

    public function getResponse(): string
    {
        return 'OK';
    }

    /**
     * @throws Exception
     */
    public function handle(Request $request): void
    {
        $request_data = $request->all();
        $signature = $request_data['signature'] ?? null;
        if (is_null($signature)) {
            throw new Exception(__('error.callback.missing_parameters'));
        }
        unset($request_data['signature']);

        ksort($request_data);
        $sign_str = '';
        foreach ($request_data as $k => $v) {
            $sign_str .= $k . '=' . $v . '&';
        }
        $sign_str = rtrim($sign_str, '&');
        $public_key = config('bbmsl.public_key');

        $verify = $this->opensslVerify($sign_str, $signature, $public_key);
        if ($verify) {
            $card_exist = UserCard::where([
                'user_id'    => $request_data['userId'],
                'region'     => 'hk',
                'card_token' => $request_data['tokenId']
            ])->first();

            if (empty($card_exist)) {
                $bind_data = [
                    'user_id'        => $request_data['userId'],
                    'region'         => 'hk',
                    'provider'       => UserCard::PROVIDER_BBMSL,
                    'card_last_four' => substr($request_data['maskedPan'], -4),
                    'brand'          => $this->getCardBrand($request_data['maskedPan']),
                    'exp_month'      => substr($request_data['expiryDate'], -2),
                    'exp_year'       => substr($request_data['expiryDate'], -4, 2),
                    'card_token'     => $request_data['tokenId'],
                    'is_default'     => 0
                ];
                UserCard::create($bind_data);
            }
        } else {
            throw new Exception(__('error.callback.invalid_signature'));
        }
    }

    /**
     * @throws Exception
     */
    private function opensslVerify(string $data, string $signature, string $public_key): bool
    {
        $public_key_pem = "-----BEGIN PUBLIC KEY-----\n" . wordwrap($public_key, 64, "\n", true) . "\n-----END PUBLIC KEY-----";
        $key = openssl_pkey_get_public($public_key_pem);
        if ($key === false) {
            throw new Exception('Failed to load public key.');
        }
        $result = openssl_verify($data, base64_decode($signature), $key, OPENSSL_ALGO_SHA256);
        if ($result === 1) {
            return true;
        } elseif ($result === 0) {
            return false;
        } else {
            throw new Exception('Error during signature verification: ' . openssl_error_string());
        }
    }
}
