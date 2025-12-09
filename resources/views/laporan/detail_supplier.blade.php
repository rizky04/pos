@extends('layouts.apps')

@section('content')
<section>
<div class="page-header-title">Detail Hutang Supplier</div>
<div class="page-header-sub">Supplier: {{ $supplier->nama_supplier }}</div>

<table class="table table-bordered mt-3">
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>No Invoice</th>
            <th>Total</th>
            <th>Dibayar</th>
            <th>Sisa</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($invoices as $p)
        <tr>
            <td>{{ $p['tanggal'] }}</td>
            <td>{{ $p['invoice'] }}</td>
            <td>Rp {{ number_format($p['total']) }}</td>
            <td>Rp {{ number_format($p['paid']) }}</td>
            <td><b>Rp {{ number_format($p['remaining']) }}</b></td>
            <td>
                <a href="/purchases/{{ $p['id'] }}/print" target="_blank" class="btn btn-sm">
                    <i class="bi bi-printer"></i>
                </a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
</section>
@endsection
