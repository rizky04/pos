<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\PurchasePayment;
use App\Models\Supplier;
use Illuminate\Support\Facades\Auth;

class DashboardPurchaseController extends Controller
{

    public function index(){
        return view('dashboard.hutang');
    }
    public function hutang()
    {
        $tenantId = Auth::user()->tenant_id;

        // Total pembelian hutang (unpaid)
        $totalUnpaid = Purchase::where('tenant_id', $tenantId)
            ->where('status_pembelian', 'unpaid')
            ->sum('grand_total');

        // Total sudah dibayar
        $totalPaid = PurchasePayment::where('tenant_id', $tenantId)
            ->sum('amount');

        // Hutang tersisa
        $remainingDebt = $totalUnpaid - $totalPaid;
        if ($remainingDebt < 0) $remainingDebt = 0;

        return response()->json([
            'success'        => true,
            'total_unpaid'   => $totalUnpaid,
            'total_paid'     => $totalPaid,
            'remaining_debt' => $remainingDebt,
        ]);
    }



    /** CHART HUTANG PER SUPPLIER */
    public function hutangChart()
    {
        $tenantId = Auth::user()->tenant_id;

        $suppliers = Supplier::where('tenant_id', $tenantId)->get();

        $result = [];

        foreach ($suppliers as $s) {
            $totalPurchase = Purchase::where('tenant_id', $tenantId)
                ->where('supplier_id', $s->id)
                ->sum('grand_total');

            $totalPaid = PurchasePayment::where('tenant_id', $tenantId)
                ->whereHas('purchase', function($q) use ($s) {
                    $q->where('supplier_id', $s->id);
                })
                ->sum('amount');

            $result[] = [
                'supplier' => $s->nama_supplier,
                'hutang'   => max($totalPurchase - $totalPaid, 0)
            ];
        }

        return response()->json($result);
    }
}
