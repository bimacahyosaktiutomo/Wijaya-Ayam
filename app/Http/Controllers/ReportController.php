<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $groupBy = $request->get('group_by', 'date');

        if ($groupBy === 'product') {
            $data = OrderDetail::selectRaw('
                    nama_produk as label,
                    SUM(kuantitas * harga_satuan) as total_penjualan,
                    SUM(kuantitas) as total_terjual
                ')
                ->whereHas('order', function ($query) {
                    $query->where('status_pemesanan', 'Selesai');
                })
                ->groupBy('nama_produk')
                ->orderByDesc('total_penjualan')
                ->get();
        } else {
            $selectFormat = $groupBy === 'month' ? "DATE_FORMAT(created_at, '%Y-%m')" : "DATE(created_at)";
            $data = Order::selectRaw("
                    {$selectFormat} as label,
                    SUM(total_harga) as total_penjualan,
                    COUNT(*) as jumlah_transaksi
                ")
                ->where('status_pemesanan', 'Selesai')
                ->groupByRaw($selectFormat)
                ->orderByDesc('label')
                ->get();
        }

        return Inertia::render('Admin/ReportDashboard', [
            'data' => $data,
            'group_by' => $groupBy,
        ]);
    }
}

