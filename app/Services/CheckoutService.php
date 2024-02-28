<?php

namespace App\Services;

use App\Helpers\CookieCartHelper;
use App\Models\CartItem;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\User;
use App\Models\Order;
use App\Models\UserAddress;
use Illuminate\Database\Eloquent\Collection;
use Inertia\Inertia;

class CheckoutService
{
    private $stripe;

    public function __construct(\Stripe\StripeClient $stripe)
    {
        $this->stripe = $stripe;
    }

    public function processCheckout(User $user, array $cartItems, ?array $newAddress, $total): \Symfony\Component\HttpFoundation\Response
    {
        $lineItems = $this->getLineItems($cartItems);
        $checkoutSession = $this->createCheckoutSession($lineItems);
        $address = $this->updateOrGetMainUserAddress($user, $newAddress);

        if ($address) {
            $this->createOrder($user, $address, $total, $checkoutSession);
        } else {
            // No address found
            return Inertia::location($checkoutSession->cancel_url);
        }

        return Inertia::location($checkoutSession->url);
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

    private function updateOrGetMainUserAddress(User $user, ?array $newAddress): ?UserAddress
    {

        if ($newAddress && $this->validateAddress($newAddress)) {
            return $this->updateUserAddress($user, $newAddress);
        }

        return $user->user_addresses()->where('isMain', 1)?->first();
    }

    private function updateUserAddress(User $user, array $newAddress): UserAddress
    {

        $existingAddress = $user->user_addresses()->where('isMain', 1)->first();
        $existingAddress?->update(['isMain' => 0]);

        $newUserAddress = new UserAddress();

        // Set the attributes
        $newUserAddress->address1 = $newAddress['address'];
        $newUserAddress->state = $newAddress['state'];
        $newUserAddress->zipcode = $newAddress['zipcode'];
        $newUserAddress->city = $newAddress['city'];
        $newUserAddress->countryCode = $newAddress['country_code'];
        $newUserAddress->type = $newAddress['type']; // Set the 'type' attribute
        $newUserAddress->user_id = $user->id;
        $newUserAddress->isMain = 1;

        // Save the new user address instance
        $newUserAddress->save();

        return $newUserAddress;
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

    private function validateAddress(array $address): bool
    {
        return isset($address['address'], $address['state'], $address['zipcode'], $address['city'], $address['country_code'], $address['type']);
    }
}
