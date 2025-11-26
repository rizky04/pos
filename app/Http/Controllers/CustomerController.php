<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    /**
     * Display index page.
     */
    public function index()
    {
        return view('customers.index');
    }

    /**
     * Get data for DataTables / AJAX.
     */
    public function getData(Request $request)
    {
        $query = Customer::query();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode', 'like', "%$search%")
                    ->orWhere('nama', 'like', "%$search%")
                    ->orWhere('telepon', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('kota', 'like', "%$search%");
            });
        }

        // Filter status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter member (yes/no)
        if ($request->filled('member') && $request->member !== 'all') {
            $query->where('member', $request->member);
        }

        // Order
        $query->orderBy('created_at', 'desc');

        // Pagination
        $perPage = $request->get('per_page', 10);
        $data = $query->paginate($perPage);

        return response()->json($data);
    }

    /**
     * Create page.
     */
    public function create()
    {
        return view('customers.create');
    }

    /**
     * Store new record.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => [
                'required',
                'string',
                'max:50',
                Rule::unique('customers')->where(function ($q) {
                    return $q->where('tenant_id', Auth::user()->tenant_id)
                             ->whereNull('deleted_at');
                })
            ],
            'nama' => 'required|string|max:200',
            'telepon' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:100',
            'tipe' => 'nullable|string|max:50',
            'member' => 'nullable|in:none,silver,gold,platinum',
            'alamat' => 'nullable|string',
            'kota' => 'nullable|string|max:100',
            'limit' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,nonactive',
            'catatan' => 'nullable|string',
        ]);

        $validated['tenant_id'] = Auth::user()->tenant_id;

        try {
            $customer = Customer::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Customer berhasil ditambahkan',
                'data' => $customer
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan customer: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show one record (JSON)
     */
    public function show($id)
    {
        $data = Customer::findOrFail($id);
        return response()->json($data);
    }

    /**
     * Show edit form.
     */
    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        return view('customers.edit', compact('customer'));
    }

    /**
     * Update record.
     */
    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        $validated = $request->validate([
            'kode' => [
                'required',
                'string',
                'max:50',
                Rule::unique('customers')->where(function ($q) {
                    return $q->where('tenant_id', Auth::user()->tenant_id)
                             ->whereNull('deleted_at');
                })
            ],
            'nama' => 'required|string|max:200',
            'telepon' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:100',
            'tipe' => 'nullable|string|max:50',
            'member' => 'nullable|in:none,silver,gold,platinum',
            'alamat' => 'nullable|string',
            'kota' => 'nullable|string|max:100',
            'limit' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,nonactive',
            'catatan' => 'nullable|string',
        ]);

        try {
            $customer->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Customer berhasil diperbarui',
                'data' => $customer
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui customer: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete record (soft delete).
     */
    public function destroy($id)
    {
        try {
            $data = Customer::findOrFail($id);
            $data->delete();

            return response()->json([
                'success' => true,
                'message' => 'Customer berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus customer: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate auto customer code.
     */
    public function generateCode()
    {
        $last = Customer::orderBy('kode', 'desc')->first();

        if (!$last) {
            $newCode = 'CUST-001';
        } else {
            $lastNum = (int) substr($last->kode, -3);
            $newNum = str_pad($lastNum + 1, 3, '0', STR_PAD_LEFT);
            $newCode = 'CUST-' . $newNum;
        }

        return response()->json([
            'success' => true,
            'code' => $newCode
        ]);
    }
}
