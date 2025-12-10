<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\PurchasePayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchasePaymentController extends Controller
{
    /**
     * Get all payments for a purchase
     */
    public function history($purchaseId)
    {
        $payments = PurchasePayment::where('purchase_id', $purchaseId)
            ->where('tenant_id', Auth::user()->tenant_id)
            ->orderBy('payment_date', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $payments
        ]);
    }


    /**
     * Store payment
     */
    public function store(Request $request)
    {
        $request->validate([
            'purchase_id'     => 'required|exists:purchases,id',
            'payment_date'    => 'required|date',
            'payment_method'  => 'required|string',
            'amount'          => 'required|numeric|min:1',
            'reference'       => 'nullable|string|max:255',
            'note'            => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            $tenantId = Auth::user()->tenant_id;

            $purchase = Purchase::where('tenant_id', $tenantId)
                ->findOrFail($request->purchase_id);

            // Hitung sisa hutang sebelum bayar
            $existingPayments = PurchasePayment::where('purchase_id', $purchase->id)
                ->sum('amount');

            $currentDebt = $purchase->grand_total - $existingPayments;

            if ($request->amount > $currentDebt) {
                return response()->json([
                    'success' => false,
                    'message' => "Nominal pembayaran melebihi sisa hutang!"
                ], 422);
            }

            $remaining = $currentDebt - $request->amount;

            // Buat history pembayaran
            $payment = PurchasePayment::create([
                'tenant_id'        => $tenantId,
                'purchase_id'      => $purchase->id,
                'payment_date'     => $request->payment_date,
                'payment_method'   => $request->payment_method,
                'reference'        => $request->reference,
                'note'             => $request->note,
                'amount'           => $request->amount,
                'remaining_amount' => $remaining,
            ]);


            // Update status pembelian jika lunas
            if ($remaining <= 0) {
                $purchase->update([
                    'status_pembelian' => 'paid'
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil ditambahkan',
                'data' => [
                    'payment' => $payment,
                    'purchase' => $purchase->refresh()
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Delete payment (rollback debt)
     */
    public function destroy($id)
    {
        $payment = PurchasePayment::where('tenant_id', Auth::user()->tenant_id)
            ->findOrFail($id);

        $purchase = Purchase::findOrFail($payment->purchase_id);

        DB::beginTransaction();

        try {
            $payment->delete();

            // Recalculate debt
            $totalPaid = PurchasePayment::where('purchase_id', $purchase->id)->sum('amount');

            if ($totalPaid >= $purchase->grand_total) {
                $purchase->update(['status_pembalian' => 'paid']);
            } else {
                $purchase->update(['status_pembalian' => 'unpaid']);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

  public function printBukti($id)
{
    $payment = PurchasePayment::with(['purchase.supplier'])->findOrFail($id);

    $purchase = $payment->purchase;

    $totalPaid = $purchase->payments()->sum('amount');
    $remaining = $purchase->grand_total - $totalPaid;

    return view('laporan.print', compact(
        'payment',
        'purchase',
        'totalPaid',
        'remaining'
    ));
}


}
