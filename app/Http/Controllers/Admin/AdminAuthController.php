<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class AdminAuthController extends Controller
{
    //
    public function showLoginForm(): Response
    {
        return Inertia::render('Admin/Auth/Login');
    }
    public function login(Request $request): RedirectResponse
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'isAdmin' => 1])) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('admin.login')->with('error', 'Invalid credentials');
    }
    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        return redirect()->route('user.home');
    }
}
