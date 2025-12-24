<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesReportController extends Controller
{

    public function index(){
        return view('report.sales.index');
    }
    /**
     * =========================================
     * 1️⃣ LAPORAN PENJUALAN (LIST / TABLE)
     * =========================================
     */
    public function list(Request $request)
    {
        $query = Transaction::with(['customer', 'items.product'])
            ->where('status', '!=', 'void')
            ->orderBy('created_at', 'desc');

        // Filter tanggal
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        $data = $query->paginate(10);

        $data->getCollection()->transform(function ($trx) {
            $totalPaid = $trx->pay_amount ?? 0;
            $total = $trx->total_after_ppn;

            return [
                'id'           => $trx->id,
                'sales_date'   => $trx->created_at->format('d-m-Y'),
                'nomor_sales'  => $trx->kode,
                'client'       => $trx->customer,
                'status_bayar' => $trx->status,
                'total'        => $total,
                'total_paid'   => $totalPaid,
                'due_amount'   => max($total - $totalPaid, 0),
                'note'         => '-',
                'items'        => $trx->items->map(function ($i) {
                    return [
                        'barang'   => $i->product,
                        'qty'      => $i->qty,
                        'price'    => $i->price,
                        'subtotal' => $i->total
                    ];
                })
            ];
        });

        return response()->json($data);
    }

    /**
     * =========================================
     * 2️⃣ LAPORAN PENJUALAN PER KASIR
     * =========================================
     */
    public function perKasir(Request $request)
    {
        $query = Transaction::select(
                'user_id',
                DB::raw('COUNT(*) as total_transaksi'),
                DB::raw('SUM(total_after_ppn) as total_penjualan')
            )
            ->where('status', '!=', 'void')
            ->groupBy('user_id')
            ->with('user');

        // Filter tanggal
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        return response()->json($query->get());
    }

    /**
     * =========================================
     * 3️⃣ LABA KOTOR
     * =========================================
     */
    public function labaKotor(Request $request)
    {
        $items = TransactionItem::query();

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $items->whereHas('transaction', function ($q) use ($request) {
                $q->whereBetween('created_at', [
                    $request->start_date . ' 00:00:00',
                    $request->end_date . ' 23:59:59'
                ]);
            });
        }

        $labaKotor = $items->sum(DB::raw('(price - purchase_price) * qty'));

        return response()->json([
            'laba_kotor' => $labaKotor
        ]);
    }

    /**
     * =========================================
     * 4️⃣ LABA BERSIH
     * =========================================
     */
    public function labaBersih(Request $request)
    {
        $trx = Transaction::where('status', '!=', 'void');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $trx->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        $totalPenjualan = $trx->sum('total_after_ppn');
        $totalDiskon    = $trx->sum('discount_value');
        $totalPpn       = $trx->sum('ppn');

        $hpp = TransactionItem::whereIn(
            'transaction_id',
            $trx->pluck('id')
        )->sum(DB::raw('purchase_price * qty'));



        $labaKotor  = $totalPenjualan - $hpp;
        $labaBersih = $labaKotor - $totalDiskon - $totalPpn;

        return response()->json([
            'total_penjualan' => $totalPenjualan,
            'hpp'             => $hpp,
            'laba_kotor'      => $labaKotor,
            'diskon'          => $totalDiskon,
            'ppn'             => $totalPpn,
            'laba_bersih'     => $labaBersih
        ]);
    }
}
