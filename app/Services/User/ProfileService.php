<?php

namespace App\Services\User;

use App\Models\User;
use App\Models\UserAddress;

class ProfileService
{
    public function updateProfile(User $user, array $data): void
    {
        $user->fill($data);

        if (array_key_exists('email', $data) && $user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();
    }

    public function deleteAccount(User $user): void
    {
        $user->delete();
    }

    public function updateAddress(User $user, array $newAddress): UserAddress
    {
        if (!$this->validateAddress($newAddress)) {
            throw new \InvalidArgumentException('Invalid address data');
        }

        $existingAddress = $user->user_addresses()->where('isMain', 1)->first();
        $existingAddress?->update(['isMain' => 0]);

        $newUserAddress = new UserAddress();

        $newUserAddress->address1 = $newAddress['address'];
        $newUserAddress->state = $newAddress['state'];
        $newUserAddress->zipcode = $newAddress['zipcode'];
        $newUserAddress->city = $newAddress['city'];
        $newUserAddress->countryCode = $newAddress['country_code'];
        $newUserAddress->type = $newAddress['type'];
        $newUserAddress->user_id = $user->id;
        $newUserAddress->isMain = 1;

        $newUserAddress->save();

        return $newUserAddress;
    }

    private function validateAddress(array $address): bool
    {
        return isset($address['address'], $address['state'], $address['zipcode'], $address['city'], $address['country_code'], $address['type']);
    }
}
