<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Halaman utama
     */
    public function index()
    {
        return view('categories.index');
    }

    /**
     * AJAX load data dengan pagination, search, filter
     */
    public function getData(Request $request)
    {
        $query = Category::query();

        // Filter search
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('kode', 'like', '%' . $search . '%')
                  ->orWhere('nama', 'like', '%' . $search . '%');
            });
        }

        // Filter status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Order by latest
        $query->orderBy('created_at', 'desc');

        // Pagination
        $perPage = $request->get('per_page', 10);
        $categories = $query->paginate($perPage);

        return response()->json($categories);
    }

    /**
     * Store kategori baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => [
                'required',
                'string',
                'max:50',
                Rule::unique('categories')->where(function ($q) {
                    return $q->where('tenant_id', Auth::user()->tenant_id);
                }),
            ],
            'nama' => 'required|string|max:200',
            'status' => 'required|in:active,nonactive',
        ]);

        $validated['tenant_id'] = Auth::user()->tenant_id;

        try {
            $category = Category::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Kategori berhasil ditambahkan',
                'data' => $category
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan kategori: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Detail kategori
     */
    public function show($id)
    {
        $category = Category::findOrFail($id);
        return response()->json($category);
    }

    /**
     * Update kategori
     */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'kode' => [
                'required',
                'string',
                'max:50',
                Rule::unique('categories')->where(function ($q) use ($id) {
                    return $q->where('tenant_id', Auth::user()->tenant_id)
                             ->where('id', '!=', $id);
                }),
            ],
            'nama' => 'required|string|max:200',
            'status' => 'required|in:active,nonactive',
        ]);

        try {
            $category->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Kategori berhasil diperbarui',
                'data' => $category
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui kategori: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hapus kategori
     */
    public function destroy($id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->delete();

            return response()->json([
                'success' => true,
                'message' => 'Kategori berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus kategori: ' . $e->getMessage()
            ], 500);
        }
    }
    /**
 * Generate auto kode kategori
 */
public function generateCode()
{
    // Ambil kode kategori terakhir berdasarkan tenant
    $lastCat = Category::where('tenant_id', Auth::user()->tenant_id)
        ->orderBy('kode', 'desc')
        ->first();

    if (!$lastCat) {
        $newCode = 'CAT-001';
    } else {
        $lastCode = $lastCat->kode;     // contoh: "CAT-003"
        $lastNumber = (int) substr($lastCode, -3);
        $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);

        $newCode = 'CAT-' . $newNumber;
    }

    return response()->json([
        'success' => true,
        'code' => $newCode
    ]);
}

}
