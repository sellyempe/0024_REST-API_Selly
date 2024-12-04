<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;



Route::get('/products', [App\Http\Controllers\ProductController::class, 'index']);

Route::post('/register', function (Request $request) {
    $user = User::create([
    'name' => $request->name,
    'email' => $request->email,
    'password' => Hash::make($request->password),
]);
    return response()->json(['message' => 'User registered successfully'], 201);
 });

 Route::post('/login', function (Request $request) {
    $user = User::where('email', $request->email)->first();
    if (! $user || ! Hash::check($request->password, $user->password)) {
    return response()->json(['message' => 'Invalid credentials'], 401);
    }
    $token = $user->createToken('auth_token')->plainTextToken;
    return response()->json(['token' => $token]);
    });

    Route::middleware('auth:sanctum')->post('/products', function (Request $request) {
        $product = Product::create([
        'name' => $request->name,
        'description' => $request->description,
        'price' => $request->price,
        ]);
        return response()->json(['message' => 'Product added successfully'], 201);
        });
       
    Route::middleware('auth:sanctum')->put('/products/{id}', function (Request $request, $id) {
        $product = Product::findOrFail($id);
        // Validasi input
        $request->validate([
        'name' => 'required|string',
        'description' => 'required|string',
        'price' => 'required|numeric',
        ]);
        // Update data produk
        $product->update([
        'name' => $request->name,
        'description' => $request->description,
        'price' => $request->price,
        ]);
        return response()->json(['message' => 'Product updated successfully'], 200);
        });

    Route::middleware('auth:sanctum')->delete('/products/{id}', function ($id) {
        $product = Product::findOrFail($id);
        // Hapus produk
        $product->delete();
        return response()->json(['message' => 'Product deleted successfully'], 200);
        });