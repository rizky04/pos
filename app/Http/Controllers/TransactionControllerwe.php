<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\Product;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
 use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
         $categories = Category::active()->orderBy('nama')->get();

    $products = Product::with('category')
        ->active()
        ->orderBy('nama')
        ->get();

        return view('transactions.pos', compact('categories', 'products'));
    }

    // public function list(){
    //     $products = Product::with('category')
    // ->orderBy('nama')
    // ->get(); // ❌ HAPUS active()

    //     return view('transactions.list', compact('products'));
    // }
    public function list()
{
    $products = Product::where('tenant_id', Auth::user()->tenant_id)
        ->orderBy('nama')
        ->get();

    return view('transactions.list', compact('products'));
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'nullable',
            'items' => 'required|array',
            'items.*.product_id' => 'required',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.price' => 'required',
            'sub_total' => 'required',
            'discount_value' => 'required',
            'discount_type' => 'required',
            'total_after_discount' => 'required',
            'ppn' => 'required',
            'total_after_ppn' => 'required',
            'pay_amount' => 'required',
            'change_amount' => 'required',
            'status' => 'required',
            'payment_method' => 'required',
            'note' => 'nullable',
            // 'date_transaction' => 'required',
        ]);

        // buat nomor transaksi
        $kode = 'TRX-' . date('YmdHis');

        $trx = Transaction::create([
            'customer_id' => $request->customer_id,
            'tenant_id' => Auth::user()->tenant_id,
            'user_id' => Auth::id(),
            'kode' => $kode,

            'sub_total' => $request->sub_total,
            'discount_value' => $request->discount_value,
            'discount_type' => $request->discount_type,
            'total_after_discount' => $request->total_after_discount,
            'ppn' => $request->ppn,
            'total_after_ppn' => $request->total_after_ppn,
            'pay_amount' => $request->pay_amount,
            'change_amount' => $request->change_amount,
            'status' => $request->status,
            'date_transaction' => now(),
            'payment_method' => $request->payment_method,
            'note' => $request->note
        ]);

        // SIMPAN ITEM
        foreach ($request->items as $item) {


          $product = Product::where('id', $item['product_id'])->firstOrFail();

          $dataku = TransactionItem::create([
                'transaction_id' => $trx->id,
                'product_id' => $item['product_id'],
                'qty' => $item['qty'],
                'price' => $item['price'],
                'purchase_price' => $product->harga_modal ?? 0,
                'total' => $item['qty'] * $item['price'],
            ]);

            // dd($dataku);

            // KURANGI STOK PRODUK
            Product::where('id', $item['product_id'])->decrement('stok', $item['qty']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil disimpan',
            'data' => $trx
        ]);
    }


 public function getData(Request $request)
    {
        $query = Transaction::with('items', 'customer')
            ->where('tenant_id', Auth::user()->tenant_id);

        // Search (kode invoice / customer)
        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode', 'like', "%$search%");
            });
        }

        // Filter status
        if ($request->status && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter date
        if ($request->date_from && $request->date_to) {
            $query->whereBetween('created_at', [
                $request->date_from . " 00:00:00",
                $request->date_to . " 23:59:59"
            ]);
        }

        $data = $query->orderBy('created_at', 'desc')->paginate(10);

        return response()->json($data);
    }

    /**
     * Detail transaksi untuk modal
     */
    public function show($id)
    {
        $transaction = Transaction::with(['items.product', 'customer'])
            ->where('tenant_id', Auth::user()->tenant_id)
            ->findOrFail($id);

        return response()->json($transaction);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        //
    }


// public function update(Request $request, $id)
// {


//     $request->validate([
//         'customer_id' => 'nullable',
//         'items' => 'required|array',
//         'items.*.product_id' => 'required',
//         'items.*.qty' => 'required|integer|min:1',
//         'items.*.price' => 'required',
//         'sub_total' => 'required',
//         'discount_value' => 'required',
//         'discount_type' => 'required',
//         'total_after_discount' => 'required',
//         'ppn' => 'required',
//         'total_after_ppn' => 'required',
//         'pay_amount' => 'required',
//         'change_amount' => 'required',
//         'status' => 'required',
//         'payment_method' => 'required',
//         'note' => 'nullable',
//     ]);

//     DB::beginTransaction();

//     try {

//         // 🔎 Ambil transaksi (tenant-safe)
//         $transaction = Transaction::with('items')
//             ->where('tenant_id', Auth::user()->tenant_id)
//             ->findOrFail($id);

//         /**
//          * ==================================================
//          * 1️⃣ KEMBALIKAN STOK DARI ITEM LAMA
//          * ==================================================
//          */
//         foreach ($transaction->items as $oldItem) {
//             Product::where('id', $oldItem->product_id)
//                 ->increment('stok', $oldItem->qty);
//         }

//         /**
//          * ==================================================
//          * 2️⃣ HAPUS ITEM LAMA
//          * ==================================================
//          */
//         $transaction->items()->delete();


//         /**
//          * ==================================================
//          * 3️⃣ UPDATE HEADER TRANSAKSI
//          * ==================================================
//          */
//         $transaction->update([
//             'customer_id' => $request->customer_id,
//             'sub_total' => $request->sub_total,
//             'discount_value' => $request->discount_value,
//             'discount_type' => $request->discount_type,
//             'total_after_discount' => $request->total_after_discount,
//             'ppn' => $request->ppn,
//             'total_after_ppn' => $request->total_after_ppn,
//             'pay_amount' => $request->pay_amount,
//             'change_amount' => $request->change_amount,
//             'status' => $request->status,
//             'payment_method' => $request->payment_method,
//             'note' => $request->note
//         ]);

