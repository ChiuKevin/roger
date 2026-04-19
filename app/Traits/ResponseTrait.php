<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ResponseTrait
{
    /**
     * Return a JSON response indicating success.
     *
     * @param mixed $data
     * @param string $message
     * @param int $statusCode
     * @return JsonResponse
     */
    protected function success(mixed $data = [], string $message = 'Success', int $statusCode = 200): JsonResponse
    {
        $data = empty($data) ? (object)[] : $data;

        return response()->json([
            'status'  => $statusCode,
            'message' => $message,
            'data'    => $data
        ], $statusCode);
    }

    /**
     * Return a JSON response indicating success include list & total.
     *
     * @param mixed $data
     * @param mixed $count_data
     * @return JsonResponse
     */
    protected function successList(mixed $data = [], mixed $count_data = []): JsonResponse
    {
        $count_data = $count_data ?: $data;
        $response_data = [
            'list'  => $data,
            'total' => count($count_data),
        ];

        return $this->success($response_data);
    }

    /**
     * Return a JSON response indicating an error.
     *
     * @param string $message
     * @param int $statusCode
     * @return JsonResponse
     */
    protected function error(string $message = 'Error', int $statusCode = 400): JsonResponse
    {
        return response()->json([
            'status'  => $statusCode,
            'message' => $message
        ], $statusCode);
    }

    /**
     * Return a JSON response with token.
     *
     * @param string $token
     * @param array $dataArray
     * @param string $message
     * @param int $statusCode
     * @return JsonResponse
     */
    protected function respondWithToken(string $token, array $dataArray = [], string $message = 'Success', int $statusCode = 200): JsonResponse
    {
        $tokenArray = [
            'token'      => $token,
            'expires_in' => auth()->factory()->getTTL() * 60
        ];
        $responseData = array_merge($tokenArray, $dataArray);

        return $this->success($responseData, $message, $statusCode);
    }
}
