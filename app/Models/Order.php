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
    protected $fillable = ['id_pemesanan', 'id_pelanggan', 'nama_pelanggan', 'no_telepon', 'status_pemesanan', 'total_harga', 'alamat_pengiriman'];

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'id_pemesanan', 'id_pemesanan');
    }
}
