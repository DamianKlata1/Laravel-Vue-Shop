<?php

namespace App\Helpers;

use App\Models\Product;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cookie;

class CookieCartHelper
{
    public static function getCount(): int
    {
        return count(self::getCartItems());
    }
    public static function getCartItems(): array
    {
        return json_decode(request()->cookie('cart_items', '[]'), true);
    }
    public static function setCartItems(array $cartItems): void
    {
        Cookie::queue('cart_items', json_encode($cartItems), 60 * 24 * 30);
    }
    public static function deleteCartItemByProductId(int $productId): void
    {
        $cartItems = self::getCartItems();
        $cartItems = Arr::where($cartItems, fn($cartItem) => $cartItem['product_id'] !== $productId);
        self::setCartItems($cartItems);
    }
    public static function clearCartItems(): void
    {
        Cookie::queue(Cookie::forget('cart_items'));
    }


}
