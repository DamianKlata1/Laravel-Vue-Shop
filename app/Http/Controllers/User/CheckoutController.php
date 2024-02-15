<?php

namespace App\Http\Controllers\User;

use App\Helpers\UserCartHelper;
use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\UserAddress;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CheckoutController extends Controller
{
    public function store(Request $request): \Symfony\Component\HttpFoundation\Response
    {
        $user = $request->user();
        $products = $request->products;
        $cartItems = $request->cartItems;
        $mergedData = [];
        foreach ($cartItems as $cartItem) {
            foreach ($products as $product) {
                if ($cartItem['product_id'] == $product['id']) {
                    $mergedData[] = array_merge($cartItem, ["title" => $product['title'], "price" => $product['price']]);
                }
            }
        }

        $stripe = new \Stripe\StripeClient(
            env('STRIPE_KEY')
        );

        $lineItems = [];

        foreach ($mergedData as $cartItem) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $cartItem['title'],
                    ],
                    'unit_amount' => (int)($cartItem['price']) * 100,
                ],
                'quantity' => $cartItem['quantity'],
            ];
        }
        $checkout_session = $stripe->checkout->sessions->create([
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.cancel')
        ]);
        $newAddress = $request->address;
        if ($newAddress['address'] != null) {
            $existingAddress = $user->user_addresses()->where('isMain', 1)->first();
            if ($existingAddress) {
                $existingAddress->update(['isMain' => 0]);
            }
            $address = new UserAddress();
            $address->address1 = $newAddress['address'];
            $address->state = $newAddress['state'];
            $address->zipcode = $newAddress['zipcode'];
            $address->city = $newAddress['city'];
            $address->countryCode = $newAddress['country_code'];
            $address->type = $newAddress['type'];
            $address->user_id = $user->id;
            $address->isMain = 1;
            $address->save();
        }
        $mainAddress = $user->user_addresses()->where('isMain', 1)->first();
        if ($mainAddress) {
            $order = new Order();
            $order->status = 'unpaid';
            $order->price = $request->total;
            $order->session_id = $checkout_session->id;
            $order->created_by = $user->id;

            $order->user_address_id = $mainAddress->id;
            $order->save();

            $cartItems = CartItem::where('user_id', $user->id)->get();
            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'unit_price' => $cartItem->product->price,
                ]);
                $cartItem->delete();
                $cartItems = UserCartHelper::getCookieCartItems();
                foreach ($cartItems as $item) {
                    unset($item);
                }
                array_splice($cartItems, 0, count($cartItems));
                UserCartHelper::setCookieCartItems($cartItems);
            }
            $paymentData = [
                'order_id' => $order->id,
                'amount' => $request->total,
                'status' => 'pending',
                'type' => 'stripe',
                'created_by' => $user->id,
                'updated_by' => $user->id,
                //'session_id' => $session->id
            ];

            Payment::create($paymentData);
        }
        return Inertia::location($checkout_session->url);
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
                $order->status = 'paid';
                $order->save();
            }

            return redirect()->route('user.dashboard');
        } catch (\Exception $e) {
            throw new NotFoundHttpException();
        }
    }

}
