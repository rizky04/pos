<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Bukti Pembayaran #{{ $payment->id }}</title>

<style>
    body {
        font-family: Arial, sans-serif;
        margin: 20px;
        font-size: 14px;
    }
    .header {
        text-align: center;
        margin-bottom: 20px;
    }
    .title {
        font-size: 22px;
        font-weight: bold;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }
    td {
        padding: 6px 4px;
    }
    .bordered td, .bordered th {
        border: 1px solid #000;
        padding: 6px;
    }
    .text-end {
        text-align: right;
    }

    @media print {
        .no-print {
            display: none;
        }
    }
</style>
</head>
<body>

<div class="header">
    <div class="title">BUKTI PEMBAYARAN HUTANG</div>
    <div>No. Pembayaran: #{{ $payment->id }}</div>
</div>

<h4>Informasi Supplier</h4>
<table>
    <tr>
        <td><b>Nama Supplier</b></td>
        <td>: {{ $purchase->supplier->nama_supplier }}</td>
    </tr>
    <tr>
        <td><b>No Invoice</b></td>
        <td>: {{ $purchase->invoice }}</td>
    </tr>
    <tr>
        <td><b>Tanggal Pembelian</b></td>
        <td>: {{ $purchase->tanggal }}</td>
    </tr>
</table>

<h4>Detail Pembayaran</h4>
<table class="bordered">
    <tr>
        <th>Tanggal Bayar</th>
        <th>Metode</th>
        <th>Nominal</th>
        <th>Referensi</th>
    </tr>
    <tr>
        <td>{{ $payment->payment_date }}</td>
        <td>{{ $payment->payment_method }}</td>
        <td>Rp {{ number_format($payment->amount) }}</td>
        <td>{{ $payment->reference ?? '-' }}</td>
    </tr>
</table>

<h4>Ringkasan</h4>
<table>
    <tr>
        <td><b>Total Invoice</b></td>
        <td class="text-end">Rp {{ number_format($purchase->grand_total) }}</td>
    </tr>
    <tr>
        <td><b>Total Dibayar</b></td>
        <td class="text-end">Rp {{ number_format($totalPaid) }}</td>
    </tr>
    <tr>
        <td><b>Sisa Hutang</b></td>
        <td class="text-end"><b>Rp {{ number_format($remaining) }}</b></td>
    </tr>
</table>

<div class="no-print" style="margin-top:20px; text-align:center;">
    <button onclick="window.print()"
        style="padding:8px 20px; font-size:14px;">
        Print
    </button>
</div>

</body>
</html>
