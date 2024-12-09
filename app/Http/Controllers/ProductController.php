<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index() {
        $products = Product::latest()->get();
        // return response([
        //     'success' => true,
        //     'message' => 'List Semua Product',
        //     'data' => $products
        // ]);


        return Inertia::render('Home' , [
            'products' => $products,
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
        ]);
    }

    public function dashboard() {
        $products = Product::latest()->get();

        return Inertia::render('Admin/ProductDashboard', [
            'products' => $products,
        ]);
    }

    public function product() {
        $products = Product::latest()->get();

        return Inertia::render('ProductDisplay', [
            'products' => $products,
        ]);
    }

    public function store(Request $request) {
        // Validate data
        $validator = Validator::make($request->all(), [
            'nama_produk' => 'sometimes|required|string|max:255',
            'deskripsi' => 'sometimes|required|string',
            'harga' => 'sometimes|required|integer|min:0',
            'stok' => 'sometimes|required|integer|min:0',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'nama_produk.required' => 'Masukkan nama produk!',
            'deskripsi.required' => 'Masukkan deskripsi produk!',
            'harga.required' => 'Masukkan harga produk!',
            'harga.integer' => 'Harga harus berupa angka!',
            'harga.min' => 'Harga tidak boleh kurang dari 0!',
            'stok.required' => 'Masukkan stok produk!',
            'stok.integer' => 'Stok harus berupa angka!',
            'stok.min' => 'Stok tidak boleh kurang dari 0!',
            'gambar.image' => 'File harus berupa gambar!',
            'gambar.mimes' => 'Gambar harus berformat jpg, jpeg, atau png!',
            'gambar.max' => 'Ukuran gambar maksimal 2 MB!',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Silahkan Isi Bidang Yang Kosong',
                'data' => $validator->errors()
            ], 400);
        }
    
        $imagePath = null;
        if ($request->hasFile('gambar')) {
            $imagePath = $request->file('gambar')->store('products', 'public');
        }
    
        $product = Product::create([
            'nama_produk' => $request->input('nama_produk'),
            'deskripsi' => $request->input('deskripsi'),
            'harga' => $request->input('harga'),
            'stok' => $request->input('stok'),
            'gambar' => $imagePath,
        ]);
        
        return Redirect::route('dashboard.product');
    }
    

    public function show($id) { 

        // Biar ada custom messagenya
        $products = Product::findOrFail($id);

        if ($products) {
            return response()->json([
                'success' => true,
                'message' => 'Detail Post!',
                'data'    => $products
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Product Tidak Ditemukan!',
                'data'    => ''
            ], 404);
        }
    }

    public function edit($id) { 
        $products = Product::findOrFail($id);

        if ($products) {
            return Inertia::render('Admin/ProductForm', [
                'product' => $products, 
                'isEdit' => true,
            ]);
        }
    }

    // ini buat fungsi updatenya
    // kalo edit , buat nampilin data di formnya
    public function update(Request $request, $id) {
        $validateData = $request->validate(
            [
                'nama_produk' => 'sometimes|required|string|max:255',
                'deskripsi' => 'sometimes|required|string',
                'harga' => 'sometimes|required|integer|min:0',
                'stok' => 'sometimes|required|integer|min:0',
                'gambar' => 'sometimes|nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]
        );        
        
        $product = Product::findOrFail($id);
        if ($request->hasFile('gambar')) {
            if ($product->gambar) {
                $filePath = Storage::disk('public')->path($product->gambar);
                if (is_file($filePath)) {
                    Storage::disk('public')->delete($product->gambar);
                }
            }
    
            $imagePath = $request->file('gambar')->store('products', 'public');
            $validateData['gambar'] = $imagePath;
        }

        $product->update($validateData);
        return Redirect::route('dashboard.product');
        // if ($product) {
        //     return response()->json([
        //         Product::findOrFail($id),
        //         'success' => true,
        //         'message' => 'Produk Berhasil Diubah!',
        //     ], 200);
        // } else {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Produk Gagal Diubah!',
        //     ], 400);
        // }

    }

    public function destroy($id)
    {
        $products = Product::findOrFail($id);
        if ($products->gambar) {
            $filePath = Storage::disk('public')->path($products->gambar);
            Log::info($filePath);
            if (is_file($filePath)) {
                Storage::disk('public')->delete($products->gambar);
            }
        }
        
        $products->delete();
        // return Redirect::route('dashboard.product');
    }
}
