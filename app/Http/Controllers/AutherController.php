<?php

namespace App\Http\Controllers;

use App\Models\Auther;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use DB;

class AutherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $authers = Auther::all();
        return response()->json([
            'status' => 'success',
            'authers' => $authers
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    /*  if (!Auth::check()) {
            return response()->json([
                'error' => 'Unauthorized'
            ], 401);
        }
        
        if (!Auth::user()->can('create', Auther::class)) {
            return response()->json([
                'error' => 'Permission denied'
            ], 403);
        }
        */
        
        try {
            DB::beginTransaction();
        
            $auther = Auther::create([
                'name' => $request->input('name'),
            ]);
        
            DB::commit();
        
            return response()->json([
                'status' => 'success',
                'auther' => $auther
            ], 201);
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
     * Display the specified resource.
     */
    public function show(Auther $auther)
    {
        return response()->json([
            'status'=> 'success',
            'auther' => $auther
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Auther $auther)
    {
        if (!Auth::check()) {
            return response()->json([
                'error' => 'Unauthorized'
            ], 401);
        }
        
        if (!Auth::user()->can('update', $auther)) {
            return response()->json([
                'error' => 'Permission denied'
            ], 403);
        }
        
        try {
            DB::beginTransaction();
        
            $updatedFields = [];
            if ($request->has('name')) {
                $updatedFields['name'] = $request->input('name');
            }
        
            $auther->update($updatedFields);
        
            DB::commit();
        
            return response()->json([
                'status' => 'success',
                'auther' => $auther
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
    public function destroy(Auther $auther)
    {
        if (!Auth::check()) {
            return response()->json([
                'error' => 'Unauthorized'
            ], 401);
        }

        if (!Auth::user()->can('delete', $auther)) {
            return response()->json([
                'error' => 'Permission denied'
            ], 403);
        }

        
        $auther->delete();

        return response()->json([
            'message' => 'Auther deleted successfully'
        ], 200);
    }
}
