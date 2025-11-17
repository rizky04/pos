<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UnitController extends Controller
{
    public function index()
    {
        return view('units.index'); // halaman blade frontend
    }

    /**
     * GET DATA (AJAX)
     */
    public function getData(Request $request)
    {
        $query = Unit::query();

        // Filter search
        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($x) use ($q) {
                $x->where('nama', 'like', "%$q%")
                  ->orWhere('kode', 'like', "%$q%");
            });
        }

        // Filter status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter default
        if ($request->filled('is_default')) {
            if ($request->is_default == "yes") {
                $query->where('is_default', true);
            } elseif ($request->is_default == "no") {
                $query->where('is_default', false);
            }
        }

        $query->orderBy('created_at', 'desc');

        $perPage = $request->get('per_page', 10);
        return response()->json($query->paginate($perPage));
    }


    /**
     * STORE
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:200',
            'kode' => [
                'required',
                'string',
                'max:20',
                Rule::unique('units')->where(function ($q) {
                    return $q->where('tenant_id', Auth::user()->tenant_id);
                })
            ],
            'tipe' => 'required|string',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:active,nonactive',
        ]);

        // Reset default jika satuan baru menjadi default
        if ($request->is_default) {
            Unit::where('tenant_id', Auth::user()->tenant_id)->update(['is_default' => false]);
        }

        $validated['is_default'] = $request->is_default ? 1 : 0;
        $validated['tenant_id'] = Auth::user()->tenant_id;

        $unit = Unit::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Satuan berhasil ditambahkan',
            'data' => $unit
        ]);
    }


    /**
     * SHOW
     */
    public function show($id)
    {
        return response()->json(Unit::findOrFail($id));
    }


    /**
     * UPDATE
     */
    public function update(Request $request, $id)
    {
        $unit = Unit::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:200',
            'kode' => [
                'required',
                'string',
                'max:20',
                Rule::unique('units')->where(function ($q) use ($unit) {
                    return $q->where('tenant_id', Auth::user()->tenant_id)->where('id', '!=', $unit->id);
                })
            ],
            'tipe' => 'required|string',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:active,nonactive',
        ]);

        if ($request->is_default) {
            Unit::where('tenant_id', Auth::user()->tenant_id)
                ->update(['is_default' => false]);
        }

        $validated['is_default'] = $request->is_default ? 1 : 0;

        $unit->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Satuan berhasil diperbarui',
            'data' => $unit
        ]);
    }


    /**
     * DELETE
     */
    public function destroy($id)
    {
        $unit = Unit::findOrFail($id);
        $unit->delete();

        return response()->json([
            'success' => true,
            'message' => 'Satuan berhasil dihapus'
        ]);
    }
}
