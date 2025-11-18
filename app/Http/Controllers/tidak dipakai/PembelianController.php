<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PembelianController extends Controller
{
    public function index()
    {
        return view('pembelian.index');
    }

    public function data(Request $request)
    {
        $query = Pembelian::with('barang')
            ->when($request->search, fn($q) =>
                $q->whereHas('barang', fn($b) =>
                    $b->where('nama_barang', 'like', "%$request->search%")
                )
            )
            ->orderBy('id_pembelian', 'desc')
            ->paginate(10);

        return response()->json($query);
    }

    public function barang(Request $request)
{
    $search = $request->get('q');
    $query = Barang::query();

    if (!empty($search)) {
        $query->where('id_barang', $search)
            ->orWhere('nama_barang', 'like', "%$search%")
            ->orWhere('merk_barang', 'like', "%$search%")
            ->orWhere('kode_barang', 'like', "%$search%");
    }

    $data = $query->limit(20)->get();

    $formatted = $data->map(function ($item) {
        return [
            'id' => $item->id_barang,
            'text' => $item->nama_barang . ' (' . $item->kode_barang . ')',
            'kode_barang' => $item->kode_barang,
            'harga_kulak' => $item->harga_kulak,
            'harga_jual' => $item->harga_jual,
            'stok' => $item->stok_barang,
        ];
    });

    return response()->json($formatted);
}


    public function store(Request $request)
    {
        DB::transaction(function () use ($request) {
            $barang = Barang::findOrFail($request->id_barang);
            $stok_awal = $barang->stok_barang;
            $stok_akhir = $stok_awal + $request->jumlah_pembelian;

            Pembelian::create([
                'tgl_pembelian' => now(),
                'id_barang' => $request->id_barang,
                'jumlah_pembelian' => $request->jumlah_pembelian,
                'harga_kulak' => $request->harga_kulak,
                'harga_jual' => $request->harga_jual,
                'id_pengguna' => Auth::user()->id_pengguna,
            ]);

            $barang->update([
                'stok_barang' => $stok_akhir,
                'harga_kulak' => $request->harga_kulak,
                'harga_jual' => $request->harga_jual,
            ]);
        });

        return response()->json(['message' => 'Data pembelian berhasil disimpan']);
    }

    public function show($id)
    {
        $data = Pembelian::with('barang')->findOrFail($id);
        return response()->json($data);
    }

    // ✅ Tambahan fungsi edit pembelian
    public function edit($id)
    {
        $pembelian = Pembelian::with('barang')->findOrFail($id);
        return response()->json($pembelian);
    }

    // ✅ Update pembelian dan sesuaikan stok barang
    public function update(Request $request, $id)
    {
        DB::transaction(function () use ($request, $id) {
            $pembelian = Pembelian::findOrFail($id);
            $barang = Barang::findOrFail($pembelian->id_barang);

            // Hitung selisih stok berdasarkan perubahan jumlah pembelian
            $selisih = $request->jumlah_pembelian - $pembelian->jumlah_pembelian;

            // Update stok barang sesuai selisih
            $barang->stok_barang += $selisih;
            $barang->harga_kulak = $request->harga_kulak;
            $barang->harga_jual = $request->harga_jual;
            $barang->save();

            // Update data pembelian
            $pembelian->update([
                'jumlah_pembelian' => $request->jumlah_pembelian,
                'harga_kulak' => $request->harga_kulak,
                'harga_jual' => $request->harga_jual,
            ]);
        });

        return response()->json(['message' => 'Data pembelian berhasil diperbarui']);
    }

    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $pembelian = Pembelian::findOrFail($id);
            $barang = Barang::findOrFail($pembelian->id_barang);

            // kurangi stok barang sesuai pembelian
            $barang->stok_barang -= $pembelian->jumlah_pembelian;
            $barang->save();

            $pembelian->delete();
        });

        return response()->json(['message' => 'Data pembelian berhasil dihapus']);
    }

    public function barangInfo($id)
    {
        $barang = Barang::findOrFail($id);
        return response()->json($barang);
    }
}
