<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\KeranjangController;

Route::get('/produks', [ProdukController::class, 'index']);
Route::post('/produks', [ProdukController::class, 'store']);
Route::get('/produks/{produk}', [ProdukController::class, 'show']);
Route::put('/produks/{produk}', [ProdukController::class, 'update']);
Route::delete('/produks/{produk}', [ProdukController::class, 'destroy']);

// KERANJANG
Route::post('/keranjang/tambah/{produkId}', [KeranjangController::class, 'addToCart']);
Route::post('/keranjang/hapus/{produkId}', [KeranjangController::class, 'removeFromCart']);
Route::post('/keranjang/kurang/{produkId}', [KeranjangController::class, 'reduceFromCart']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
