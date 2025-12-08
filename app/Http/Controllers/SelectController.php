<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SelectController extends Controller
{
    public function product()
    {
        $data = Product::select('id','nama','harga_modal')
            ->orderBy('nama')
            ->get();

        return response()->json($data);
    }

    public function supplier()
    {
        $data = Supplier::select('id','nama_supplier')
            ->orderBy('nama_supplier')
            ->get();

        return response()->json($data);
    }
}
