<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\PurchasePayment;
use App\Models\Supplier;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


use function Symfony\Component\Clock\now;

class LaporanPurchaseController extends Controller
{

    public function hutang(Request $request)
    {
        $tenantId = Auth::user()->tenant_id;

        $supplierId = $request->supplier_id;
        $from = $request->from_date;
        $to = $request->to_date;

        $suppliers = Supplier::where('tenant_id', $tenantId)->get();
        $result = [];

        foreach ($suppliers as $s) {

            if ($supplierId && $supplierId != $s->id) continue;

            // FILTER PEMBELIAN
            $purchaseQuery = Purchase::where('tenant_id', $tenantId)
                ->where('supplier_id', $s->id);

            if ($from) $purchaseQuery->whereDate('tanggal', '>=', $from);
            if ($to) $purchaseQuery->whereDate('tanggal', '<=', $to);

            $totalPurchase = $purchaseQuery->sum('grand_total');

            // FILTER PEMBAYARAN
            $paymentQuery = PurchasePayment::where('tenant_id', $tenantId)
                ->whereHas('purchase', function ($q) use ($s) {
                    $q->where('supplier_id', $s->id);
                });

            if ($from) $paymentQuery->whereDate('payment_date', '>=', $from);
            if ($to) $paymentQuery->whereDate('payment_date', '<=', $to);

            $totalPaid = $paymentQuery->sum('amount');

            $result[] = [
                'supplier_id'     => $s->id,
                'supplier'        => $s->nama_supplier,
                'total_purchase'  => $totalPurchase,
                'total_paid'      => $totalPaid,
                'remaining'       => max($totalPurchase - $totalPaid, 0)
            ];
        }

        return view('laporan.hutang', compact('result', 'suppliers'));
    }

 public function hutangDashboard()
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

    public function detailSupplier($id, Request $request)
{
    $tenantId = Auth::user()->tenant_id;

    $from = $request->from;
    $to = $request->to;

    $supplier = Supplier::where('tenant_id', $tenantId)->findOrFail($id);

    $query = Purchase::with(['payments'])
        ->where('tenant_id', $tenantId)
        ->where('supplier_id', $id);

    if ($from) $query->where('tanggal', '>=', $from);
    if ($to) $query->where('tanggal', '<=', $to);

    $invoices = $query->get()->map(function ($p) {
        $paid = $p->payments->sum('amount');
        return [
            'id'        => $p->id,
            'tanggal'   => $p->tanggal,
            'invoice'   => $p->invoice,
            'total'     => $p->grand_total,
            'paid'      => $paid,
            'remaining' => max($p->grand_total - $paid, 0),
        ];
    });

    return view('laporan.detail_supplier', compact('supplier', 'invoices'));
}


public function detailSupplierAjax($id, Request $request)
{
    $tenantId = Auth::user()->tenant_id;

    $supplier = Supplier::where('tenant_id', $tenantId)->findOrFail($id);

    $query = Purchase::with('payments')
        ->where('tenant_id', $tenantId)
        ->where('supplier_id', $id);

    if ($request->from) $query->whereDate('tanggal', '>=', $request->from);
    if ($request->to) $query->whereDate('tanggal', '<=', $request->to);

    $invoices = $query->get()->map(function ($p) {
        $paid = $p->payments->sum('amount');
        return [
            'id'        => $p->id,
            'tanggal'   => $p->tanggal,
            'invoice'   => $p->invoice,
            'total'     => $p->grand_total,
            'paid'      => $paid,
            'remaining' => max($p->grand_total - $paid, 0),

            // WAJIB DITAMBAHKAN
        'payments'  => $p->payments->map(function ($pay) {
            return [
                'id'     => $pay->id,
                'date'   => $pay->payment_date,
                'amount' => $pay->amount,
            ];
        }),
        ];
    });

    return response()->json([
        'supplier' => $supplier,
        'invoices' => $invoices
    ]);
}



}

