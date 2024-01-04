<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::filtered()->paginate(10)->withQueryString();
        return Inertia::render('Admin/Users',[
            'users' => $users
        ]);
    }
    public function store(Request $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->save();
        return redirect()->route('admin.users.index')->with('success', 'User created successfully');
    }
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $user->name = $request->name;
        $user->save();
        return redirect()->route('admin.users.index')->with('success', 'User updated successfully');
    }
    public function delete($id)
    {
        $user = User::find($id);
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully');
    }


}
