<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Print Pembelian - {{ $data->invoice }}</title>

<style>
    body { font-family: Arial, sans-serif; font-size: 13px; }
    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    th, td { border: 1px solid #333; padding: 5px; }
    th { background: #eee; }
    .title { font-size: 18px; font-weight: bold; margin-bottom: 10px; }
    @media print { @page { size: A4; margin: 10mm; } }
</style>
</head>
<body>

<div class="title">Detail Pembelian</div>

<table>
    <tr><td><b>No Invoice</b></td><td>{{ $data->invoice }}</td></tr>
    <tr><td><b>Tanggal</b></td><td>{{ $data->tanggal }}</td></tr>
    <tr><td><b>Supplier</b></td><td>{{ $data->supplier->nama_supplier }}</td></tr>
    <tr><td><b>Jatuh Tempo</b></td><td>{{ $data->jatuh_tempo }}</td></tr>
    <tr><td><b>Status</b></td><td>{{ $data->status_pembelian }}</td></tr>
</table>

<h4 style="margin-top:20px;">Daftar Item</h4>

<table>
    <thead>
        <tr>
            <th>Nama Barang</th>
            <th>Qty</th>
            <th>Harga</th>
            <th>Disc (%)</th>
            <th>Subtotal</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data->items as $i)
        <tr>
            <td>{{ $i->product->nama }}</td>
            <td>{{ $i->qty }}</td>
            <td>Rp {{ number_format($i->harga_beli,0,",",".") }}</td>
            <td>{{ $i->discount_percent }}</td>
            <td>Rp {{ number_format($i->subtotal,0,",",".") }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<h3 style="text-align:right; margin-top:10px;">
    Grand Total: Rp {{ number_format($data->grand_total,0,",",".") }}
</h3>

<script>
    window.print();
</script>

</body>
</html>
