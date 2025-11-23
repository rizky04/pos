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

    public function list(){
        return view('transactions.list');
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
        // dd($request->all());
        $request->validate([
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
        ]);

        // buat nomor transaksi
        $kode = 'TRX-' . date('YmdHis');

        $trx = Transaction::create([
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
            'status' => 'paid',
        ]);

        // SIMPAN ITEM
        foreach ($request->items as $item) {

            TransactionItem::create([
                'transaction_id' => $trx->id,
                'product_id' => $item['product_id'],
                'qty' => $item['qty'],
                'price' => $item['price'],
                'total' => $item['qty'] * $item['price'],
            ]);

            // KURANGI STOK PRODUK
            Product::where('id', $item['product_id'])->decrement('stok', $item['qty']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil disimpan',
            'data' => $trx
        ]);
    }

    /**
     * Display the specified resource.
     */
//    public function getData(Request $request)
//     {
//         $query = Transaction::with(['items.product']);

//         // --- FILTER SEARCH ---
//         if ($request->search != '') {
//             $search = $request->search;

//             $query->where(function ($q) use ($search) {
//                 $q->where('kode', 'like', "%$search%")
//                   ->orWhere('status', 'like', "%$search%");
//             });
//         }

//         // --- FILTER STATUS ---
//         if ($request->filled('status') && $request->status !== 'all') {
//             $query->where('status', $request->status);
//         }

//         // --- FILTER RANGE TANGGAL ---
//         if ($request->filled('date_from') && $request->filled('date_to')) {
//             $query->whereBetween('created_at', [
//                 $request->date_from . " 00:00:00",
//                 $request->date_to . " 23:59:59"
//             ]);
//         }

//         // --- ORDER ---
//         $query->orderBy('created_at', 'desc');

//         // --- PAGINATION ---
//         $perPage = $request->get('per_page', 10);

//         $transactions = $query->paginate($perPage);

//         return response()->json($transactions);
//     }
 public function getData(Request $request)
    {
        $query = Transaction::with('items')
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
        $transaction = Transaction::with(['items.product'])
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

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTransactionRequest $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        //
    }
}
