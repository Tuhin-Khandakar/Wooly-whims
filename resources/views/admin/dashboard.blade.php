@extends('layouts.admin')

@section('header', 'Admin Dashboard')

@section('content')
<div class="space-y-8" x-data="dashboard()">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 italic transition transform hover:scale-105">
            <p class="text-xs font-black text-gray-400 uppercase tracking-[0.2em] mb-2">Delivered Revenue</p>
            <h3 class="text-3xl font-black text-gray-900 mb-2">Tk {{ number_format($total_revenue, 2) }}</h3>
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold {{ $thisMonthRevenue >= $lastMonthRevenue ? 'text-green-500' : 'text-red-500' }}">
                    {{ $thisMonthRevenue >= $lastMonthRevenue ? '+' : '' }}Tk {{ number_format($thisMonthRevenue - $lastMonthRevenue, 2) }}
                </span>
                <span class="text-[10px] text-gray-400 capitalize">vs last month</span>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 italic transition transform hover:scale-105">
            <p class="text-xs font-black text-gray-400 uppercase tracking-[0.2em] mb-2">Total Orders</p>
            <h3 class="text-3xl font-black text-gray-900 mb-2">{{ $total_orders }}</h3>
            <p class="text-xs text-brand-sage font-bold">From all time</p>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 italic transition transform hover:scale-105">
            <p class="text-xs font-black text-gray-400 uppercase tracking-[0.2em] mb-2">Pending Orders</p>
            <h3 class="text-3xl font-black text-orange-500 mb-2">{{ $pending_orders }}</h3>
            <p class="text-xs text-gray-400">Needs fulfillment</p>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 italic transition transform hover:scale-105">
            <p class="text-xs font-black text-gray-400 uppercase tracking-[0.2em] mb-2">Active Products</p>
            <h3 class="text-3xl font-black text-gray-900 mb-2">{{ $total_products }}</h3>
            <p class="text-xs text-gray-400 italic underline decoration-brand-sage/30">In shop</p>
        </div>
    </div>

    <!-- Analytics Toolbar -->
    <div class="flex flex-col md:flex-row justify-between items-center gap-4 bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
        <h2 class="font-serif text-xl font-bold text-gray-800 italic underline decoration-brand-sage">Real-time Insights</h2>
        <div class="flex items-center gap-3">
            <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Global Range</span>
            <select @change="updateRange($event.target.value)" class="px-4 py-2 border border-gray-100 rounded-xl text-sm font-bold bg-gray-50/50 outline-none focus:border-brand-sage">
                <option value="7">Last 7 Days</option>
                <option value="30" selected>Last 30 Days</option>
                <option value="90">Last 90 Days</option>
            </select>
        </div>
    </div>

    <!-- Charts Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Revenue Line Chart -->
        <div class="lg:col-span-2 bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
            <h3 class="text-sm font-black text-gray-300 uppercase tracking-[0.2em] mb-6">Revenue Trend (Tk)</h3>
            <div class="h-80">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Status Donut Chart -->
        <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
             <h3 class="text-sm font-black text-gray-300 uppercase tracking-[0.2em] mb-6">Order Status</h3>
             <div class="h-80">
                 <canvas id="statusChart"></canvas>
             </div>
        </div>
    </div>

    <!-- Bottom Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Top Products Table -->
        <div class="lg:col-span-1 bg-white p-8 rounded-2xl shadow-sm border border-gray-100 italic">
            <h3 class="text-sm font-black text-gray-300 uppercase tracking-[0.2em] mb-6">Top Performers</h3>
            <div class="space-y-6">
                <template x-for="product in products" :key="product.product_name">
                    <div class="flex items-center justify-between group">
                        <div class="min-w-0 flex-1">
                            <p class="font-bold text-gray-800 truncate" x-text="product.product_name"></p>
                            <p class="text-[10px] text-gray-400 uppercase font-black" x-text="product.total_qty + ' units sold'"></p>
                        </div>
                        <div class="text-right">
                             <p class="font-black text-brand-sage-dark" x-text="'Tk ' + Number(product.total_revenue).toFixed(0)"></p>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- Recent Orders (Keep Existing Table) -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-8 border-b border-gray-50 flex justify-between items-center">
                <h3 class="font-bold text-gray-800">Recent Transactions</h3>
                <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-brand-sage hover:underline">View All &rarr;</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left italic">
                    <thead>
                        <tr class="bg-gray-50/50 text-[10px] font-black uppercase tracking-widest text-gray-400">
                            <th class="py-4 px-8">Order #</th>
                            <th class="py-4 px-8">Customer</th>
                            <th class="py-4 px-8">Total</th>
                            <th class="py-4 px-8 text-center">Status</th>
                            <th class="py-4 px-8"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($recent_orders as $order)
                        <tr class="hover:bg-gray-50/20">
                            <td class="py-4 px-8 font-bold text-gray-900">{{ $order->order_number }}</td>
                            <td class="py-4 px-8">
                                <p class="text-sm font-medium">{{ $order->customer_name }}</p>
                            </td>
                            <td class="py-4 px-8 font-black text-gray-700">Tk {{ number_format($order->total, 2) }}</td>
                            <td class="py-4 px-8 text-center">
                                 <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-tighter bg-gray-100 text-gray-500">
                                     {{ $order->status }}
                                 </span>
                            </td>
                            <td class="py-4 px-8 text-right">
                                <a href="{{ route('admin.orders.show', $order) }}" class="text-xs font-black text-brand-sage hover:text-brand-sage-dark">&rarr;</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    function dashboard() {
        return {
            products: @json($chartData['products']),
            revenueChart: null,
            statusChart: null,
            
            init() {
                this.renderCharts(@json($chartData));
            },
            
            updateRange(days) {
                fetch(`{{ route('admin.analytics.data') }}?days=${days}`)
                    .then(res => res.json())
                    .then(data => {
                        this.products = data.products;
                        this.updateCharts(data);
                    });
            },
            
            renderCharts(data) {
                const colors = ['#8FAF6E', '#2D4A1E', '#D9C8A9', '#FAF8F3', '#666666'];
                
                // Revenue Chart
                this.revenueChart = new Chart(document.getElementById('revenueChart'), {
                    type: 'line',
                    data: {
                        labels: data.revenue.labels,
                        datasets: [{
                            label: 'Daily Revenue',
                            data: data.revenue.values,
                            borderColor: '#8FAF6E',
                            backgroundColor: 'rgba(143, 175, 110, 0.1)',
                            fill: true,
                            tension: 0.4,
                            pointRadius: 4,
                            pointBackgroundColor: '#fff',
                            borderWidth: 3
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, grid: { borderDash: [5, 5] } },
                            x: { grid: { display: false } }
                        }
                    }
                });

                // Status Chart
                this.statusChart = new Chart(document.getElementById('statusChart'), {
                    type: 'doughnut',
                    data: {
                        labels: data.status.labels,
                        datasets: [{
                            data: data.status.values,
                            backgroundColor: colors,
                            borderWidth: 0,
                            hoverOffset: 15
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '70%',
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: { usePointStyle: true, font: { size: 10, weight: 'bold' } }
                            }
                        }
                    }
                });
            },
            
            updateCharts(data) {
                this.revenueChart.data.labels = data.revenue.labels;
                this.revenueChart.data.datasets[0].data = data.revenue.values;
                this.revenueChart.update();
                
                this.statusChart.data.labels = data.status.labels;
                this.statusChart.data.datasets[0].data = data.status.values;
                this.statusChart.update();
            }
        }
    }
</script>
@endsection
