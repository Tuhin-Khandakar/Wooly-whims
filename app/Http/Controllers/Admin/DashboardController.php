<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $total_orders = Order::count();
        $pending_orders = Order::where('status', 'pending')->count();
        $total_revenue = Order::where('status', 'delivered')->sum('total');
        $total_products = Product::count();
        $recent_orders = Order::latest()->take(10)->get();

        // Comparison Data
        $thisMonthRevenue = Order::where('status', 'delivered')
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('total');
        $lastMonthRevenue = Order::where('status', 'delivered')
            ->whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->sum('total');

        // Initial Chart Data (Last 30 days)
        $chartData = $this->getAnalyticsData(30);

        return view('admin.dashboard', compact(
            'total_orders', 
            'pending_orders', 
            'total_revenue', 
            'total_products', 
            'recent_orders',
            'thisMonthRevenue',
            'lastMonthRevenue',
            'chartData'
        ));
    }

    public function analytics(Request $request)
    {
        $days = $request->get('days', 30);
        return response()->json($this->getAnalyticsData($days));
    }

    protected function getAnalyticsData($days)
    {
        $startDate = Carbon::now()->subDays($days);

        // Revenue Trend
        $revenueTrend = Order::where('status', 'delivered')
            ->where('created_at', '>=', $startDate)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as revenue'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Orders by Status
        $statusBreakdown = Order::where('created_at', '>=', $startDate)
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        // Top 5 Products
        $topProducts = OrderItem::where('created_at', '>=', $startDate)
            ->select('product_name', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(subtotal) as total_revenue'))
            ->groupBy('product_name')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        return [
            'revenue' => [
                'labels' => $revenueTrend->pluck('date'),
                'values' => $revenueTrend->pluck('revenue')
            ],
            'status' => [
                'labels' => $statusBreakdown->pluck('status'),
                'values' => $statusBreakdown->pluck('count')
            ],
            'products' => $topProducts
        ];
    }
}
