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
        'kode' => [
            'required',
            Rule::unique('purchases')->where(function ($query) {
                return $query->where('tenant_id', Auth::user()->tenant_id)
                             ->whereNull('deleted_at');
            })
        ],
        'invoice' => 'required|string|unique:purchases,invoice',
        'supplier' => 'required|string',
        'date' => 'required|date',
        'metode' => 'required|string',
        'status' => 'required|string',
        'ppnPercent' => 'required|numeric|min:0|max:20',
        'items' => 'required|array|min:1',
        'items.*.name' => 'required|string',
        'items.*.qty' => 'required|numeric|min:1',
        'items.*.price' => 'required|numeric|min:1',
    ]);

    DB::beginTransaction();
    try {
        $tenantId = Auth::user()->tenant_id;

        /**
         * Supplier handling: frontend hanya menyimpan nama supplier
         * kita buat supplier otomatis kalau belum ada
         */
        $supplier = Supplier::firstOrCreate(
            ['tenant_id' => $tenantId, 'nama' => $request->supplier],
            ['alamat' => null]
        );

        /** HITUNG SUBTOTAL */
        $subtotal = 0;
        foreach ($request->items as $item) {
            $subtotal += $item['qty'] * $item['price'];
        }

        $ppnPercent = $request->ppnPercent;
        $totalPpn = intval(($subtotal * $ppnPercent) / 100);
        $grandTotal = $subtotal + $totalPpn;

        /** SIMPAN PURCHASE */
        $purchase = Purchase::create([
            'tenant_id' => $tenantId,
            'supplier_id' => $supplier->id,
            'kode' => $this->generateCode(),
            'invoice' => $request->invoice,
            'tanggal' => $request->date,
            'jatuh_tempo' => $request->due_date,
            'status_pembelian' => $request->status,
            'metode_bayar' => $request->metode,
            'catatan' => $request->note,
            'ppn_percent' => $ppnPercent,
            'subtotal' => $subtotal,
            'total_ppn' => $totalPpn,
            'grand_total' => $grandTotal,
        ]);

        /** ITEMS */
        foreach ($request->items as $i) {
            PurchaseItem::create([
                'tenant_id' => $tenantId,
                'purchase_id' => $purchase->id,
                'product_id' => null, // Frontend tidak pakai product ID
                'nama_barang' => $i['name'],
                'qty' => $i['qty'],
                'harga_beli' => $i['price'],
                'subtotal' => $i['qty'] * $i['price']
            ]);
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Pembelian berhasil disimpan!',
            'data' => $purchase->load('items')
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => 'Gagal menyimpan pembelian: ' . $e->getMessage()
        ], 500);
    }
}



    /**
     * SHOW Pembelian (detail)
     */
    public function show($id)
    {
        $data = Purchase::with(['supplier', 'items'])
            ->where('tenant_id', Auth::user()->tenant_id)
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
        $pembelian = Purchase::where('tenant_id', Auth::user()->tenant_id)->findOrFail($id);

        $request->validate([
            'invoice' => "required|string|max:255|unique:pembelians,invoice,{$pembelian->id}",
            'tanggal' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.nama_barang' => 'required',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.harga_beli' => 'required|integer|min:0',
        ]);

        DB::beginTransaction();

        try {
            $subtotal = collect($request->items)->sum(fn($i) => $i['qty'] * $i['harga_beli']);
            $ppnPercent = intval($request->ppn_percent ?? 11);
            $totalPpn = $subtotal * ($ppnPercent / 100);
            $grandTotal = $subtotal + $totalPpn;

            // Update pembelian
            $pembelian->update([
                'id_supplier' => $request->id_supplier,
                'invoice' => $request->invoice,
                'tanggal' => $request->tanggal,
                'jatuh_tempo' => $request->jatuh_tempo,
                'status_pembelian' => $request->status_pembelian,
                'metode_bayar' => $request->metode_bayar,
                'catatan' => $request->catatan,
                'ppn_percent' => $ppnPercent,
                'subtotal' => $subtotal,
                'total_ppn' => $totalPpn,
                'grand_total' => $grandTotal,
            ]);

            // Hapus semua item lama
            PurchaseItem::where('pembelian_id', $pembelian->id)->delete();

            // Tambahkan item baru
            foreach ($request->items as $item) {
                PurchaseItem::create([
                    'pembelian_id' => $pembelian->id,
                    'product_id' => $item['product_id'] ?? null,
                    'nama_barang' => $item['nama_barang'],
                    'qty' => $item['qty'],
                    'harga_beli' => $item['harga_beli'],
                    'subtotal' => $item['qty'] * $item['harga_beli'],
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data pembelian berhasil diperbarui!',
                'data' => $pembelian->load('items')
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

    public function generateCode()
    {
        $last = Purchase::where('tenant_id', Auth::user()->tenant_id)
            ->orderBy('kode', 'desc')
            ->first();

        if (!$last) {
            $newCode = 'PURC-001';
        } else {
            $lastNumber = (int) substr($last->kode, -3);
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
            $newCode = 'PURC-' . $newNumber;
        }

        return response()->json([
            'success' => true,
            'code' => $newCode
        ]);
    }
}
