<?php

namespace App\Services\User;

use App\Helpers\CookieCartHelper;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CheckoutService
{
    private $stripe;

    public function __construct(\Stripe\StripeClient $stripe)
    {
        $this->stripe = $stripe;
    }

    public function processCheckout(User $user, array $cartItems, $total): string
    {
        $lineItems = $this->getLineItems($cartItems);
        $checkoutSession = $this->createCheckoutSession($lineItems);
        $address = $user->user_addresses()->where('isMain', 1)->first();

        $this->createOrder($user, $address, $total, $checkoutSession);

        return $checkoutSession->url;
    }
    public function finalizeCheckout(string $sessionId): void
    {
        try {
            $session = $this->stripe->checkout->sessions->retrieve($sessionId);

            $order = Order::where('session_id', $session->id)->first();

            if ($order->status === 'unpaid') {
                $order->update(['status' => 'paid']);
            }

        } catch (\Exception $e) {
            throw new NotFoundHttpException('Session not found: ' . $e->getMessage());
        }
    }

    private function createCheckoutSession(array $lineItems): \Stripe\Checkout\Session
    {
        return $this->stripe->checkout->sessions->create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.cancel')
        ]);
    }
    private function createOrder(User $user, UserAddress $mainAddress, $total, $checkoutSession): void
    {
        $order = new Order();
        $order->status = 'unpaid';
        $order->price = $total;
        $order->session_id = $checkoutSession->id;
        $order->created_by = $user->id;
        $order->user_address_id = $mainAddress->id;
        $order->save();

        $this->createOrderItemsAndHandleCart($user, $order);

        $paymentData = [
            'order_id' => $order->id,
            'amount' => $total,
            'status' => 'pending',
            'type' => 'stripe',
            'created_by' => $user->id,
            'updated_by' => $user->id,
        ];

        Payment::create($paymentData);
    }

    private function createOrderItemsAndHandleCart(User $user, Order $order): void
    {
        $cartItems = CartItem::where('user_id', $user->id)->get();

        foreach ($cartItems as $cartItem) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $cartItem->product_id,
                'quantity' => $cartItem->quantity,
                'unit_price' => $cartItem->product->price,
            ]);

            $cartItem->delete();
        }

        CookieCartHelper::clearCartItems();
    }

    private function getLineItems(array $cartItems): array
    {
        $lineItems = [];

        foreach ($cartItems as $cartItem) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $cartItem['product']['title'],
                    ],
                    'unit_amount' => (int)($cartItem['product']['price'] * 100),
                ],
                'quantity' => $cartItem['quantity'],
            ];
        }

        return $lineItems;
    }

}
