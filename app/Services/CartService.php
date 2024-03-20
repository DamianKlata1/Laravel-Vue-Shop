<?php

namespace App\Services;

use App\Helpers\CookieCartHelper;
use App\Http\Requests\User\CartStoreRequest;
use App\Http\Resources\CartItemResource;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CartService
{
    public function getCartData(Request $request): ?array
    {
        $user = $request->user();
        if ($user) {
            $cartData = $this->getCartDataForUser($user);
        } else {
            $cartData = $this->getCartDataForGuest();
        }
        return $cartData;
    }
    public function store(CartStoreRequest $request, Product $product): void
    {
        $requestedQuantity = $request->validated()['quantity'] ?? 1;

        $user = $request->user();

        if ($user) {
            $this->storeCartItemForUser($user, $product, $requestedQuantity);
        } else {
            $this->storeCartItemForGuest($product, $requestedQuantity);
        }
    }
    public function update(CartStoreRequest $request, Product $product): void
    {
        $requestedQuantity = $request->validated()['quantity'] ?? 1;

        $user = $request->user();

        if ($user) {
            $this->updateCartItemForUser($user, $product, $requestedQuantity);
        } else {
            $this->updateCartItemForGuest($product, $requestedQuantity);
        }
    }
    public function delete(Request $request,Product $product): bool
    {
        $user = $request->user();
        if ($user) {
            return $this->deleteCartItemForUser($user, $product);
        } else {
            return $this->deleteCartItemForGuest($product);
        }
    }
    private function getCartDataForUser(User $user): ?array
    {
        $cartItems = $user->cart_items()->with('product.product_images')->get();
        $userAddress = $user->user_addresses()->where('isMain', true)->first();
        if ($cartItems->count() > 0) {
            $cartItemsCollection = CartItemResource::collection($cartItems);
            $total = $cartItems->reduce(fn(?float $carry, CartItem $cartItem) => $carry + $cartItem->product->price * $cartItem->quantity);
        } else {
            return null;
        }
        return [
            'cartItems' => $cartItemsCollection,
            'userAddress' => $userAddress,
            'total' => $total
        ];
    }
    private function getCartDataForGuest(): ?array
    {
        $cookieCartItems = CookieCartHelper::getCartItems();
        if (count($cookieCartItems) > 0) {
            $cartItemsCollection = CartItemResource::collection($cookieCartItems);
            $total = collect($cookieCartItems)->reduce(fn(?float $carry, array $cartItem) => $carry + Product::find($cartItem['product_id'])->price * $cartItem['quantity']);
        } else {
            return null;
        }
        return [
            'cartItems' => $cartItemsCollection,
            'userAddress' => null,
            'total' => $total
        ];
    }
    private function storeCartItemForUser(User $user, Product $product, int $quantity): void
    {
        $cartItem = $user->cart_items()->where('product_id',$product->id)->first();
        if ($cartItem) {
            $cartItem->update(['quantity' => $cartItem->quantity + $quantity]);
        } else {
            $user->cart_items()->create([
                'product_id' => $product->id,
                'quantity' => $quantity,
            ]);
        }
    }
    private function storeCartItemForGuest(Product $product, int $quantity): void
    {
        $cartItems = CookieCartHelper::getCartItems();
        $productExists = false;
        foreach ($cartItems as &$cartItem) {
            if ($cartItem['product_id'] === $product->id) {
                $cartItem['quantity'] += $quantity;
                $productExists = true;
                break;
            }
        }
        if (!$productExists) {
            $cartItems[] = [
                'user_id' => null,
                'product_id' => $product->id,
                'quantity' => $quantity,
            ];
        }
        CookieCartHelper::setCartItems($cartItems);
    }
    private function updateCartItemForUser(User $user, Product $product, int $quantity): void
    {
        $cartItem = $user->cart_items()->where('product_id',$product->id)->first();
        $cartItem?->update(['quantity' => $quantity]);
    }
    private function updateCartItemForGuest(Product $product, int $quantity): void
    {
        $cartItems = CookieCartHelper::getCartItems();
        foreach ($cartItems as &$cartItem) {
            if ($cartItem['product_id'] === $product->id) {
                $cartItem['quantity'] = $quantity;
                break;
            }
        }
        CookieCartHelper::setCartItems($cartItems);
    }
    private function deleteCartItemForUser(User $user, Product $product): bool
    {
        $user->cart_items()->where('product_id',$product->id)->first()?->delete();
        return $user->cart_items()->count() > 0;
    }
    private function deleteCartItemForGuest(Product $product): bool
    {
        $cartItems = CookieCartHelper::getCartItems();
        foreach ($cartItems as $index => &$cartItem) {
            if ($cartItem['product_id'] === $product->id) {
                array_splice($cartItems, $index, 1);
                break;
            }
        }
        CookieCartHelper::setCartItems($cartItems);
        return count($cartItems) > 0;
    }
}
