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
    public function show(Transaction $transaction)
    {
        //
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
