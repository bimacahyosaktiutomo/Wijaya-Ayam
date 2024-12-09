<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use Inertia\Inertia;

class CartController extends Controller
{
    //
    // public function getCartInfo() {
    //     $userId = Auth::id();
    //     $cartItems = Cart::session($userId)->getContent();
    //     $totalItems = $cartItems->count();
    //     return [
    //         'cartItems' => $cartItems,
    //         'totalItems' => $totalItems,
    //     ];
    // }

    public function cart() {
        $userId = Auth::id();
        $cartItems = Cart::session($userId)->getContent();
        $totalPrice = $cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });
        return Inertia::render('Cart', [
            'cartItems' => $cartItems,
            'totalPrice' => $totalPrice,
        ]);
    }

    public function add($itemId) {
        $userId = Auth::id();
        $item = Product::find($itemId);
        Cart::session($userId)->add(array(
            'id' => $itemId,
            'name' => $item->nama_produk,
            'price' => $item->harga,
            'quantity' => 1,
            'attributes' => array(
                'deskripsi' => $item->deskripsi,
                'gambar' => $item->gambar, 
                'stok' => $item->stok, 
            ),
        ));
    }

    public function updateQuantity(Request $request ,$itemId) {
        $userId = Auth::id();
        // $item = Product::find($itemId);
        Cart::session($userId)->update($itemId, array(
            'quantity' => $request->qty,
        ));
    }

    public function remove($itemId) {
        $userId = Auth::id();
        Cart::session($userId)->remove($itemId);
    }
}
