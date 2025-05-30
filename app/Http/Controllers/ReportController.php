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
        $lineGroupBy = $request->get('line_group_by', 'month');
        $lineSelectedMonth = $request->get('line_selected_month') ?: now()->format('Y-m');
    
        $selectFormat = $lineGroupBy === 'month'
            ? "DATE_FORMAT(created_at, '%Y-%m')"
            : "DATE(created_at)";

        $query = Order::selectRaw("
                {$selectFormat} as label,
                SUM(total_harga) as total_penjualan,
                COUNT(*) as jumlah_transaksi
            ")
            ->where('status_pemesanan', 'Selesai');

        if ($lineGroupBy === 'day') {
            [$year, $month] = explode('-', $lineSelectedMonth);
            $query->whereRaw('MONTH(created_at) = ?', [$month])
                    ->whereRaw('YEAR(created_at) = ?', [$year]);
        }

        $lineChartData = $query->groupByRaw($selectFormat)
                        ->orderBy('label')
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
                'line_chart_data' => $lineChartData,
                'line_group_by' => $lineGroupBy,
                'line_selected_month' => $lineSelectedMonth,
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
            'line_chart_data' => $lineChartData,
            'line_group_by' => $lineGroupBy,
            'line_selected_month' => $lineSelectedMonth,
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