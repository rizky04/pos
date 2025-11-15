<?php

namespace App\Http\Controllers;

use App\Models\Mechanic;
use App\Models\Sales;
use App\Models\SalesItem;
use App\Models\SalesPayment;
use App\Models\Service;
use App\Models\ServiceSparepart;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    //  public function index()
    // {
    //     $today = Carbon::today();
    //     $month = Carbon::now()->month;

    //     // Card Summary
    //     $summary = [
    //         'services_today' => Service::whereDate('service_date', $today)->count(),
    //         'services_month' => Service::whereMonth('service_date', $month)->count(),
    //         'omzet_today'    => Service::whereDate('service_date', $today)->sum('total_cost'),
    //         'omzet_month'    => Service::whereMonth('service_date', $month)->sum('total_cost'),
    //         'spare_today'    => ServiceSparepart::whereHas('service', fn($q) => $q->whereDate('service_date', $today))->sum('qty'),
    //         'spare_month'    => ServiceSparepart::whereHas('service', fn($q) => $q->whereMonth('service_date', $month))->sum('qty'),
    //     ];

    //     // Grafik omzet 30 hari terakhir
    //     $omzetChart = Service::select(
    //             DB::raw('DATE(service_date) as date'),
    //             DB::raw('SUM(total_cost) as omzet')
    //         )
    //         ->where('service_date', '>=', Carbon::now()->subDays(30))
    //         ->groupBy('date')
    //         ->orderBy('date')
    //         ->get();

    //     // Top Mekanik bulan ini
    //     $topMechanics = Mechanic::select('mechanics.name', DB::raw('COUNT(service_mechanics.service_id) as total'))
    //         ->join('service_mechanics', 'mechanics.id', '=', 'service_mechanics.mechanic_id')
    //         ->join('services', 'services.id', '=', 'service_mechanics.service_id')
    //         ->whereMonth('services.service_date', $month)
    //         ->groupBy('mechanics.name')
    //         ->orderByDesc('total')
    //         ->limit(5)
    //         ->get();

    //     return view('home', compact('summary', 'omzetChart', 'topMechanics'));
    // }

    public function index()
{
    $totalSales = Sales::sum('total');
    $totalPaid = SalesPayment::sum('amount_paid');
    $totalDue = $totalSales - $totalPaid;

    $todaySalesCount = Sales::whereDate('sales_date', now())->count();

    // Grafik Penjualan Bulanan
    $salesPerMonth = Sales::selectRaw('MONTH(sales_date) as month, SUM(total) as total')
        ->groupBy('month')
        ->orderBy('month')
        ->pluck('total', 'month');

    // Barang Terlaris
    $topItems = SalesItem::select('id_barang', DB::raw('SUM(qty) as total_qty'), DB::raw('SUM(subtotal) as total_sales'))
        ->groupBy('id_barang')
        ->with('barang')
        ->orderByDesc('total_qty')
        ->take(5)
        ->get();

    // Status Pembayaran
    $statusCounts = Sales::select('status_bayar', DB::raw('count(*) as count'))
        ->groupBy('status_bayar')
        ->pluck('count', 'status_bayar');
        // Tambahkan di controller
$salesPerMonth = Sales::selectRaw('MONTH(sales_date) as month, SUM(total) as total')
    ->groupBy('month')
    ->orderBy('month')
    ->pluck('total', 'month')
    ->toArray();

$statusCounts = Sales::select('status_bayar', DB::raw('count(*) as count'))
    ->groupBy('status_bayar')
    ->pluck('count', 'status_bayar')
    ->toArray();

return view('home', compact(
    'totalSales',
    'totalPaid',
    'totalDue',
    'todaySalesCount',
    'salesPerMonth',
     'topItems',
    'statusCounts'
));


    // return view('home', compact(
    //     'totalSales',
    //     'totalPaid',
    //     'totalDue',
    //     'todaySalesCount',
    //     'salesPerMonth',
    //     'topItems',
    //     'statusCounts'
    // ));
}

}
