<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReviewModel;
use App\Models\ProductModel;

class ReviewController extends Controller
{
    
    public function store(Request $request)
    {
        $request->validate([
            'product_id'    => 'required|integer|exists:products,id',
            'reviewer_name' => 'required|string|max:100',
            'rating'        => 'required|integer|min:1|max:5',
            'comment'       => 'required|string|min:5|max:1000',
        ]);

        ReviewModel::create([
            'product_id'    => $request->product_id,
            'customer_id'   => session('client_id') ?? null,
            'reviewer_name' => $request->reviewer_name,
            'rating'        => $request->rating,
            'comment'       => $request->comment,
            'status'        => 'pending', // Admin approve வேண்டும்
        ]);

        return redirect()->back()->with('review_success',
            'Thank you! Your review has been submitted and is awaiting approval.');
    }

    
    public function adminIndex()
    {
        $reviews = ReviewModel::with('product')
            ->latest()
            ->paginate(20);

        return view('Admin.reviews', compact('reviews'));
    }

     
    public function approve($id)
    {
        $review = ReviewModel::findOrFail($id);
        $review->update(['status' => 'approved']);

        return redirect()->back()->with('success', 'Review approved!');
    }

     
    public function reject($id)
    {
        $review = ReviewModel::findOrFail($id);
        $review->update(['status' => 'rejected']);

        return redirect()->back()->with('success', 'Review rejected!');
    }

    
    public function destroy($id)
    {
        ReviewModel::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Review deleted!');
    }
}