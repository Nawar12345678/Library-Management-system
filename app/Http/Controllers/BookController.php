<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use DB;

class BookController extends Controller
{

    public function index()
    {
        $books = Book::with('authors')->get();
            return response()->json([
            'status' => 'success',
            'books' => $books
        ], 200);


    }

    /**
     * Store a newly created book in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
    
            $book = Book::create([
                'title' => $request->input('title'),
            ]);
    
            if ($request->has('authers')) {
                $authers = $request->input('authers');
                foreach ($authers as $authorId) {
                    $author = Author::find($author_id);
                    if ($author) {
                        $book->authers()->attach($auther);
                    }
                }
            }
    
            DB::commit();
    

            SendNewBookNotification::dispatch($book);

            return response()->json([
                'status' => 'success',
                'book' => $book
            ], 201);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th);
            return response()->json([
                'status' => 'error',
            ], 500);
        }
    }
            
    /**
     * Display the specified book.
     */
    public function show(Book $book)
    {
        $book = Book::with('authors')->findOrFail($id);
        return response()->json([
            'status'=> 'success',
            'book' => $book
        ], 200);
    }

    /**
     * Update the specified book in storage.
     */
        public function update(Request $request, Book $book)
        {
            if (!Auth::check()) {
                return response()->json([
                    'error' => 'Unauthorized'
                ], 401);
            }
            
            if (!Auth::user()->can('update', $book)) {
                return response()->json([
                    'error' => 'Permission denied'
                ], 403);
            }
            
            try {
                DB::beginTransaction();
            
                $updatedFields = [];
                if ($request->has('title')) {
                    $updatedFields['title'] = $request->input('title');
                }
            
                $book->update($updatedFields);
            
                DB::commit();
            
                return response()->json([
                    'status' => 'success',
                    'book' => $book
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
     * Remove the specified book from storage.
     */
    public function destroy(Book $book)
    {
    

        
        $book->delete();

        return response()->json([
            'message' => 'Book deleted successfully'
        ], 200);
    }
}
