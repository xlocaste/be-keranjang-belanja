<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;
use App\Http\Requests\ProdukRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProdukResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProdukController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        //get posts
        $produks = Produk::paginate(5);

        //return collection of posts as a resource
        // return new ProdukResource(true, 'List Data Produk', $produks);

        return ProdukResource::collection($produks);
    }


    /**
     * store
     *
     * @param  mixed $request
     * @return void
     */
    public function store(ProdukRequest $request)
    {
        //create produks$produks
        $produks = Produk::create([
            'nama'      => $request->nama,
            'quantity'  => $request->quantity,
            'kategori'  => $request->kategori,
        ]);

        //return response
        return response()->json([
            'message' => 'Produk berhasil ditambahkan!',
        ]);
    }

    /**
     * show
     *
     * @param  mixed $produks
     * @return void
     */
    public function show(Produk $produk)
    {
        // print_r($produks->toArray()); exit;
        //return single produks$produks as a resource
        return new ProdukResource(true, 'Data Produk Ditemukan!', $produk);
    }

    /**
     * update
     *
     * @param  mixed $request
     * @param  mixed $produks
     * @return void
     */
    public function update(ProdukRequest $request, Produk $produk)
    {
        // print_r($produks->toArray()); exit;
            //update produks$produks without Produk
            $produk->update([
                'nama'      => $request->nama,
                'quantity'  => $request->quantity,
                'kategori'  => $request->kategori,
            ]);

        //return response
        return response()->json([
            'message' => 'Produk berhasil diedit!',
        ]);
    }

    /**
     * destroy
     *
     * @param  mixed $produks
     * @return void
     */
    public function destroy(Produk $produk)
    {
        $produk->delete();
        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil dihapus!',
        ]);
    }
}
