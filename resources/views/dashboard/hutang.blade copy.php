@extends('layouts.apps')

@section('content')

<section>


        <!-- Header -->
        <div class="d-flex justify-content-between align-items-start">
            <div>
                 <div class="page-header-title">Dashboard Hutang Supplier</div>
<div class="page-header-sub">Pantau keadaan hutang pembelian secara real-time.</div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('purchases.index') }}" class="btn-soft light" id="btnRefresh">
                    <i class="bi bi-arrow-clockwise"></i> Kembali
                </a>

            </div>
        </div>

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
</section>

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

</script>
@endpush
