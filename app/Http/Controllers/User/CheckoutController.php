<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\CheckoutRequest;
use App\Services\CheckoutService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    private $checkoutService;

    public function __construct(CheckoutService $checkoutService)
    {
        $this->checkoutService = $checkoutService;
    }

    public function store(CheckoutRequest $request): \Symfony\Component\HttpFoundation\Response
    {
        $user = $request->user();
        $cartItems = $request->cartItems;
        $newAddress = $request->has('address') ? $request->validated()['address'] : null;
        $total = $request->total;

        return $this->checkoutService->processCheckout($user, $cartItems, $newAddress, $total);
    }

    public function success(Request $request): RedirectResponse
    {
        return $this->checkoutService->finalizeCheckout($request);
    }

}
