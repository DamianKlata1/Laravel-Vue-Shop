<?php

namespace App\Helpers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cookie;

class Cart
{
    public static function getCount()
    {
        $user = auth()->user();
        if ($user) {
            return CartItem::whereUserId($user->id)->count();
        } else {
            return count(self::getCookieCartItems());
        }
    }

    public static function getCartItems()
    {
        $user = auth()->user();
        if ($user) {
            return CartItem::whereUserId($user->id)->get()->map(fn($item) => [
                'product_id' => $item->product_id,
                'quantity' => $item->quantity
            ]);
        } else {
            return self::getCookieCartItems();
        }
    }

    public static function getCookieCartItems()
    {
        return json_decode(request()->cookie('cart_items', '[]'), true);
    }

    public static function setCookieCartItems(array $cartItems)
    {
        Cookie::queue('cart_items', json_encode($cartItems), 60 * 24 * 30);
    }

    public static function clearCookieCartItems()
    {
        Cookie::queue(Cookie::forget('cart_items'));

    }

    public static function saveCookieCartItems()
    {
        $user = auth()->user();
        $userCartItems = CartItem::where(['user_id' => $user->id])->get()->keyBy('product_id');
        $savedCartItems = [];
        foreach (self::getCookieCartItems() as $cartItem) {
            if (isset($userCartItems[$cartItem['product_id']])) {
                $userCartItems[$cartItem['product_id']]->update('quantity', $cartItem['quantity']);
                continue;
            }
            $savedCartItems[] = [
                'user_id' => $user->id,
                'product_id' => $cartItem['product_id'],
                'quantity' => $cartItem['quantity']
            ];

        }
        if (!empty($savedCartItems)) {
            CartItem::insert($savedCartItems);
        }
    }

    public static function moveCartItemsIntoDb()
    {
        $request = request();
        $cartItems = self::getCookieCartItems();
        $newCartItems = [];

        foreach ($cartItems as $cartItem) {
            $existingCartItem = CartItem::where(['user_id' => $request->user()->id, 'product_id' => $cartItem['product_id']])->first();
            if (!$existingCartItem) {
                $newCartItems[] = [
                    'user_id' => $request->user()->id,
                    'product_id' => $cartItem['product_id'],
                    'quantity' => $cartItem['quantity']
                ];

            }
        }
        if (!empty($newCartItems)) {
            CartItem::insert($newCartItems);
        }

    }

    public static function getProductsAndCartItems()
    {
        if (auth()->user()) {
            $cartItems = self::getCartItems();
        } else {
            $cartItems = self::getCookieCartItems();
        }
        $ids = Arr::pluck($cartItems, 'product_id');
        $products = Product::whereIn('id', $ids)->with('product_images')->get();
        $cartItems = Arr::keyBy($cartItems, 'product_id');
        return [$products, $cartItems];
    }
}
