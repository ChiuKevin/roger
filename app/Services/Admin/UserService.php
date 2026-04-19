<?php

namespace App\Services\Admin;

use App\Models\User;
use App\Services\Common\BaseUserService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserService extends BaseUserService
{
    /**
     * Get users
     *
     * @return JsonResponse
     */
    public function getUsers(): JsonResponse
    {
        $users = User::all();

        return $this->successList($users);
    }

    /**
     * Create user
     *
     * @param array $data
     * @return JsonResponse
     */
    public function createUser(array $data): JsonResponse
    {
        DB::beginTransaction();

        try {
            $data['phone'] = $this->formatPhone($data['country_code'], $data['phone']);
            $check = $this->findByCountryCodeAndPhone($data['country_code'], $data['phone']);
            if (!empty($check)) {
                return $this->error(__('validation.unique', ['attribute' => 'phone']));
            }

            User::create($data);

            DB::commit();

            return $this->success();
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Failed to create user', [
                'data'  => $data,
                'error' => $e->getMessage(),
            ]);

            return $this->error(__('error.create', ['attribute' => 'user']), 500);
        }
    }

    /**
     * Get user by ID
     *
     * @param string $id
     * @return JsonResponse
     */
    public function getUserById(string $id): JsonResponse
    {
        $user = User::findOrFail($id);

        return $this->success($user);
    }

    /**
     * Update user
     *
     * @param array $data
     * @param string $id
     * @return JsonResponse
     */
    public function updateUser(array $data, string $id): JsonResponse
    {
        DB::beginTransaction();

        try {
            $user = User::find($id);

            if (isset($data['phone'])) {
                $data['phone'] = $this->formatPhone($data['country_code'], $data['phone']);
                $check = $this->findByCountryCodeAndPhone($data['country_code'], $data['phone']);
                if (!empty($check) && $check->id != $id) {
                    return $this->error(__('validation.unique', ['attribute' => 'phone']));
                }
            }

            $user->update($data);

            DB::commit();

            return $this->success();
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Failed to update user', [
                'data'  => $data,
                'error' => $e->getMessage(),
            ]);

            return $this->error(__('error.update', ['attribute' => 'user']), 500);
        }
    }

    /**
     * Delete a user by ID.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function deleteUser(string $id): JsonResponse
    {
        $user = User::findOrFail($id);
        $user->delete();

        return $this->success();
    }
}
