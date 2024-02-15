<?php

namespace App\Helpers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cookie;

class UserCartHelper
{
    public static function getCount(Authenticatable $user): int
    {
        return CartItem::whereUserId($user->id)?->count();
    }

    public static function getCartItems(Authenticatable $user): Collection|array
    {
        return CartItem::whereUserId($user->id)?->get()->map(
            fn($item) => [
                'product_id' => $item->product_id,
                'quantity' => $item->quantity
            ]
        );
    }
    public static function setCookieCartItemsIntoUser(Authenticatable $user): void
    {
        $userCartItems = CartItem::where(['user_id' => $user->id])?->get()->keyBy('product_id');
        $savedCartItems = [];
        foreach (CookieCartHelper::getCartItems() as $cartItem) {
            if (isset($userCartItems[$cartItem['product_id']])) {
//               $userCartItems[$cartItem['product_id']]->update(['quantity' => $cartItem['quantity']]);
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
}
