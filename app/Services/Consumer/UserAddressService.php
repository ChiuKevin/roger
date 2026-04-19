<?php

namespace App\Services\Consumer;

use App\Models\UserAddress;
use App\Services\Service;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class UserAddressService extends Service
{
    public function getAddresses(): JsonResponse
    {
        $user = auth()->user();
        $user_addresses = $user->addresses()->get();

        return $this->success($user_addresses);
    }

    public function createAddress(array $data): JsonResponse
    {
        try {
            $data['phone'] = $this->formatPhone($data['country_code'], $data['phone']);

            $user = auth()->user();
            $data = array_merge($data, ['user_id' => $user->id]);

            UserAddress::create($data);

            return $this->success();
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return $this->error($e->getMessage());
        }
    }

    public function showAddress(string $id): JsonResponse
    {
        try {
            $user_address = UserAddress::select('id', 'contact_name', 'email', 'country_code', 'phone', 'address_line1', 'address_line2')->findOrFail($id);

            return $this->success($user_address);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return $this->error($e->getMessage());
        }
    }

    public function updateAddress(array $data, string $id): JsonResponse
    {
        try {
            $user_address = UserAddress::findOrFail($id);
            $user_address->update($data);

            return $this->success();
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return $this->error($e->getMessage());
        }
    }

    public function deleteAddress(string $id): JsonResponse
    {
        $user_address = UserAddress::findOrFail($id);
        $user_address->delete();

        return $this->success();
    }
}
