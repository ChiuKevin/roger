<?php

namespace App\Services\Common\Providers\Card;

use App\Models\UserCard;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class BBMSLProvider implements CardProvider
{
    private string $base_url;
    private string $merchant_id;
    private string $private_key;
    private string $user_id;

    public function __construct()
    {
        $this->base_url = 'https://payapi.sit.bbmsl.com';//TODO:此為測試網址，正式網址需再確認
        $this->merchant_id = config('bbmsl.merchant_id');
        $this->private_key = config('bbmsl.private_key');
        $this->user_id = auth()->user()->id;
    }

    /**
     * @throws Exception
     */
    public function add(): array
    {
        try {
            $request_array = [
                'merchantId'     => $this->merchant_id,
                'userId'         => $this->user_id,
                'callbackUrl'    => [
                    'notify' => route('callbacks.card', ['provider_id' => UserCard::PROVIDER_BBMSL]),
                ],
                'paymentMethods' => 'CARD'
            ];
            $response = $this->makeBBMSLRequest('/tokenization/add-token', $request_array);
            if (isset($response['responseCode']) && $response['responseCode'] == '0000') {
                return ['success' => true, 'content' => $response['data']];
            } else
                return ['success' => false, 'content' => $response['message']];
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return ['success' => false, 'content' => $e->getMessage()];
        }
    }

    /**
     *為了與 ECPay bindCard 相容所產生的方法
     */
    public function bindCard($data): array
    {
        return ['success' => false, 'content' => $data];
    }

    public function query(): array
    {
        try {
            $request_array = [
                'merchantId' => $this->merchant_id,
                'userId'     => $this->user_id,
            ];
            $response = $this->makeBBMSLRequest('/tokenization/query-token', $request_array);
            if (isset($response['responseCode']) && $response['responseCode'] == '0000') {
                return ['success' => true, 'content' => $response['list']];
            } else
                return ['success' => false, 'content' => $response['message']];
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return ['success' => false, 'content' => $e->getMessage()];
        }
    }

    public function delete(string $token_id): array
    {
        try {
            $request_array = [
                'merchantId' => $this->merchant_id,
                'userId'     => $this->user_id,
                'tokenId'    => $token_id,
            ];

            $response = $this->makeBBMSLRequest('/tokenization/delete-token', $request_array);
            if (isset($response['responseCode']) && $response['responseCode'] == '0000') {
                return ['success' => true];
            } else
                return ['success' => false, 'content' => $response['message']];
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return ['success' => false, 'content' => $e->getMessage()];
        }
    }

    /**
     * @throws Exception
     */
    private function makeBBMSLRequest(string $path, array $request_array): array
    {
        $api_url = $this->base_url . $path;
        $request_content = json_encode($request_array);
        $request_data = [
            'request'   => $request_content,
            'signature' => $this->opensslSign($request_content, $this->private_key)
        ];

        return $this->sendRequest($api_url, $request_data);
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

    /**
     * @throws Exception
     */
    private function opensslSign(string $request_content, string $private_key): string
    {
        $private_key_pem = "-----BEGIN PRIVATE KEY-----\n" . wordwrap($private_key, 64, "\n", true) . "\n-----END PRIVATE KEY-----";
        $key = openssl_pkey_get_private($private_key_pem);
        if ($key === false) {
            throw new Exception('Failed to load private key.');
        }
        $success = openssl_sign($request_content, $encrypted, $key, OPENSSL_ALGO_SHA256);
        if (!$success) {
            throw new Exception('Failed to sign data.');
        }

        return base64_encode($encrypted);
    }
}
