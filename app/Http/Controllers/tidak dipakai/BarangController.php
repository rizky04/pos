<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Http\Requests\StoreBarangRequest;
use App\Http\Requests\UpdateBarangRequest;
use Illuminate\Http\Request;
 use SimpleSoftwareIO\QrCode\Facades\QrCode;


class BarangController extends Controller
{
     public function index()
    {
        return view('barang.index');
    }

    // public function getData(Request $request)
    // {
    //     $query = Barang::query();

    //     if ($request->has('search') && $request->search != '') {
    //         $query->where('id_barang', $request->search)
    //             ->orWhere('nama_barang', 'like', "%$request->search%")
    //             ->orWhere('merk_barang', 'like', "%$request->search%")
    //             ->orWhere('keterangan', 'like', "%$request->search%")
    //             ->orWhere('jenis', 'like', "%$request->search%");
    //     }

    //     // $barang = $query->where('hapus', '0')->orderBy('stok_barang', 'asc')->paginate(15);
    //     $barang = $query->where('stok_barang', '>', 'pagu')->orderBy('stok_barang', 'desc')->paginate(15);

    //     return response()->json($barang);
    // }

    public function getData(Request $request)
{
    $query = Barang::query();

    // 🔍 Filter pencarian teks
    if ($request->has('search') && $request->search != '') {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('id_barang', $search)
                ->orWhere('kode_barang', 'like', "%$search%")
                ->orWhere('nama_barang', 'like', "%$search%")
                ->orWhere('merk_barang', 'like', "%$search%")
                ->orWhere('keterangan', 'like', "%$search%")
                ->orWhere('jenis', 'like', "%$search%");
        });
    }

    // 🧠 Filter batas aman stok
    if ($request->has('filter') && $request->filter != '') {
        if ($request->filter == 'aman') {
            // Stok aman = stok >= pagu
            $query->whereColumn('stok_barang', '>=', 'pagu');
        } elseif ($request->filter == 'tidak_aman') {
            // Stok tidak aman = stok < pagu
            $query->whereColumn('stok_barang', '<', 'pagu');
        }
    }

    // 🚫 Pastikan tidak menampilkan data yang dihapus
    $query->where('hapus', '0');

    // 🔢 Urutkan dari stok terendah agar mudah terlihat mana yang tidak aman
    $barang = $query->orderBy('stok_barang', 'desc')->paginate(15);

    return response()->json($barang);
}


    // app/Http/Controllers/SparepartController.php
    public function search(Request $request)
    {
        $search = $request->get('q');

        $spareparts = Barang::query()
            ->where('nama_barang', 'like', "%$search%")
            ->orWhere('kode_barang', 'like', "%$search%")
            ->orWhere('merk_barang', 'like', "%$search%")
            ->orWhere('hapus', '0')
            ->limit(20)
            ->get();

        $results = $spareparts->map(function ($sp) {
            return [
                'id' => $sp->id,
                'text' => $sp->nama_barang . ' (' . $sp->kode_barang . ')' . ' - ' .  $sp->merk_barang,
            ];
        });

        return response()->json(['results' => $results]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'kode_barang' => 'required|unique:tbl_barang',
            'nama_barang' => 'required',
            'merk_barang' => 'required',
            'keterangan' => 'required',
            'lokasi' => 'required',
            'stok_barang' => 'required',
            'pagu' => 'required',
            'harga_kulak' => 'required',
            'harga_jual' => 'required',
            'distributor' => 'required',
            'jenis' => 'required',
            'hapus' => 'required',
        ]);

        Barang::create($request->all());

        return response()->json(['success' => true, 'message' => 'Product berhasil ditambahkan']);
    }
     public function show($id)
    {
        return Barang::findOrFail($id);
    }


    public function update(Request $request, $id)
    {
        $product = Barang::findOrFail($id);
        $product->update($request->all());

        return response()->json(['success' => true, 'message' => 'Product berhasil diupdate']);
    }

    public function destroy($id)
    {
        // Barang::findOrFail($id)->delete();
        // return response()->json(['success' => true, 'message' => 'Product berhasil dihapus']);
        Barang::where('id_barang', $id)->update(['hapus' => 1]);
        return response()->json(['success' => true, 'message' => 'Product berhasil dinonaktifkan']);
    }

    public function nonActive($id)
    {
        // dd($id);
        Barang::where('id_barang', $id)->update(['hapus' => 1]);
        return response()->json(['success' => true, 'message' => 'Product berhasil dinonaktifkan']);
    }



public function generateQr($id)
{
    $barang = Barang::findOrFail($id);
    // dd($barang);

    // Data yang ingin ditampilkan di QR
    // $url = url('/barang/' . $barang->id_barang);

    // Hasilkan QR Code SVG atau PNG
    // $qr = base64_encode(QrCode::format('png')->size(200)->generate($url));

    return view('barang.qr', compact('barang'));
}

public function printQr(Request $request)
{
    // dd($request->all());
    // Kalau ada pilihan barang yang mau diprint
    $ids = $request->get('ids');

    $query = Barang::query();
    if ($ids) {
        $query->whereIn('id_barang', explode(',', $ids));
    }

    $barangs = $query->where('hapus', 0)->paginate(100);

    return view('barang.print_qr', compact('barangs'));
}

public function getByCode($code)
{
    $barang = Barang::where('id_barang', $code)->orWhere('hapus', '0')->first();

    if (!$barang) {
        return response()->json(null, 404);
    }

    return response()->json([
        'id_barang' => $barang->id_barang,
        'nama_barang' => $barang->nama_barang,
        'kode_barang' => $barang->kode_barang,
        'harga_jual' => $barang->harga_jual,
        'harga_kulak' => $barang->harga_kulak,
        'stock' => $barang->stok_barang,
    ]);
}


public function getByQR($code)
{
    $barang = Barang::where('id_barang', $code)->orWhere('hapus', '0')->first();

    if (!$barang) {
        return response()->json(['message' => 'Barang tidak ditemukan'], 404);
    }

    return response()->json([
        'id_barang'    => $barang->id_barang,
        'nama_barang'  => $barang->nama_barang,
        'kode_barang'  => $barang->kode_barang,
        'harga_jual'   => $barang->harga_jual,
        'harga_kulak'  => $barang->harga_kulak,
        'stok_barang'  => $barang->stok_barang,
    ]);
}



}
