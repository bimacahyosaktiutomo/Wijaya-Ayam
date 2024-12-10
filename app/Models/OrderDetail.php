<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;
    //
    protected $primaryKey = 'id_details';
    protected $table = 'orders';
    protected $fillable = ['id_details', 'id_pemesanan', 'id_pelanggan', 'kuantitas', 'harga_satuan'];
}
