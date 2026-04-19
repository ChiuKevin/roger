<?php

namespace App\Http\Controllers\Consumer;

use App\Http\Controllers\Controller;
use App\Rules\CountryCodePhoneRule;
use App\Services\Consumer\UserAddressService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserAddressController extends Controller
{
    protected UserAddressService $userAddressService;

    public function __construct(UserAddressService $userAddressService)
    {
        $this->userAddressService = $userAddressService;
    }

    /**
     * Display current user's addresses.
     */
    public function index(): JsonResponse
    {
        return $this->userAddressService->getAddresses();
    }

    /**
     * Create new address.
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'contact_name'  => 'required|string',
            'email'         => 'required|string|email',
            'country_code'  => ['required', new CountryCodePhoneRule()],
            'phone'         => ['required', new CountryCodePhoneRule()],
            'address_line1' => 'required|string',
            'address_line2' => 'required|string',
        ]);

        return $this->userAddressService->createAddress($data);
    }

    /**
     * Show the specific address.
     */
    public function show(string $id): JsonResponse
    {
        return $this->userAddressService->showAddress($id);
    }

    /**
     * Update the specified address.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $data = $request->validate([
            'contact_name'  => 'nullable|string',
            'email'         => 'nullable|string|email',
            'country_code'  => ['nullable', new CountryCodePhoneRule()],
            'phone'         => ['nullable', new CountryCodePhoneRule()],
            'address_line1' => 'nullable|string',
            'address_line2' => 'nullable|string',
        ]);

        return $this->userAddressService->updateAddress($data, $id);
    }

    /**
     * Remove address.
     */
    public function destroy(string $id): JsonResponse
    {
        return $this->userAddressService->deleteAddress($id);
    }
}
