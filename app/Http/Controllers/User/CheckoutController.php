<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\CheckoutRequest;
use App\Services\User\CheckoutService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CheckoutController extends Controller
{
    private CheckoutService $checkoutService;

    public function __construct(CheckoutService $checkoutService)
    {
        $this->checkoutService = $checkoutService;
    }

    public function store(Request $request): \Symfony\Component\HttpFoundation\Response
    {
        $checkoutSessionUrl = $this->checkoutService->processCheckout($request->user(), $request->cartItems, $request->total);

        return Inertia::location($checkoutSessionUrl);
    }

    public function success(Request $request): RedirectResponse
    {
        try {
            $this->checkoutService->finalizeCheckout($request->session_id);
        } catch (\Exception $e) {
            return redirect()->route('checkout.cancel')->with('error', 'An error occurred while processing your payment: ' . $e->getMessage());
        }

        return redirect()->route('user.dashboard');
    }

}
