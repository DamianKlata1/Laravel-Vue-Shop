<?php

namespace App\Http\Controllers\User;

use App\Helpers\Cart;
use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CartController extends Controller
{

    public function view(Request $request,Product $product)
    {
        $user = $request->user();
        if($user){
            $cartItems = CartItem::where('user_id', $user->id)->get();
            $userAddress = UserAddress::where('user_id', $user->id)->where('isMain',true)->first();
            if($cartItems->count() > 0){
                return Inertia::render('User/CartList',[
                        'cartItems' => $cartItems,
                        'userAddress' => $userAddress,
                    ]
                );
            }
            else{
                return redirect()->route('user.home')->with('info', 'Your cart is empty!');
            }
        }
        else{
            $cartItems = Cart::getCookieCartItems();
            if(count($cartItems) > 0 ){
                $cartItems = new CartResource(Cart::getProductsAndCartItems());
                return Inertia::render('User/CartList',[
                        'cartItems' => $cartItems,
                    ]
                );
            }else{
                return redirect()->route('user.home')->with('info', 'Your cart is empty!');
            }
        }

    }

    public function store(Request $request, Product $product)
    {

        $quantity = $request->post('quantity', 1);
        $user = $request->user();

        if ($user) {
            $cartItem = CartItem::where(['user_id' => $user->id, 'product_id' => $product->id])->first();
            if ($cartItem) {
                $cartItem->update(['quantity' => $cartItem->quantity + $quantity]);
            } else {
                CartItem::create([
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                ]);
            }
        }
        else{
            $cartItems = Cart::getCookieCartItems();
            $productExists = false;
            foreach ($cartItems as &$cartItem) {
                if ($cartItem['product_id'] === $product->id) {
                    $cartItem['quantity'] += $quantity;
                    $productExists = true;
                    break;
                }
            }
            if(!$productExists) {
                $cartItems[] = [
                    'user_id' => null,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $product->price,
                ];
            }
            Cart::setCookieCartItems($cartItems);
        }
        return redirect()->back()->with('success', 'Product added to cart successfully!');

    }

    public function update(Request $request, Product $product)
    {
        $quantity = $request->integer('quantity', 1);
        $user = $request->user();

        if ($user) {
            $cartItem = CartItem::where(['user_id' => $user->id, 'product_id' => $product->id])->first();
            if ($cartItem) {
                $cartItem->update(['quantity' => $quantity]);
            }
        } else {
            $cartItems = Cart::getCookieCartItems();
            foreach ($cartItems as &$cartItem) {
                if ($cartItem['product_id'] === $product->id) {
                    $cartItem['quantity'] = $quantity;
                    break;
                }
            }
            Cart::setCookieCartItems($cartItems);
        }
        return redirect()->back()->with('success', 'Cart updated successfully!');
    }

    public function delete(Request $request, Product $product)
    {
        $user = $request->user();
        if($user){
            CartItem::query()->where(['user_id' => $user->id, 'product_id' => $product->id])->first()?->delete();
            Cart::clearCookieCartItems();
            if(CartItem::count() <= 0){
                return redirect()->route('user.home')->with('info', 'Your cart is empty!');
            }
            else{
                return redirect()->back()->with('success', 'Item has been removed!');
            }
        }
        else{
            $cartItems = Cart::getCookieCartItems();
            foreach ($cartItems as $index => &$cartItem) {
                if ($cartItem['product_id'] === $product->id) {
                    array_splice($cartItems, $index, 1);
                    break;
                }
            }
            Cart::setCookieCartItems($cartItems);
            if(CartItem::count() <= 0){
                return redirect()->route('user.home')->with('info', 'Your cart is empty!');
            }
            else{
                return redirect()->back()->with('success', 'Item has been removed!');
            }

        }
    }

}
