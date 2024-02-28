<?php

namespace App\Http\Controllers\User;

use App\Helpers\UserCartHelper;
use App\Helpers\CookieCartHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\CartItemResource;
use App\Http\Resources\CartResource;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\UserAddress;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CartController extends Controller
{

    public function view(Request $request): Response|RedirectResponse
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
                        'id' => null, // Set id to null or omit it if not needed
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
        return Inertia::render('User/CartList', [
                'cartItems' => $cartItemsCollection,
                'userAddress' => $userAddress,
                'total' => round($total,2),
            ]
        );

    }
    public function store(Request $request, Product $product): RedirectResponse
    {
        $requestedQuantity = $request->post('quantity', 1);
        if($requestedQuantity < 1) {
            return redirect()->back()->with('error', 'Quantity must be at least 1!');
        }
        if($product->quantity < $requestedQuantity) {
            return redirect()->back()->with('error', 'Not enough stock!');
        }
        if($product->published === 0) {
            return redirect()->back()->with('error', 'Product is not available!');
        }
        $user = $request->user();


        if ($user) {
            $cartItem = CartItem::where(['user_id' => $user->id, 'product_id' => $product->id])->first();
            if ($cartItem) {
                $cartItem->update(['quantity' => $cartItem->quantity + $requestedQuantity]);
            } else {
                CartItem::create([
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'quantity' => $requestedQuantity,
                ]);
            }
        } else {
            $cartItems = CookieCartHelper::getCartItems();
            $productExists = false;
            foreach ($cartItems as &$cartItem) {
                if ($cartItem['product_id'] === $product->id) {
                    $cartItem['quantity'] += $requestedQuantity;
                    $productExists = true;
                    break;
                }
            }
            if (!$productExists) {
                $cartItems[] = [
                    'user_id' => null,
                    'product_id' => $product->id,
                    'quantity' => $requestedQuantity,
                ];
            }
            CookieCartHelper::setCartItems($cartItems);
        }
        return redirect()->back()->with('success', 'Product added to cart successfully!');

    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $requestedQuantity = $request->integer('quantity', 1);
        if($requestedQuantity < 1) {
            return redirect()->back()->with('error', 'Quantity must be at least 1!');
        }
        if($product->quantity < $requestedQuantity) {
            return redirect()->back()->with('error', 'Not enough stock!');
        }
        if($product->published === 0) {
            return redirect()->back()->with('error', 'Product is not available!');
        }
        $user = $request->user();

        if ($user) {
            $cartItem = CartItem::where(['user_id' => $user->id, 'product_id' => $product->id])->first();
            $cartItem?->update(['quantity' => $requestedQuantity]);
        } else {
            $cartItems = CookieCartHelper::getCartItems();
            foreach ($cartItems as &$cartItem) {
                if ($cartItem['product_id'] === $product->id) {
                    $cartItem['quantity'] = $requestedQuantity;
                    break;
                }
            }
            CookieCartHelper::setCartItems($cartItems);
        }
        return redirect()->back()->with('success', 'UserCartHelper updated successfully!');
    }

    public function delete(Request $request, Product $product): RedirectResponse
    {
        $user = $request->user();
        if ($user) {
            CartItem::query()->where(['user_id' => $user->id, 'product_id' => $product->id])->first()?->delete();
            if (CartItem::count() <= 0) {
                return redirect()->route('user.home')->with('info', 'Your cart is empty!');
            } else {
                return redirect()->back()->with('success', 'Item has been removed!');
            }
        } else {
            $cartItems = CookieCartHelper::getCartItems();
            foreach ($cartItems as $index => &$cartItem) {
                if ($cartItem['product_id'] === $product->id) {
                    array_splice($cartItems, $index, 1);
                    break;
                }
            }
            CookieCartHelper::setCartItems($cartItems);
            if (count($cartItems) <= 0){
                return redirect()->route('user.home')->with('info', 'Your cart is empty!');
            } else {
                return redirect()->back()->with('success', 'Item has been removed!');
            }

        }
    }

}
