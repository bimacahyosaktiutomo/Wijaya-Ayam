<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
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
            'cartItems' => $cartItems, // gak perlu sih sebenernya, soalnya valuenya global
            'totalPrice' => $totalPrice, //--> di Middleware/HandleInertiaRequests.php
        ]);
    }

    public function dashboard() {
        $orders = Order::latest()->get();
        return Inertia::render('Admin/OrderDashboard', [
            'orders' => $orders,
        ]);
    }

    public function history() {
        $orders = Order::where('id_pelanggan', Auth::id())->latest()->get();
        return Inertia::render('OrderHistory', [
            'orders' => $orders,
        ]);
    }

    public function detail($idPemesanan) {
        $order = Order::where('id_pelanggan', Auth::id())
            ->where('id_pemesanan', $idPemesanan)
            ->first(); // Kalo nyari sebiji pakai first
        $items = OrderDetail::where('id_pelanggan', Auth::id())
        ->where('id_pemesanan', $idPemesanan)
        ->latest()
        ->get();
        return Inertia::render('OrderDetails', [
            'order' => $order,
            'items' => $items,
        ]);
    }
    
    public function fromDashboardDetail($idPemesanan) {
        $order = Order::where('id_pemesanan', $idPemesanan)->first(); // Kalo nyari sebiji pakai first
        $idPelanggan = $order->id_pelanggan;
        $items = OrderDetail::where('id_pelanggan', $idPelanggan)
        ->where('id_pemesanan', $idPemesanan)
        ->latest()
        ->get();
        return Inertia::render('OrderDetails', [
            'order' => $order,
            'items' => $items,
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_pelanggan' => 'required',
            'nama_pelanggan' => 'required',
            'no_telepon' => 'required',
            'status_pemesanan' => 'required',
            'total_harga' => 'required|numeric',
            'alamat_pengiriman' => 'required|string',
        ]);

        
        $order = Order::create($validatedData);
        if ($order) {
            $cartItems = Cart::session(Auth::id())->getContent();
            foreach ($cartItems as $item) {
                $orderDetail = OrderDetail::create([
                    'id_pemesanan' => $order->id_pemesanan,
                    'id_pelanggan' => Auth::id(),
                    'nama_produk' => $item->name,
                    'kuantitas' => $item->quantity,
                    'harga_satuan' => $item->price,
                ]);

                $product = Product::find($item->id);
                if ($product) {
                    $product->stok -= $item->quantity;
                    $product->save();
                }
            }
            Cart::session(Auth::id())->clear();
        }

        return Inertia::location(route('details', ['id' => $order->id_pemesanan]));
    }

    public function updateStatus(Request $request, $idPemesanan)
    {
        $validatedData = $request->validate([
            'status_pemesanan' => 'required|in:Diantar,Selesai',
        ]);
    
        $order = Order::where('id_pemesanan', $idPemesanan)->first();
    
        if ($order) {
            $order->update([
                'status_pemesanan' => $validatedData['status_pemesanan'],
            ]);
        }
    }
    
}
