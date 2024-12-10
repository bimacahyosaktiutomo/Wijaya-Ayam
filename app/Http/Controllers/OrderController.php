<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function checkout() {
        $userId = Auth::id();
        $cartItems = Cart::session($userId)->getContent();
        $totalPrice = Cart::session($userId)->getSubTotal();
        return Inertia::render('Checkout', [
            'cartItems' => $cartItems, // gak perlusih sebenernya, soalnya valuenya global
            'totalPrice' => $totalPrice, //--> di Middleware/HandleInertiaRequests.php
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_pelanggan' => 'required',
            'status_pemesanan' => 'required',
            'total_harga' => 'required|numeric',
            'alamat_pengiriman' => 'required|string',
        ]);

        
        $order = Order::create($validatedData);
        $cartItems = Cart::session(Auth::id())->getContent();
        foreach ($cartItems as $item) {
            $orderDetail = OrderDetail::create([
                'id_pemesanan' => $order->id_pemesanan,
                'id_pelanggan' => Auth::id(),
                'nama_produk' => $item->name,
                'kuantitas' => $item->quantity,
                'harga_satuan' => $item->price,
            ]);
        }

        Cart::session(Auth::id())->clear();
        return response()->json(['message' => 'Order created successfully!', 'order' => $order]); // Ganti kehalaman nanti
    }
}
