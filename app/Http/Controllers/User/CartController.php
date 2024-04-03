<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\CartStoreRequest;
use App\Http\Resources\CartResource;
use App\Models\Product;
use App\Services\User\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CartController extends Controller
{
    private CartService $cartService;
    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }
    public function view(Request $request): Response|RedirectResponse
    {
        $data = $this->cartService->getCartData($request);
        if (!$data) {
            return redirect()->route('user.home')->with('info', 'Your cart is empty!');
        }

        return Inertia::render('User/CartList', [
                'cartItems' => $data['cartItems'],
                'userAddress' => $data['userAddress'],
                'total' => round($data['total'], 2),
            ]
        );
    }
    public function store(CartStoreRequest $request, Product $product): RedirectResponse
    {
        $this->cartService->store($request, $product);

        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }

    public function update(CartStoreRequest $request, Product $product): RedirectResponse
    {
        $this->cartService->update($request, $product);

        return redirect()->back()->with('success', 'Product updated successfully!');
    }

    public function delete(Request $request, Product $product): RedirectResponse
    {
        $hasItemsLeft = $this->cartService->delete($request, $product);

        if (!$hasItemsLeft) {
            return redirect()->route('user.home')->with('info', 'Your cart is empty!');
        } else {
            return redirect()->back()->with('success', 'Item has been removed!');
        }
    }

}
