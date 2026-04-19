<?php

namespace App\Services\Common\Providers\Card;

use App\Models\UserCard;
use App\Traits\CardBrandTrait;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class ECPayProvider implements CardProvider
{
    use CardBrandTrait;

    protected string $base_url;
    protected string $merchant_id;
    protected string $hash_key;
    protected string $hash_iv;
    protected string $user_id;

    public function __construct()
    {
        $app_env = config('app.env');
        $env = $app_env === 'production' ? '' : '-stage';
        $this->base_url = 'https://ecpg' . $env . '.ecpay.com.tw';

        $this->merchant_id = config('ecpay.merchant_id');
        $this->hash_key = config('ecpay.hash_key');
        $this->hash_iv = config('ecpay.hash_iv');
        $this->user_id = auth()->user()->id;
    }

    public function add(): array
    {
        $order_info = [
            'MerchantTradeDate' => date('Y/m/d H:i:s'),
            'MerchantTradeNo'   => date('YmdHis') . rand(100000, 999999),
            'TotalAmount'       => 1,
            'TradeDesc'         => 'getToken',
            'ItemName'          => 'getToken',
            'ReturnURL'         => route('callbacks.card', ['provider_id' => UserCard::PROVIDER_ECPAY]),
        ];

        $consumer_info = [
            'MerchantMemberID' => $this->user_id,
        ];

        $request_data = [
            'MerchantID'     => $this->merchant_id,
            'ConsumerInfo'   => $consumer_info,
            'OrderInfo'      => $order_info,
            'OrderResultURL' => route('callbacks.card', ['provider_id' => UserCard::PROVIDER_ECPAY]),
        ];

        $response = $this->makeECPayRequest('/Merchant/GetTokenbyBindingCard', $request_data);

        if ($response['Data']['RtnCode'] === 1) {
            $bearer_token = substr(request()->header('Authorization'), 7);
            $bind_card_url = config('app.url') . '/consumer/user/cards/bindCardPayToken?token=' . $response['Data']['Token'] . '&authorization=' . $bearer_token;
            return ['success' => true, 'content' => $bind_card_url];
        } else
            return ['success' => false, 'content' => $response['Data']['RtnMsg']];
    }

    public function bindCard($data): array
    {
        $request_data = [
            'MerchantID'       => $this->merchant_id,
            'BindCardPayToken' => $data['BindCardPayToken'],
            'MerchantMemberID' => $this->user_id,
        ];

        $response = $this->makeECPayRequest('/Merchant/CreateBindCard', $request_data);

        if ($response['Data']['RtnCode'] === 1) {
            if ($response['Data']['IsSameCard'] === true) {
                return ['success' => true, 'content' => '此信用卡已綁定過了'];
            } else {
                $bind_data = [
                    'user_id'        => $this->user_id,
                    'region'         => app('region'),
                    'provider'       => UserCard::PROVIDER_ECPAY,
                    'card_last_four' => $response['Data']['CardInfo']['Card4No'],
                    'brand'          => $this->getCardBrand($response['Data']['CardInfo']['Card6No']),
                    'exp_month'      => $response['Data']['CardInfo']['CardValidMM'],
                    'exp_year'       => $response['Data']['CardInfo']['CardValidYY'],
                    'card_token'     => $response['Data']['BindCardID'],
                    'is_default'     => 0
                ];
                UserCard::create($bind_data);

                return ['success' => true, 'content' => '信用卡新增綁定成功'];
            }
        } else
            return ['success' => false, 'content' => $response['Data']['RtnMsg']];
    }


    public function query(): array
    {
        $request_data = [
            'MerchantID'       => $this->merchant_id,
            'MerchantMemberID' => $this->user_id,
        ];

        $response = $this->makeECPayRequest('/Merchant/GetMemberBindCard', $request_data);

        if ($response['Data']['RtnCode'] === 1) {
            return ['success' => true, 'content' => $response['Data']['BindCardList']];
        } else
            return ['success' => false, 'content' => $response['Data']['RtnMsg']];
    }

    public function delete(string $token_id): array
    {
        $request_data = [
            'MerchantID' => $this->merchant_id,
            'BindCardID' => $token_id,
        ];

        $response = $this->makeECPayRequest('/Merchant/DeleteMemberBindCard', $request_data);

        if ($response['Data']['RtnCode'] === 1) {
            return ['success' => true];
        } else
            return ['success' => false, 'content' => $response['Data']['RtnMsg']];
    }

    private function makeECPayRequest(string $path, array $request_data): array
    {
        $api_url = $this->base_url . $path;

        $request_data = [
            'MerchantID' => $this->merchant_id,
            'RqHeader'   => [
                'Timestamp' => time(),
            ],
            'data'       => $this->aesEncrypt(json_encode($request_data), $this->hash_key, $this->hash_iv),
        ];

        $response = $this->sendRequest($api_url, $request_data);
        $decrypted_response = $this->aesDecrypt($response['Data'], $this->hash_key, $this->hash_iv);
        $decoded_response = json_decode(urldecode($decrypted_response), true);
        $response['Data'] = $decoded_response;

        return $response;
    }

    private function sendRequest(string $url, array $request_data): array
    {
        $client = new Client();
        try {
            $response_raw = $client->post($url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json'    => $request_data,
            ]);

            return json_decode($response_raw->getBody(), true);
        } catch (GuzzleException $e) {
            Log::error($e->getMessage());
            return ['message' => $e->getMessage()];
        }
    }

    private function aesEncrypt(string $data, string $key, string $iv): string
    {
        $method = 'AES-128-CBC';
        $encrypted = openssl_encrypt($data, $method, $key, OPENSSL_RAW_DATA, $iv);

        return base64_encode($encrypted);
    }

    private function aesDecrypt(string $encrypted_data, string $key, string $iv): false|string
    {
        $method = 'AES-128-CBC';
        $encrypted_data = base64_decode($encrypted_data);

        return openssl_decrypt($encrypted_data, $method, $key, OPENSSL_RAW_DATA, $iv);
    }
}
