<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Illuminate\Validation\Rules;


class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::filtered()->paginate(10)->withQueryString();
        return Inertia::render('Admin/Users', [
            'users' => $users
        ]);
    }

    public function store(Request $request)
    {
        $request->merge(['isAdmin' => $request->isAdmin == 'true']);
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|lowercase|email|max:255|unique:' . User::class,
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'isAdmin' => 'required|boolean'
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->isAdmin = $request->isAdmin;

        $user->save();


        return redirect()->route('admin.users.index')->with('success', 'User created successfully');
    }

    public function update(Request $request, $id)
    {
        $request->merge(['isAdmin' => $request->isAdmin == 'true']);
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|lowercase|email|max:255|unique:' . User::class. ',email,' . $id,
                'isAdmin' => 'required|boolean'
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->isAdmin = $request->isAdmin;


        $user->save();
        return redirect()->route('admin.users.index')->with('success', 'User updated successfully');
    }

    public function delete($id)
    {
        $user = User::find($id);
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully');
    }

    public function updatePassword(Request $request, $id)
    {
        try {
            $request->validate([
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        $user = User::find($id);
        $user->password = Hash::make($request->password);
        $user->save();
        return redirect()->route('admin.users.index')->with('success', 'Password updated successfully');
    }


}
