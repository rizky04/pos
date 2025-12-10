<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePurchaseRequest;
use App\Http\Requests\UpdatePurchaseRequest;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\PurchaseItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PurchaseController extends Controller
{

    public function index(){
        $product = Product::all();
        $supplier = Supplier::all();
        return view('purchase.index', compact('product', 'supplier'));
    }
    /**
     * GET DATA (List + Filter)
     * Dipakai untuk tabel jQuery
     */
    public function getData(Request $request)
    {
        $query = Purchase::with(['supplier', 'items'])
            ->where('tenant_id', Auth::user()->tenant_id)
            ->orderBy('tanggal', 'desc');

        // ===== Filtering =====
        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('invoice', 'like', "%$search%")
                  ->orWhereHas('supplier', function ($s) use ($search) {
                      $s->where('nama_supplier', 'like', "%$search%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status_pembelian', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->where('tanggal', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('tanggal', '<=', $request->date_to);
        }

        // Pagination
        $perPage = $request->per_page ?? 10;
        $data = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data'    => $data
        ]);
    }

    /**
     * STORE PEMBELIAN
     */
public function store(Request $request)
{

    $request->validate([
        'invoice' => 'required|string|unique:purchases,invoice',
        'supplier_id' => 'required|exists:suppliers,id',
        'date' => 'required|date',
        'status' => 'required|string',
        'method' => 'required|string',
        'items' => 'required|array|min:1',
        'items.*.product_id' => 'required|exists:products,id',
        'items.*.qty' => 'required|numeric|min:1',
        'items.*.price' => 'required|numeric|min:1',
        'items.*.discount_percent' => 'nullable|numeric|min:0|max:100',
        'ppnPercent' => 'nullable|numeric|min:0|max:20',
        'discount_transaction' => 'nullable|numeric|min:0',
    ]);

    DB::beginTransaction();
    try {

        $tenantId = Auth::user()->tenant_id;

        $subtotal = 0;

        foreach ($request->items as $item) {
            $line = $item['qty'] * $item['price'];

            $discVal = $line * ($item['discount_percent'] / 100);
            $line = $line - $discVal;

            $subtotal += $line;
        }

        $ppn = intval($request->ppnPercent ?? 11);
        $discountTransaction = intval($request->discount_transaction ?? 0);

        $subtotalAfterDiscount = max(0, $subtotal - $discountTransaction);

        $ppnValue = intval(($subtotalAfterDiscount * $ppn) / 100);
        $grandTotal = $subtotalAfterDiscount + $ppnValue;

        // dd($discountTransaction);

        $purchase = Purchase::create([
            'tenant_id' => $tenantId,
            'supplier_id' => $request->supplier_id,
            'kode' => $this->generateCodeOnly(), // custom method below
            'invoice' => $request->invoice,
            'tanggal' => $request->date,
            'jatuh_tempo' => $request->due_date,
            'status_pembelian' => $request->status,
            'metode_bayar' => $request->method,
            'catatan' => $request->note,
            'ppn_percent' => $ppn,
            'subtotal' => $subtotal,
            'discount_transaction' => $discountTransaction,
            'total_ppn' => $ppnValue,
            'grand_total' => $grandTotal,
        ]);

        foreach ($request->items as $item) {

            $line = $item['qty'] * $item['price'];
            $discVal = $line * ($item['discount_percent'] / 100);

            PurchaseItem::create([
                'tenant_id' => $tenantId,
                'purchase_id' => $purchase->id,
                'product_id' => $item['product_id'],
                'nama_barang' => Product::find($item['product_id'])->nama,
                'qty' => $item['qty'],
                'discount_percent' => $item['discount_percent'],
                'harga_beli' => $item['price'],
                'subtotal' => $line - $discVal,
            ]);

            // Update stock product
            Product::where('id', $item['product_id'])
                    ->increment('stok', $item['qty']);
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Pembelian berhasil disimpan',
            'data' => $purchase->load(['items', 'supplier'])
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
     * SHOW Pembelian (detail)
     */
    public function show($id)
    {
        $data = Purchase::with(['supplier', 'items.product'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => $data
        ]);
    }


    /**
     * UPDATE PEMBELIAN
     */
   public function update(Request $request, $id)
{


    $purchase = Purchase::where('tenant_id', Auth::user()->tenant_id)->findOrFail($id);

    $request->validate([
        'invoice' => "required|string|max:255|unique:purchases,invoice,{$purchase->id}",
        'supplier_id' => 'required|exists:suppliers,id',
        'date' => 'required|date',
        'items' => 'required|array|min:1',
        'items.*.product_id' => 'required|exists:products,id',
        'items.*.qty' => 'required|numeric|min:1',
        'items.*.price' => 'required|numeric|min:1',
        'items.*.discount_percent' => 'nullable|numeric|min:0|max:100',
        'ppnPercent' => 'nullable|numeric|min:0|max:30',
        'discountTransaction' => 'nullable|numeric|min:0',
    ]);

    DB::beginTransaction();
    try {

        $tenantId = Auth::user()->tenant_id;

        /** Restore stock old items */
        foreach ($purchase->items as $oldItem) {
            Product::where('id', $oldItem->product_id)
                ->decrement('stok', $oldItem->qty);
        }

        /** Delete old items */
        PurchaseItem::where('purchase_id', $purchase->id)->delete();

        $subtotal = 0;

        foreach ($request->items as $item) {

            $line = $item['qty'] * $item['price'];
            $discVal = $line * ($item['discount_percent'] / 100);
            $line = $line - $discVal;

            $subtotal += $line;

            PurchaseItem::create([
                'tenant_id' => $tenantId,
                'purchase_id' => $purchase->id,
                'product_id' => $item['product_id'],
                'nama_barang' => Product::find($item['product_id'])->nama,
                'qty' => $item['qty'],
                'discount_percent' => $item['discount_percent'],
                'harga_beli' => $item['price'],
                'subtotal' => $line,
            ]);

            Product::where('id', $item['product_id'])
                    ->increment('stok', $item['qty']);
        }

        $ppn = intval($request->ppnPercent ?? 11);
        $discountTransaction = intval($request->discount_transaction ?? 0);

        $subtotalAfterDiscount = max(0, $subtotal - $discountTransaction);

        $ppnValue = intval(($subtotalAfterDiscount * $ppn) / 100);
        $grandTotal = $subtotalAfterDiscount + $ppnValue;

        $purchase->update([
            'supplier_id' => $request->supplier_id,
            'invoice' => $request->invoice,
            'tanggal' => $request->date,
            'jatuh_tempo' => $request->due_date,
            'status_pembelian' => $request->status,
            'metode_bayar' => $request->method,
            'catatan' => $request->note,
            'discount_transaction' => $discountTransaction,
            'ppn_percent' => $ppn,
            'subtotal' => $subtotal,
            'total_ppn' => $ppnValue,
            'grand_total' => $grandTotal,
        ]);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Pembelian berhasil diperbarui',
            'data' => $purchase->load(['items', 'supplier'])
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
     * DELETE Pembelian
     */
    public function destroy($id)
    {
        $pembelian = Purchase::where('tenant_id', Auth::user()->tenant_id)->findOrFail($id);

        DB::beginTransaction();
        try {

             /** Restore stock old items */
            foreach ($pembelian->items as $oldItem) {
                Product::where('id', $oldItem->product_id)
                    ->decrement('stok', $oldItem->qty);
            }
            $pembelian->items()->delete();
            $pembelian->delete();


            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Pembelian berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function generateCodeOnly()
{
    $last = Purchase::where('tenant_id', Auth::user()->tenant_id)
        ->orderBy('kode', 'desc')->first();

    if (!$last) {
        return "PURC-001";
    }

    $no = (int) substr($last->kode, -3);
    return "PURC-" . str_pad($no + 1, 3, "0", STR_PAD_LEFT);
}

public function print($id)
{
    $data = Purchase::with(['supplier', 'items.product'])
        ->where('tenant_id', Auth::user()->tenant_id)
        ->findOrFail($id);

    return view('purchase.print', compact('data'));
}


}
