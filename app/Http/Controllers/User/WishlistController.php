<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\WishlistItem;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class WishlistController extends Controller
{
    public function view(): Response
    {
        $wishlistItems = WishlistItem::where('user_id', auth()->user()->id)->with('product.product_images')->get();
        return Inertia::render('User/Wishlist', [
                'wishlistItems' => $wishlistItems,
            ]
        );
    }

    public function store(Product $product): RedirectResponse
    {
        $user = auth()->user();
        if (WishlistItem::where(['user_id' => $user->id, 'product_id' => $product->id])->first()) {
            return redirect()->back()->with('info', 'Product already in wishlist!');
        }
        else {
            WishlistItem::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
            ]);
        }
        return redirect()->back()->with('success', 'Product added to wishlist!');
    }

    public function delete(WishlistItem $wishlistItem): RedirectResponse
    {
        if($wishlistItem->user_id != auth()->user()->id) {
            return redirect()->back()->with('error', 'You cannot delete this item!');
        }
        $wishlistItem->delete();
        return redirect()->back()->with('success', 'Product removed from wishlist!');
    }

}