//         /**
//          * ==================================================
//          * 4️⃣ SIMPAN ITEM BARU & KURANGI STOK
//          * ==================================================
//          */
//         foreach ($request->items as $item) {

//             $product = Product::where('id', $item['product_id'])
//                 ->where('tenant_id', Auth::user()->tenant_id)
//                 ->firstOrFail();

//             TransactionItem::create([
//                 'tenant_id' => Auth::user()->tenant_id,
//                 'transaction_id' => $transaction->id,
//                 'product_id' => $item['product_id'],
//                 'qty' => $item['qty'],
//                 'price' => $item['price'],
//                 'purchase_price' => $product->harga_modal ?? 0,
//                 'total' => $item['qty'] * $item['price'],
//             ]);

//             // Kurangi stok baru
//             $product->decrement('stok', $item['qty']);
//         }

//         DB::commit();

//         return response()->json([
//             'success' => true,
//             'message' => 'Transaksi berhasil diperbarui',
//             'data' => $transaction->load('items.product')
//         ]);

//     } catch (\Exception $e) {
//         DB::rollBack();

//         return response()->json([
//             'success' => false,
//             'message' => 'Gagal update transaksi',
//             'error' => $e->getMessage()
//         ], 500);
//     }
// }
public function update(Request $request, $id)
{
    $request->validate([
        'customer_id' => 'nullable',
        'items' => 'required|array',
        'items.*.product_id' => 'required',
        'items.*.qty' => 'required|integer|min:1',
        'items.*.price' => 'required',
        'sub_total' => 'required',
        'discount_value' => 'required',
        'discount_type' => 'required',
        'total_after_discount' => 'required',
        'ppn' => 'required',
        'total_after_ppn' => 'required',
        'pay_amount' => 'required',
        'change_amount' => 'required',
        'status' => 'required',
        'payment_method' => 'required',
        'note' => 'nullable',
    ]);

    DB::beginTransaction();

    try {
        // 🔎 Ambil transaksi dengan items
        $transaction = Transaction::with('items')
            ->where('tenant_id', Auth::user()->tenant_id)
            ->findOrFail($id);

        Log::info('=== UPDATE TRANSACTION START ===');
        Log::info('Transaction ID: ' . $id);
        Log::info('Old items count: ' . $transaction->items->count());

        /**
         * ==================================================
         * 1️⃣ KEMBALIKAN STOK DARI ITEM LAMA
         * ==================================================
         */
        foreach ($transaction->items as $oldItem) {
            Log::info('Returning stock: Product ' . $oldItem->product_id . ' +' . $oldItem->qty);

            Product::where('id', $oldItem->product_id)
                ->where('tenant_id', Auth::user()->tenant_id)
                ->increment('stok', $oldItem->qty);
        }

        /**
         * ==================================================
         * 2️⃣ HAPUS ITEM LAMA (HARD DELETE untuk memastikan)
         * ==================================================
         */
        // Hapus dengan forceDelete untuk memastikan benar-benar terhapus
        foreach ($transaction->items as $oldItem) {
            $oldItem->forceDelete();
        }

        // Double check dengan query langsung
        TransactionItem::where('transaction_id', $id)->forceDelete();

        Log::info('All old items deleted');

        /**
         * ==================================================
         * 3️⃣ UPDATE HEADER TRANSAKSI
         * ==================================================
         */
        $transaction->update([
            'customer_id' => $request->customer_id,
            'sub_total' => $request->sub_total,
            'discount_value' => $request->discount_value,
            'discount_type' => $request->discount_type,
            'total_after_discount' => $request->total_after_discount,
            'ppn' => $request->ppn,
            'total_after_ppn' => $request->total_after_ppn,
            'pay_amount' => $request->pay_amount,
            'change_amount' => $request->change_amount,
            'status' => $request->status,
            'payment_method' => $request->payment_method,
            'note' => $request->note
        ]);

        Log::info('Transaction header updated');

        /**
         * ==================================================
         * 4️⃣ SIMPAN ITEM BARU & KURANGI STOK
         * ==================================================
         */
        Log::info('New items count: ' . count($request->items));

        foreach ($request->items as $item) {
            $product = Product::where('id', $item['product_id'])
                ->where('tenant_id', Auth::user()->tenant_id)
                ->firstOrFail();

            Log::info('Creating new item: Product ' . $item['product_id'] . ' x' . $item['qty']);

            TransactionItem::create([
                'tenant_id' => Auth::user()->tenant_id,
                'transaction_id' => $transaction->id,
                'product_id' => $item['product_id'],
                'qty' => $item['qty'],
                'price' => $item['price'],
                'purchase_price' => $product->harga_modal ?? 0,
                'total' => $item['qty'] * $item['price'],
            ]);

            // Kurangi stok baru
            Log::info('Reducing stock: Product ' . $item['product_id'] . ' -' . $item['qty']);
            $product->decrement('stok', $item['qty']);
        }

        DB::commit();

        Log::info('=== UPDATE TRANSACTION SUCCESS ===');

        // Reload data untuk response
        $transaction->load('items.product', 'customer');

        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil diperbarui',
            'data' => $transaction
        ]);

    } catch (\Exception $e) {
        DB::rollBack();

        Log::error('=== UPDATE TRANSACTION FAILED ===');
        Log::error('Error: ' . $e->getMessage());
        Log::error('Trace: ' . $e->getTraceAsString());

        return response()->json([
            'success' => false,
            'message' => 'Gagal update transaksi',
            'error' => $e->getMessage()
        ], 500);
    }
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        //
    }
}
