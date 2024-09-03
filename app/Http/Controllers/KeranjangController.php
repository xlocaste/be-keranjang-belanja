<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Produk;
use App\Models\Keranjang;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Model;
use App\Http\Resources\KeranjangResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class KeranjangController extends Controller
{
    public function index(Request $request, string $userId)
    {
        // Cek apakah user ada berdasarkan userId dari URL
        $user = User::findOrFail($userId);

        // Ambil semua item di keranjang untuk user ini
        $keranjangItems = Keranjang::where('user_id', $userId)->get();

        // Loop melalui item keranjang untuk menyertakan nama produk
        $result = $keranjangItems->map(function ($item) {
            $produk = Produk::find($item->produk_id);
            return [
                'produk_id' => $item->produk_id,
                'nama_produk' => $produk->nama,
                'quantity' => $item->quantity,
                'kategori' => $produk->kategori,
            ];
        });

        // Kembalikan respons dengan data keranjang
        return response()->json([
            'success' => true,
            'data' => $result,
        ]);
    }

    public function addToCart(Request $request, $produkId)
    {
        $userId = $request->input('user_id'); // Ambil user_id dari request

        if (!$userId) {
            return response()->json(['message' => 'User ID is required.'], 400);
        }

        $produk = Produk::findOrFail($produkId);

        $keranjang = Keranjang::where('user_id', $userId)
                    ->where('produk_id', $produkId)
                    ->first();

        if ($keranjang) {
            // Jika produk sudah ada di keranjang, tambahkan quantity
            $keranjang->quantity += 1;
        } else {
            // Jika produk belum ada di keranjang, tambahkan produk baru
            $keranjang = new Keranjang();
            $keranjang->user_id = $userId;
            $keranjang->produk_id = $produkId;
            $keranjang->quantity = 1;
        }

        $keranjang->save();

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil ditambahkan!',
        ]);
    }

    public function removeFromCart(Request $request, $produkId)
    {
        $userId = $request->input('user_id'); // Ambil user_id dari request

        if (!$userId) {
            return response()->json(['message' => 'User ID is required.'], 400);
        }

        // Temukan produk di keranjang pengguna
        $keranjang = Keranjang::where('user_id', $userId)
                    ->where('produk_id', $produkId)
                    ->first();

        if ($keranjang) {
            $keranjang->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil dihapus!',
        ]);
    }

    public function reduceFromCart(Request $request, $produkId)
    {
        $userId = $request->input('user_id'); // Ambil user_id dari request

        if (!$userId) {
            return response()->json(['message' => 'User ID is required.'], 400);
        }

        // Temukan produk di keranjang pengguna
        $keranjang = Keranjang::where('user_id', $userId)
                    ->where('produk_id', $produkId)
                    ->first();

        if ($keranjang) {
            // Kurangi quantity
            if ($keranjang->quantity > 1) {
                $keranjang->quantity -= 1;
                $keranjang->save();
            } else {
                // Jika quantity menjadi 0, hapus produk dari keranjang
                $keranjang->delete();
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil dikurangkan!',
        ]);
    }

}
