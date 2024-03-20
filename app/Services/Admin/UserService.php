<?php

namespace App\Services\Admin;

use App\Http\Requests\Admin\PasswordUpdateRequest;
use App\Http\Requests\Admin\UserAddRequest;
use App\Http\Requests\Admin\UserUpdateRequest;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function getUsers(): LengthAwarePaginator
    {
        return User::filtered()->paginate(10)->withQueryString();
    }

    /**
     * @throws \Exception
     */
    public function createUser(array $data): void
    {
        try {
            $user = new User();
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->password = Hash::make($data['password']);
            $user->isAdmin = $data['isAdmin'];
            $user->save();
        } catch (\Exception $e) {
            throw new \Exception('User could not be created: ' . $e->getMessage());
        }
    }

    /**
     * @throws \Exception
     */
    public function updateUser(int $userId, array $data): void
    {
        try {
            $user = User::find($userId);
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->isAdmin = $data['isAdmin'];
            $user->save();
        } catch (\Exception $e) {
            throw new \Exception('User could not be updated: ' . $e->getMessage());
        }
    }

    /**
     * @throws \Exception
     */
    public function updatePassword(int $userId, array $data): void
    {
        try{
            $user = User::find($userId);
            $user->password = Hash::make($data['password']);
            $user->save();
        } catch (\Exception $e) {
            throw new \Exception('Password could not be updated: ' . $e->getMessage());
        }
    }

    /**
     * @throws \Exception
     */
    public function deleteUser(int $userId): void
    {
        try {
            User::find($userId)->delete();
        } catch (\Exception $e) {
            throw new \Exception('User could not be deleted: ' . $e->getMessage());
        }
    }

}
