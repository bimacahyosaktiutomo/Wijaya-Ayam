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
            $produkList = OrderDetail::selectRaw('
                    nama_produk,
                    SUM(kuantitas) as total_terjual,
                    SUM(kuantitas * harga_satuan) as total_penjualan
                ')
                ->join('orders', 'orders.id_pemesanan', '=', 'orders_details.id_pemesanan')
                ->where('orders.status_pemesanan', 'Selesai')
                ->groupBy('nama_produk')
                ->orderBy('nama_produk')
                ->get();

            return Inertia::render('Admin/ReportDashboard', [
                'data' => [],
                'produkList' => $produkList,
                'group_by' => $groupBy,
            ]);
        }

        $selectFormat = $groupBy === 'month'
            ? "DATE_FORMAT(created_at, '%Y-%m')"
            : "DATE(created_at)";
            
        $data = Order::selectRaw("
                {$selectFormat} as label,
                SUM(total_harga) as total_penjualan,
                COUNT(*) as jumlah_transaksi
            ")
            ->where('status_pemesanan', 'Selesai')
            ->groupByRaw($selectFormat)
            ->orderByDesc('label')
            ->get();

        $donutChartData = OrderDetail::selectRaw('
                nama_produk as label,
                SUM(kuantitas) as total_terjual
            ')
            ->whereHas('order', function ($query) {
                $query->where('status_pemesanan', 'Selesai');
            })
            ->groupByRaw('nama_produk')
            ->get();

        return Inertia::render('Admin/ReportDashboard', [
            'data' => $data,
            'produkList' => [],
            'donut_chart_data' => $donutChartData,
            'group_by' => $groupBy,
        ]);
    }

    public function productReportByDate(Request $request)
    {
        $tanggal = $request->input('tanggal');
        $produk = $request->input('produk');

        $data = OrderDetail::selectRaw('
                SUM(orders_details.kuantitas * orders_details.harga_satuan) as total_penjualan,
                SUM(orders_details.kuantitas) as total_terjual
            ')
            ->join('orders', 'orders.id_pemesanan', '=', 'orders_details.id_pemesanan')
            ->where('orders.status_pemesanan', 'Selesai')
            ->where('orders_details.nama_produk', $produk)
            ->whereDate('orders.created_at', $tanggal)
            ->first();

        return response()->json($data);
    }
}