<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('suppliers.index');
    }

    /**
     * Get data for DataTables / AJAX
     */
    public function getData(Request $request)
    {
        $query = Supplier::query();



        // Filter search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_supplier', 'like', '%' . $search . '%')
                    ->orWhere('nama_supplier', 'like', '%' . $search . '%')
                    ->orWhere('contact_person', 'like', '%' . $search . '%')
                    ->orWhere('telepon', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        // // Filter status
        // if ($request->has('status') && $request->status != '') {
        //     $query->where('status', $request->status);
        // }

        // // Filter termin
        // if ($request->has('termin') && $request->termin !== '') {
        //     $query->where('termin_pembayaran', $request->termin);
        // }

        // Filter status
if ($request->filled('status') && $request->status !== 'all') {
    $query->where('status', $request->status);
}

// Filter termin
if ($request->filled('termin') && $request->termin !== 'all') {
    $query->where('termin_pembayaran', $request->termin);
}

        // Order by
        $query->orderBy('created_at', 'desc');

        // Pagination
        $perPage = $request->get('per_page', 10);

        // $suppliers = Supplier::paginate($perPage);

        $suppliers = $query->paginate($perPage);

        return response()->json($suppliers);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('suppliers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_supplier' => [
                'required',
                'string',
                'max:50',
                Rule::unique('suppliers')->where(function ($q) {
                    return $q->where('tenant_id', Auth::user()->tenant_id)
                             ->whereNull('deleted_at');
                })
            ],
            'nama_supplier' => 'required|string|max:200',
            'contact_person' => 'nullable|string|max:100',
            'telepon' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:100',
            'alamat' => 'nullable|string',
            'kota' => 'nullable|string|max:100',
            'termin_pembayaran' => 'nullable|integer|min:0',
            'npwp' => 'nullable|string|max:50',
            'status' => 'required|in:active,nonactive',
        ]);

        try {
            $supplier = Supplier::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Supplier berhasil ditambahkan',
                'data' => $supplier
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan supplier: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $supplier = Supplier::findOrFail($id);
        return response()->json($supplier);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('suppliers.edit', compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);

        $validated = $request->validate([
            'kode_supplier' => [
                'required',
                'string',
                'max:50',
                Rule::unique('suppliers')->where(function ($q) {
                    return $q->where('tenant_id', Auth::user()->tenant_id)
                             ->whereNull('deleted_at');
                })
            ],
            'nama_supplier' => 'required|string|max:200',
            'contact_person' => 'nullable|string|max:100',
            'telepon' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:100',
            'alamat' => 'nullable|string',
            'kota' => 'nullable|string|max:100',
            'termin_pembayaran' => 'nullable|integer|min:0',
            'npwp' => 'nullable|string|max:50',
            'status' => 'required|in:active,nonactive',
        ]);

        try {
            $supplier->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Supplier berhasil diperbarui',
                'data' => $supplier
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui supplier: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $supplier = Supplier::findOrFail($id);

            // Check if supplier has related transactions (optional)
            // if ($supplier->pembelians()->count() > 0) {
            //     return response()->json([
            //         'success' => false,
            //         'message' => 'Supplier tidak dapat dihapus karena memiliki transaksi pembelian'
            //     ], 400);
            // }

            $supplier->delete();

            return response()->json([
                'success' => true,
                'message' => 'Supplier berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus supplier: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export suppliers to CSV/Excel
     */
    public function export(Request $request)
    {
        // Implement export logic here
        // You can use Laravel Excel package
        return response()->json([
            'success' => false,
            'message' => 'Export feature not implemented yet'
        ]);
    }

    /**
     * Import suppliers from CSV/Excel
     */
    public function import(Request $request)
    {
        // Implement import logic here
        // You can use Laravel Excel package
        return response()->json([
            'success' => false,
            'message' => 'Import feature not implemented yet'
        ]);
    }

    /**
     * Generate auto supplier code
     */
    public function generateCode()
    {
        $lastSupplier = Supplier::orderBy('kode_supplier', 'desc')->first();

        if (!$lastSupplier) {
            $newCode = 'SUP-001';
        } else {
            // Extract number from last code
            $lastCode = $lastSupplier->kode_supplier;
            $lastNumber = (int) substr($lastCode, -3);
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
            $newCode = 'SUP-' . $newNumber;
        }

        return response()->json([
            'success' => true,
            'code' => $newCode
        ]);
    }
}
