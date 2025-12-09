@extends('layouts.apps')

@section('content')
    <section>
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <div class="page-header-title">Laporan Hutang Supplier</div>
                <div class="page-header-sub">Detail hutang pembelian berdasarkan supplier.</div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('purchases.index') }}" class="btn-soft light" id="btnRefresh">
                    <i class="bi bi-arrow-clockwise"></i> Kembali
                </a>
            </div>
        </div>

        <div class="container">
            <div class="row mt-3">
                <!-- Card Total Hutang -->
                <div class="col-md-4">
                    <div class="card p-3 shadow-sm">
                        <h6>Total Pembelian Belum Lunas</h6>
                        <h3 id="cardTotalUnpaid">Rp 0</h3>
                    </div>
                </div>
                <!-- Card Total Pembayaran -->
                <div class="col-md-4">
                    <div class="card p-3 shadow-sm">
                        <h6>Total Pembayaran</h6>
                        <h3 id="cardTotalPaid">Rp 0</h3>
                    </div>
                </div>
                <!-- Card Sisa -->
                <div class="col-md-4">
                    <div class="card p-3 shadow-sm">
                        <h6>Sisa Hutang</h6>
                        <h3 id="cardRemaining">Rp 0</h3>
                    </div>
                </div>
            </div>
            <!-- Chart -->
            <div class="card mt-3 p-3 shadow-sm">
                <h6>Hutang per Supplier</h6>
                <canvas id="hutangChart" height="120"></canvas>
            </div>
        </div>

        <!-- TABLE -->
        <div class="table-wrap">
            <form method="GET" class="row g-2 mb-3">

                <div class="col-md-3">
                    <label class="form-label">Supplier</label>
                    <select name="supplier_id" class="form-select">
                        <option value="">Semua Supplier</option>
                        @foreach ($suppliers as $s)
                            <option value="{{ $s->id }}" {{ request('supplier_id') == $s->id ? 'selected' : '' }}>
                                {{ $s->nama_supplier }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label>Dari</label>
                    <input type="date" name="from_date" class="form-control" value="{{ request('from_date') ?? '' }}">
                </div>

                <div class="col-md-2">
                    <label>Sampai</label>
                    <input type="date" name="to_date" class="form-control" value="{{ request('to_date') ?? '' }}">
                </div>

                <div class="col-md-2 align-self-end">
                    <button class="btn-soft">Filter</button>
                </div>


            </form>

            <div class="table-responsive">
                <table class="table align-middle" id="transactionTable">
                    <thead>
                        <tr>
                            <th>no</th>
                            <th>Supplier</th>
                            <th>Total Pembelian</th>
                            <th>Total Pembayaran</th>
                            <th>Sisa Hutang</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($result as $r)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $r['supplier'] }}</td>
                                <td>Rp {{ number_format($r['total_purchase']) }}</td>
                                <td>Rp {{ number_format($r['total_paid']) }}</td>
                                <td><b>Rp {{ number_format($r['remaining']) }}</b></td>
                                <td>
                                    <button class="btn btn-sm btn-detail-supplier" data-id="{{ $r['supplier_id'] }}">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </td>

                                {{-- <td>
                                    <a href="{{ route('laporan.detail_supplier', $r['supplier_id']) }}" class="btn btn-sm">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td> --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    <!-- Modal Detail Supplier -->
<div class="modal fade" id="modalDetailSupplier" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Detail Hutang Supplier</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <h6 id="supplierName" class="mb-3"></h6>

                <table class="table table-bordered">
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
                    <tbody id="detailBody"></tbody>
                </table>
            </div>

        </div>
    </div>
</div>

@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Load summary cards
        $.get('/dashboard/getDataHutang', function(res) {
            $("#cardTotalUnpaid").text("Rp " + res.total_unpaid.toLocaleString());
            $("#cardTotalPaid").text("Rp " + res.total_paid.toLocaleString());
            $("#cardRemaining").text("Rp " + res.remaining_debt.toLocaleString());
        });


        // Chart hutang per supplier
        $.get('/dashboard/hutang/chart', function(res) {

            const labels = res.map(x => x.supplier);
            const values = res.map(x => x.hutang);

            new Chart(document.getElementById('hutangChart'), {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Hutang Supplier',
                        data: values
                    }]
                }
            });

        });

        $(document).on("click", ".btn-detail-supplier", function() {
    const supplierId = $(this).data("id");

    $.ajax({
        url: `/laporan/hutang/detail/${supplierId}`,
        method: "GET",
        success: function(res) {

            $("#supplierName").text("Supplier: " + res.supplier.nama_supplier);

            let html = "";
            res.invoices.forEach(inv => {
                html += `
                    <tr>
                        <td>${inv.tanggal}</td>
                        <td>${inv.invoice}</td>
                        <td>Rp ${inv.total.toLocaleString()}</td>
                        <td>Rp ${inv.paid.toLocaleString()}</td>
                        <td><b>Rp ${inv.remaining.toLocaleString()}</b></td>
                        <td>
                            <a href="/purchases/${inv.id}/print" target="_blank" class="btn btn-sm">
                                <i class="bi bi-printer"></i>
                            </a>
                        </td>
                    </tr>
                `;
            });

            $("#detailBody").html(html);

            const modal = new bootstrap.Modal(document.getElementById('modalDetailSupplier'));
            modal.show();
        }
    });
});

    </script>
@endpush
