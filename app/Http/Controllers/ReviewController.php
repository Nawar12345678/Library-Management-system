<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use DB;
use app\Models\Auther;
use app\Models\Book;
use App\Models\Review;




class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reviews = Review::all();
        return response()->json([
            'status' => 'success',
            'reviews' => $reviews
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
        
            $review = Review::create([
                'review' => $request->input('review'),
            ]);
        
            DB::commit();
        
            return response()->json([
                'status' => 'success',
                'review' => $review
            ], 201);
        }


        catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th);
            return response()->json([
                'status' => 'error',
            ], 500);
        }

        $book -> Book::where('id', $request->book_id)->first();
        $book -> reviews()->create([
            'review' => $request->review ,
        ]);

        $book -> Auther::where('id', $request->auther_id)->first();
        $book -> reviews()->create([
            'review' => $request->review ,
        ]);
        
    /*  review::withTrashed()->get();
        review::onlyTrashed()->get();

*/
    

        
    }

    /**
     * Display the specified resource.
     */
    public function show(Review $review)
    {
        return response()->json([
            'status'=> 'success',
            'review' => $review
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Review $review)
    {
        if (!Auth::check()) {
            return response()->json([
                'error' => 'Unauthorized'
            ], 401);
        }
        
        if (!Auth::user()->can('update', $review)) {
            return response()->json([
                'error' => 'Permission denied'
            ], 403);
        }
        
        try {
            DB::beginTransaction();
        
            $updatedFields = [];
            if ($request->has('review')) {
                $updatedFields['review'] = $request->input('review');
            }
        
            $auther->update($updatedFields);
        
            DB::commit();
        
            return response()->json([
                'status' => 'success',
                'review' => $review
            ], 200);
        }
        catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th);
            return response()->json([
                'status' => 'error',
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Review $review)
    {

        
        $auther->delete();

        return response()->json([
            'message' => 'Review deleted successfully'
        ], 200);
    }
}
