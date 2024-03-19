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

    public function createUserFromRequest(UserAddRequest $request): void
    {
        $user = new User();
        $user->name = $request->validated()['name'];
        $user->email = $request->validated()['email'];
        $user->password = Hash::make($request->validated()['password']);
        $user->isAdmin = $request->validated()['isAdmin'];
        $user->save();
    }
    public function updateUserFromRequest(int $userId, UserUpdateRequest $request): void
    {
        $user = User::find($userId);
        $user->name = $request->validated()['name'];
        $user->email = $request->validated()['email'];
        $user->isAdmin = $request->validated()['isAdmin'];
        $user->save();
    }
    public function updatePasswordFromRequest(int $userId, PasswordUpdateRequest $request): void
    {
        $user = User::find($userId);
        $user->password = Hash::make($request->validated()['password']);
        $user->save();
    }
    public function deleteUser(int $userId)
    {
        try{
            User::find($userId)->delete();
        } catch (\Exception $e) {
            return redirect()->route('admin.users.index')->with('error', 'User could not be deleted: '. $e->getMessage());
        }
    }

}
