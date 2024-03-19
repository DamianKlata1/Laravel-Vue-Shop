<?php

namespace App\Services;

use App\Helpers\CookieCartHelper;
use App\Http\Resources\CartItemResource;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\UserAddress;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CartService
{
    public function getCartData(Request $request): array|RedirectResponse
    {
        $user = $request->user();
        $cartItemsCollection = null;
        $userAddress = null;
        $total = 0;
        if ($user) {
            $cartItems = CartItem::where('user_id', $user->id)->with('product.product_images')->get();
            $userAddress = UserAddress::where('user_id', $user->id)->where('isMain', true)->first();
            if ($cartItems->count() > 0) {
                $cartItemsCollection = CartItemResource::collection($cartItems);
                $total = $cartItems->reduce(fn(?float $carry, CartItem $cartItem) => $carry + $cartItem->product->price * $cartItem->quantity);
            } else {
                return redirect()->route('user.home')->with('info', 'Your cart is empty!');
            }
        } else {
            $cookieCartItems = CookieCartHelper::getCartItems();
            if (count($cookieCartItems) > 0) {
                $formattedCartItems = collect($cookieCartItems)->map(function ($cartItem) {
                    return [
                        'id' => null,
                        'product_id' => $cartItem['product_id'],
                        'quantity' => $cartItem['quantity'],
                    ];
                });
                $cartItemsCollection = CartItemResource::collection($formattedCartItems);
                $total = $formattedCartItems->reduce(fn(?float $carry, $cartItem) => $carry + Product::find($cartItem['product_id'])->price * $cartItem['quantity']);
            } else {
                return redirect()->route('user.home')->with('info', 'Your cart is empty!');
            }
        }
        return [
            'cartItems' => $cartItemsCollection,
            'userAddress' => $userAddress,
            'total' => $total
        ];
    }
}
