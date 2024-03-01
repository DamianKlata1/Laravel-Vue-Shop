<?php

namespace App\Http\Controllers\User;

use App\Helpers\CookieCartHelper;
use App\Helpers\UserCartHelper;
use App\Http\Controllers\Controller;
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

    public function store(Request $request): \Symfony\Component\HttpFoundation\Response
    {
        $user = $request->user();
        $cartItems = $request->cartItems;
        try {
            $newAddress = $request->validate([
                'address.type' => 'required|string|max:45',
                'address.address' => 'required|string',
                'address.city' => 'required|string',
                'address.state' => 'required|string',
                'address.zipcode' => 'required|string',
                'address.country_code' => 'required|string|size:3',
            ]);
        }catch (\Exception $e) {
            return redirect()->to((route('cart.view')))->with('error', 'Invalid address'. $e->getMessage());
        }
        $total = $request->total;

        return $this->checkoutService->processCheckout($user, $cartItems, $newAddress['address'], $total);
    }

    public function success(Request $request): RedirectResponse
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_KEY'));
        $sessionId = $request->get('session_id');
        try {
            $session = \Stripe\Checkout\Session::retrieve($sessionId);
            if (!$session) {
                throw new NotFoundHttpException;
            }
            $order = Order::where('session_id', $session->id)->first();
            if (!$order) {
                throw new NotFoundHttpException();
            }
            if ($order->status === 'unpaid') {
                $order->update(['status' => 'paid']);
            }

            return redirect()->route('user.dashboard');
        } catch (\Exception $e) {
            throw new NotFoundHttpException();
        }
    }

}
