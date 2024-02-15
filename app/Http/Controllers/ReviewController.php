<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Product $product): RedirectResponse
    {
        try {
            $request->validate([
                'rating' => 'required|integer|min:1|max:5',
                'comment' => 'required|string',
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong!');
        }

        $review = new Review;
        $review->user_id = Auth::id();
        $review->product_id = $product->id;
        $review->rating = $request->rating;
        $review->comment = $request->comment;

        $review->save();

        return redirect()->back()->with('success', 'Review added successfully!');
    }

    public function toggleMarkAsHelpful(Review $review): RedirectResponse
    {
        $user = Auth::user();

        //if the review is owned by the user, don't allow to mark as helpful
        if ($review->user_id == $user->id) {
            return redirect()->back()->with('error', 'You cannot mark your own review as helpful!');
        }

        // Check if the user has already marked the review as helpful
        if ($user->helpfulReviews()->where('review_id', $review->id)->exists()) {
            // User has marked as helpful, so unmark it
            $user->helpfulReviews()->detach($review);
            $message = 'Review unmarked as helpful.';
        } else {
            // User hasn't marked as helpful, so mark it
            $user->helpfulReviews()->attach($review);
            $message = 'Review marked as helpful.';
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Review $review): RedirectResponse
    {
        $review->delete();
        return redirect()->back()->with('success', 'Review deleted successfully');
    }
}
