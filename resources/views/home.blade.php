@extends('layouts.main')

@section('content')
<div class="page-header mb-4">
    <h4>Dashboard Penjualan</h4>
    <h6>Ringkasan aktivitas penjualan dan pembayaran</h6>
</div>

<div class="row">
    <div class="col-md-3 mb-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5>Total Penjualan</h5>
                <h3>Rp {{ number_format($totalSales, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h5>Total Pembayaran</h5>
                <h3>Rp {{ number_format($totalPaid, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-warning text-dark">
            <div class="card-body">
                <h5>Piutang</h5>
                <h3>Rp {{ number_format($totalDue, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-secondary text-white">
            <div class="card-body">
                <h5>Transaksi Hari Ini</h5>
                <h3>{{ $todaySalesCount }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-8 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                Grafik Penjualan Bulanan
            </div>
            <div class="card-body">
                <canvas id="salesChart" height="120"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header bg-success text-white">
                Status Pembayaran
            </div>
            <div class="card-body">
                <canvas id="statusChart" height="220"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">Transaksi Terbaru</div>
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Client</th>
                    <th>Total</th>
                    <th>Dibayar</th>
                    <th>Sisa</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($recentSales ?? [] as $s)
                <tr>
                    <td>{{ $s->nomor_sales }}</td>
                    <td>{{ $s->sales_date }}</td>
                    <td>{{ $s->client->nama_client ?? '-' }}</td>
                    <td>Rp {{ number_format($s->total, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($s->total_paid, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($s->due_amount, 0, ',', '.') }}</td>
                    <td>
                        <span class="badge bg-{{ $s->status_bayar == 'lunas' ? 'success' : ($s->status_bayar == 'cicil' ? 'warning' : 'danger') }}">
                            {{ ucfirst($s->status_bayar) }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="card mt-4">
    <div class="card-header bg-info text-white">
        Barang Terlaris
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>Total Terjual</th>
                    <th>Total Penjualan (Rp)</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($topItems as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->barang->nama_barang ?? '-' }}</td>
                        <td>{{ $item->total_qty }}</td>
                        <td>Rp {{ number_format($item->total_sales, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Belum ada data penjualan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@php
    // Buat label bulan manual agar aman
    $monthLabels = [];
    foreach (array_keys($salesPerMonth) as $m) {
        $monthLabels[] = date('M', mktime(0, 0, 0, $m, 1));
    }
@endphp

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // === Data dari Laravel ===
    const salesData = @json(array_values($salesPerMonth));
    const salesLabels = @json($monthLabels);

    const statusData = @json(array_values($statusCounts));
    const statusLabels = @json(array_keys($statusCounts));

    // === Chart Penjualan Bulanan ===
    const ctx1 = document.getElementById('salesChart').getContext('2d');
    new Chart(ctx1, {
        type: 'line',
        data: {
            labels: salesLabels,
            datasets: [{
                label: 'Total Penjualan (Rp)',
                data: salesData,
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.2)',
                borderWidth: 2,
                fill: true,
                tension: 0.3,
                pointRadius: 4,
                pointBackgroundColor: '#007bff'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: true, position: 'top' }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });

    // === Chart Status Pembayaran ===
    const ctx2 = document.getElementById('statusChart').getContext('2d');
    new Chart(ctx2, {
        type: 'pie',
        data: {
            labels: statusLabels.map(label => label.charAt(0).toUpperCase() + label.slice(1)),
            datasets: [{
                data: statusData,
                backgroundColor: ['#28a745', '#ffc107', '#dc3545']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' },
            }
        }
    });
</script>
@endpush
@endsection
