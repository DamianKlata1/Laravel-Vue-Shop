<?php

namespace App\Http\Controllers\User;

use App\Helpers\CookieCartHelper;
use App\Helpers\UserCartHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\CheckoutRequest;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\UserAddress;
use App\Services\CheckoutService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
