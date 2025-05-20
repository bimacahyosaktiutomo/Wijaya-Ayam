<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;
    //
    protected $primaryKey = 'id_details';
    protected $table = 'orders_details';
    protected $fillable = ['id_details', 'id_pemesanan', 'id_pelanggan', 'nama_produk', 'kuantitas', 'harga_satuan'];

    public function order()
    {
        return $this->belongsTo(Order::class, 'id_pemesanan', 'id_pemesanan');
    }
}
