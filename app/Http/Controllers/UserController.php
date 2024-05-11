<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $authers = Auther::all();
        return response()->json([
            'status' => 'success',
            'users' => $users
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        try {
            DB::beginTransaction();
        
            $auther = Auther::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => $request->input('password'),

            ]);
        
            DB::commit();
        
            return response()->json([
                'status' => 'success',
                'user' => $user
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
    public function show(User $user)
    {
        return response()->json([
            'status'=> 'success',
            'user' => $user
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        if (!Auth::check()) {
            return response()->json([
                'error' => 'Unauthorized'
            ], 401);
        }
        
        if (!Auth::user()->can('update', $user)) {
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
            if ($request->has('email')) {
                $updatedFields['email'] = $request->input('email');
            }
            if ($request->has('password')) {
                $updatedFields['password'] = $request->input('password');
            }
        
            $user->update($updatedFields);
        
            DB::commit();
        
            return response()->json([
                'status' => 'success',
                'user' => $user
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
    public function destroy(User $user)
    {
    

        
        $user->delete();

        return response()->json([
            'message' => 'user deleted successfully'
        ], 200);
    }

    public function active(){
        ActiveJob::dispatcher();
        return "Users Active Now";
    }
}
