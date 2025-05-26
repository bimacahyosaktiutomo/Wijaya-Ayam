<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReportController extends Controller
{
    public function dashboard(Request $request)
    {
        $groupBy = $request->get('group_by', 'date');
        $donutChartData = OrderDetail::selectRaw('
                nama_produk as label,
                SUM(kuantitas) as total_terjual
            ')
            ->whereHas('order', function ($query) {
                $query->where('status_pemesanan', 'Selesai');
            })
            ->groupByRaw('nama_produk')
            ->get();

        // LineChart
        $groupByLineData = $request->get('line_group_by', 'month');

        if ($groupByLineData === 'month') {
            $selectLineDataFormat = "DATE_FORMAT(created_at, '%b %y')"; // e.g. Jan 24
            $orderByFormat = "STR_TO_DATE(label, '%b %y')";
        } else {
            $selectLineDataFormat = "DATE_FORMAT(created_at, '%d %b %y')"; // e.g. 15 Jan 24
            $orderByFormat = "STR_TO_DATE(label, '%d %b %y')";
        }

        $lineData = Order::selectRaw("
                {$selectLineDataFormat} as label,
                SUM(total_harga) as total_penjualan,
                COUNT(*) as jumlah_transaksi
            ")
            ->where('status_pemesanan', 'Selesai')
            ->groupByRaw($selectLineDataFormat)
            ->orderByRaw("{$orderByFormat} ASC")
            ->get();

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
                'donut_chart_data' => $donutChartData,
                'line_chart_data' => $lineData,
                'line_group_by' => $groupByLineData,
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

        return Inertia::render('Admin/ReportDashboard', [
            'data' => $data,
            'produkList' => [],
            'donut_chart_data' => $donutChartData,
            'line_chart_data' => $lineData,
            'line_group_by' => $groupByLineData,
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

    public function productReportByDateRange(Request $request)
    {
        $startDate = $request->input('start');
        $endDate = $request->input('end');
        $produk = $request->input('produk');

        $data = OrderDetail::selectRaw('
                SUM(orders_details.kuantitas * orders_details.harga_satuan) as total_penjualan,
                SUM(orders_details.kuantitas) as total_terjual
            ')
            ->join('orders', 'orders.id_pemesanan', '=', 'orders_details.id_pemesanan')
            ->where('orders.status_pemesanan', 'Selesai')
            ->where('orders_details.nama_produk', $produk)
            ->whereBetween('orders.created_at', [
                $startDate . ' 00:00:00',
                $endDate . ' 23:59:59'
            ])
            ->first();

        return response()->json($data);
    }
}