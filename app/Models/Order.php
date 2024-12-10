<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    //
    protected $primaryKey = 'id_pemesanan';
    protected $table = 'orders';
    protected $fillable = ['id_pemesanan', 'id_pelanggan', 'status_pemesanan', 'total_harga', 'alamat_pengiriman'];
}
