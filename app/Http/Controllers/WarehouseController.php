<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class WarehouseController extends Controller
{
    /**
     * Display listing page.
     */
    public function index()
    {
        return view('warehouses.index');
    }

    /**
     * Get data for DataTables / AJAX
     */
    public function getData(Request $request)
    {
        $query = Warehouse::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%$search%")
                    ->orWhere('name', 'like', "%$search%")
                    ->orWhere('city', 'like', "%$search%")
                    ->orWhere('pic', 'like', "%$search%");
            });
        }

        // Filter status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter type
        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        // Order
        $query->orderBy('created_at', 'desc');

        // Pagination
        $perPage = $request->get('per_page', 10);
        $warehouses = $query->paginate($perPage);

        return response()->json($warehouses);
    }

    /**
     * Store new warehouse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('warehouses')->where(fn($query) =>
                    $query->where('tenant_id', Auth::user()->tenant_id)
                )
            ],
            'name' => 'required|string|max:200',
            'type' => 'required|in:utama,outlet,produksi,lain',
            'city' => 'nullable|string|max:100',
            'address' => 'nullable|string',
            'pic' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:50',
            'status' => 'required|in:active,nonactive',
            'is_default' => 'boolean'
        ]);

        $validated['tenant_id'] = Auth::user()->tenant_id;

        try {
            $warehouse = Warehouse::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Warehouse berhasil ditambahkan',
                'data' => $warehouse
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan warehouse: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show detail
     */
    public function show($id)
    {
        $warehouse = Warehouse::findOrFail($id);
        return response()->json($warehouse);
    }

    /**
     * Update warehouse
     */
    public function update(Request $request, $id)
    {
        $warehouse = Warehouse::findOrFail($id);

        $validated = $request->validate([
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('warehouses')
                    ->where(fn($query) =>
                        $query->where('tenant_id', Auth::user()->tenant_id)
                    )
                    ->ignore($id)
            ],
            'name' => 'required|string|max:200',
            'type' => 'required|in:utama,outlet,produksi,lain',
            'city' => 'nullable|string|max:100',
            'address' => 'nullable|string',
            'pic' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:50',
            'status' => 'required|in:active,nonactive',
            'is_default' => 'boolean'
        ]);

        try {
            $warehouse->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Warehouse berhasil diperbarui',
                'data' => $warehouse
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui warehouse: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete warehouse
     */
    public function destroy($id)
    {
        try {
            $warehouse = Warehouse::findOrFail($id);
            $warehouse->delete();

            return response()->json([
                'success' => true,
                'message' => 'Warehouse berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus warehouse: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Auto generate warehouse code
     */
    public function generateCode()
    {
        $last = Warehouse::orderBy('code', 'desc')->first();

        if (!$last) {
            $newCode = 'WH-001';
        } else {
            $lastNumber = (int) substr($last->code, -3);
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
            $newCode = 'WH-' . $newNumber;
        }

        return response()->json([
            'success' => true,
            'code' => $newCode
        ]);
    }
}
