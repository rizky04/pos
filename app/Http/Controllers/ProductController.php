<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Index page (blade)
     */
    public function index()
    {
        $categories = Category::where('tenant_id', Auth::user()->tenant_id)->get();
        $units = Unit::where('tenant_id', Auth::user()->tenant_id)->get();
        return view('products.index', compact('categories', 'units'));
    }

    /**
     * Get data AJAX (pagination, search, filter)
     */
    public function getData(Request $request)
    {
        $query = Product::with(['category', 'unit'])
            ->where('tenant_id', Auth::user()->tenant_id);

        // Search kode / nama
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode', 'like', '%' . $search . '%')
                  ->orWhere('nama', 'like', '%' . $search . '%');
            });
        }

        // Filter kategori
        if ($request->filled('category_id') && $request->category_id !== 'all') {
            $query->where('category_id', $request->category_id);
        }

        // Filter unit
        if ($request->filled('unit_id') && $request->unit_id !== 'all') {
            $query->where('unit_id', $request->unit_id);
        }

        // Filter status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $query->orderBy('created_at', 'desc');

        $perPage = $request->get('per_page', 10);
        $products = $query->paginate($perPage);

        return response()->json($products);
    }

    /**
     * Create view
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store (POST)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => [
                'required',
                'string',
                'max:50',
                Rule::unique('products')->where(function ($query) {
                    return $query->where('tenant_id', Auth::user()->tenant_id);
                })
            ],
            'nama' => 'required|string|max:200',
            'category_id' => 'required|exists:categories,id',
            'unit_id' => 'required|exists:units,id',
            'harga_modal' => 'required|integer|min:0',
            'harga_jual' => 'required|integer|min:0',
            'stok' => 'required|integer|min:0',
            'status' => 'required|in:active,nonactive',
        ]);

        $validated['tenant_id'] = Auth::user()->tenant_id;

        try {
            $product = Product::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil ditambahkan',
                'data' => $product
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan produk: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show detail
     */
    public function show($id)
    {
        $product = Product::with(['category', 'unit'])
            ->where('tenant_id', Auth::user()->tenant_id)
            ->findOrFail($id);

        return response()->json($product);
    }

    /**
     * Edit (blade)
     */
    public function edit($id)
    {
        $product = Product::where('tenant_id', Auth::user()->tenant_id)->findOrFail($id);
        return view('products.edit', compact('product'));
    }

    /**
     * Update (PUT)
     */
    public function update(Request $request, $id)
    {
        $product = Product::where('tenant_id', Auth::user()->tenant_id)->findOrFail($id);

        $validated = $request->validate([
            'kode' => [
                'required',
                Rule::unique('products')
                    ->where(function ($query) use ($id) {
                        return $query->where('tenant_id', Auth::user()->tenant_id)
                                     ->where('id', '!=', $id);
                    })
            ],
            'nama' => 'required|string|max:200',
            'category_id' => 'required|exists:categories,id',
            'unit_id' => 'required|exists:units,id',
            'harga_modal' => 'required|integer|min:0',
            'harga_jual' => 'required|integer|min:0',
            'stok' => 'required|integer|min:0',
            'status' => 'required|in:active,nonactive',
        ]);

        try {
            $product->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil diperbarui',
                'data' => $product
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui produk: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete (Soft Delete)
     */
    public function destroy($id)
    {
        try {
            $product = Product::where('tenant_id', Auth::user()->tenant_id)->findOrFail($id);
            $product->delete();

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil dihapus'
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus produk: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate automatic product code
     */
    public function generateCode()
    {
        $last = Product::where('tenant_id', Auth::user()->tenant_id)
            ->orderBy('kode', 'desc')
            ->first();

        if (!$last) {
            $newCode = 'PRD-001';
        } else {
            $lastNumber = (int) substr($last->kode, -3);
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
            $newCode = 'PRD-' . $newNumber;
        }

        return response()->json([
            'success' => true,
            'code' => $newCode
        ]);
    }
}
