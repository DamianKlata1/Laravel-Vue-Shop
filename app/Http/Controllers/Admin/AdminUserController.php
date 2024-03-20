<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PasswordUpdateRequest;
use App\Http\Requests\Admin\UserAddRequest;
use App\Http\Requests\Admin\UserUpdateRequest;
use App\Http\Requests\UserRequest;
use App\Services\Admin\UserService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;


class AdminUserController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(): Response
    {
        $users = $this->userService->getUsers();

        return Inertia::render('Admin/Users', [
            'users' => $users
        ]);
    }

    public function store(UserAddRequest $request): RedirectResponse
    {
        try {
            $this->userService->createUser($request->validated());
        } catch (\Exception $e) {
            return redirect()->route('admin.users.index')->with('error', 'User could not be created: ' . $e->getMessage());
        }

        return redirect()->route('admin.users.index')->with('success', 'User created successfully');
    }

    public function update(int $userId, UserUpdateRequest $request): RedirectResponse
    {
        try {
            $this->userService->updateUser($userId, $request->validated());
        } catch (\Exception $e) {
            return redirect()->route('admin.users.index')->with('error', 'User could not be updated: ' . $e->getMessage());
        }

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully');
    }

    public function delete(int $userId): RedirectResponse
    {
        try {
            $this->userService->deleteUser($userId);
        } catch (\Exception $e) {
            return redirect()->route('admin.users.index')->with('error', 'User could not be deleted: ' . $e->getMessage());
        }

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully');
    }

    public function updatePassword(int $userId, PasswordUpdateRequest $request): RedirectResponse
    {
        try{
            $this->userService->updatePassword($userId, $request->validated());
        } catch (\Exception $e) {
            return redirect()->route('admin.users.index')->with('error', 'Password could not be updated: ' . $e->getMessage());
        }

        return redirect()->route('admin.users.index')->with('success', 'Password updated successfully');
    }


}
