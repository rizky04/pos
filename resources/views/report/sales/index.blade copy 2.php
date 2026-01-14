@extends('layouts.apps')

@section('content')
<section>
<div class="page-header">
    <div class="page-title">
        <h4>Laporan Penjualan</h4>
        <h6>List, Kasir, Laba Kotor & Bersih</h6>
    </div>
</div>
<div class="card mb-3">
    <div class="card-body">
        <div class="row align-items-end">
            <div class="col-md-4">
                <label>Dari Tanggal</label>
                <input type="date" id="start_date" class="form-control">
            </div>
            <div class="col-md-4">
                <label>Sampai Tanggal</label>
                <input type="date" id="end_date" class="form-control">
            </div>
            <div class="col-md-4">
                <button class="btn btn-primary w-100" id="btnFilter">
                    🔍 Terapkan Filter
                </button>
            </div>
        </div>
    </div>
</div>

<div class="row">
    {{-- TAB KIRI --}}
    <div class="col-md-3">
        <div class="card">
            <div class="card-body p-2">
                <div class="nav flex-column nav-pills">
                    <button class="nav-link active" data-tab="list">📄 List Penjualan</button>
                    <button class="nav-link" data-tab="kasir">👤 Per Kasir</button>
                    <button class="nav-link" data-tab="kotor">📈 Laba Kotor</button>
                    <button class="nav-link" data-tab="bersih">💰 Laba Bersih</button>
                    <button class="nav-link" data-tab="grafik">📊 Grafik</button>
                </div>
            </div>
        </div>
    </div>

    {{-- KONTEN KANAN --}}
    <div class="col-md-9">

        {{-- ================= LIST PENJUALAN ================= --}}
        <div class="tab-content active" id="tab-list">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive text-center">
                        <table class="table table-bordered" id="sales-table">
                            <thead class="bg-light">
                                <tr>
                                    <th>#</th>
                                    <th>Tanggal</th>
                                    <th>Nomor Sales</th>
                                    <th>Client</th>
                                    <th>Status</th>
                                    <th>Total</th>
                                    <th>Paid</th>
                                    <th>Due</th>
                                    <th>Detail</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <ul class="pagination mt-2" id="pagination"></ul>
                </div>
            </div>
        </div>

        {{-- ================= PER KASIR ================= --}}
        <div class="tab-content d-none" id="tab-kasir">
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead class="bg-light">
                            <tr>
                                <th>Kasir</th>
                                <th>Total Transaksi</th>
                                <th>Total Penjualan</th>
                            </tr>
                        </thead>
                        <tbody id="kasir-body"></tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ================= LABA KOTOR ================= --}}
        <div class="tab-content d-none" id="tab-kotor">
            <div class="card text-center">
                <div class="card-body">
                    <h6>Laba Kotor</h6>
                    <h2 class="text-success" id="laba-kotor">Rp 0</h2>
                </div>
            </div>
        </div>

        {{-- ================= LABA BERSIH ================= --}}
        <div class="tab-content d-none" id="tab-bersih">
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr><th>Total Penjualan</th><td id="total-penjualan"></td></tr>
                        <tr><th>HPP</th><td id="hpp"></td></tr>
                        <tr><th>Diskon</th><td id="diskon"></td></tr>
                        <tr><th>PPN</th><td id="ppn"></td></tr>
                        <tr class="bg-light"><th>Laba Bersih</th><td id="laba-bersih" class="fw-bold text-success"></td></tr>
                    </table>
                </div>
            </div>
        </div>
        {{-- ================= GRAFIK ================= --}}
        <div class="tab-content d-none" id="tab-grafik">
            <div class="row">
                <div class="col-md-12 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h6>Grafik Penjualan Harian</h6>
                            <canvas id="chartSales"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h6>Penjualan per Kasir</h6>
                            <canvas id="chartKasir"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h6>Laba Kotor vs Bersih</h6>
                            <canvas id="chartLaba"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>
</section>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
  $('#btnFilter').on('click', function () {
    loadSales();
    loadKasir();
    loadLabaKotor();
    loadLabaBersih();

    // HANYA load chart jika tab grafik aktif
    if ($('.nav-link.active').data('tab') === 'grafik') {
        setTimeout(() => {
            loadChartSales();
            loadChartKasir();
            loadChartLaba();
        }, 100);
    }
});


    function getDateFilter(){
    return {
        start_date: $('#start_date').val(),
        end_date: $('#end_date').val()
    }
}

function rupiah(v){
    return new Intl.NumberFormat('id-ID',{style:'currency',currency:'IDR',minimumFractionDigits:0}).format(v);
}

/* ================= TAB SWITCH ================= */
// $('.nav-link').on('click', function(){
//     $('.nav-link').removeClass('active');
//     $(this).addClass('active');

//     $('.tab-content').addClass('d-none');
//     $('#tab-' + $(this).data('tab')).removeClass('d-none');

//     if($(this).data('tab') === 'kasir') loadKasir();
//     if($(this).data('tab') === 'kotor') loadLabaKotor();
//     if($(this).data('tab') === 'bersih') loadLabaBersih();
//      if(tab === 'grafik'){
//         setTimeout(() => {
//             loadChartSales();
//             loadChartKasir();
//             loadChartLaba();
//         }, 100);
//     }
// });

$('.nav-link').on('click', function () {

    const tab = $(this).data('tab'); // ⬅️ INI YANG HILANG

    $('.nav-link').removeClass('active');
    $(this).addClass('active');

    $('.tab-content').addClass('d-none');
    $('#tab-' + tab).removeClass('d-none');

    if (tab === 'kasir') loadKasir();
    if (tab === 'kotor') loadLabaKotor();
    if (tab === 'bersih') loadLabaBersih();

    if (tab === 'grafik') {
        setTimeout(() => {
            loadChartSales();
            loadChartKasir();
            loadChartLaba();
        }, 100);
    }
});


/* ================= LIST ================= */
// function loadSales(page = 1){
//     $.get("{{ route('sales.data') }}",{page},res=>{
//         let rows='',i=(res.current_page-1)*res.per_page;
//         res.data.forEach(s=>{
//             rows+=`
//             <tr>
//                 <td>${++i}</td>
//                 <td>${s.sales_date}</td>
//                 <td>${s.nomor_sales}</td>
//                 <td>${s.client?.nama_client || '-'}</td>
//                 <td>${s.status_bayar}</td>
//                 <td>${rupiah(s.total)}</td>
//                 <td>${rupiah(s.total_paid)}</td>
//                 <td>${rupiah(s.due_amount)}</td>
//                 <td><button class="btn btn-sm btn-info">👁</button></td>
//             </tr>`;
//         });
//         $('#sales-table tbody').html(rows);
//     });
// }
function loadSales(page = 1){
    $.get("{{ route('sales.data') }}", {
        page,
        ...getDateFilter()
    }, res => {
        let rows='',i=(res.current_page-1)*res.per_page;
        res.data.forEach(s=>{
            rows+=`
            <tr>
                <td>${++i}</td>
                <td>${s.sales_date}</td>
                <td>${s.nomor_sales}</td>
                <td>${s.client?.nama_client || '-'}</td>
                <td>${s.status_bayar}</td>
                <td>${rupiah(s.total)}</td>
                <td>${rupiah(s.total_paid)}</td>
                <td>${rupiah(s.due_amount)}</td>
                <td><button class="btn btn-sm btn-info">👁</button></td>
            </tr>`;
        });
        $('#sales-table tbody').html(rows);
    });
}


/* ================= KASIR ================= */
// function loadKasir(){
//     $.get('/sales/report/kasir',res=>{
//         let rows='';
//         res.forEach(r=>{
//             rows+=`
//             <tr>
//                 <td>${r.user?.name || '-'}</td>
//                 <td>${r.total_transaksi}</td>
//                 <td>${rupiah(r.total_penjualan)}</td>
//             </tr>`;
//         });
//         $('#kasir-body').html(rows);
//     });
// }
function loadKasir(){
    $.get('/sales/report/kasir', getDateFilter(), res=>{
        let rows='';
        res.forEach(r=>{
            rows+=`
            <tr>
                <td>${r.user?.name || '-'}</td>
                <td>${r.total_transaksi}</td>
                <td>${rupiah(r.total_penjualan)}</td>
            </tr>`;
        });
        $('#kasir-body').html(rows);
    });
}


/* ================= LABA KOTOR ================= */
// function loadLabaKotor(){
//     $.get('/sales/report/laba-kotor',res=>{
//         $('#laba-kotor').text(rupiah(res.laba_kotor));
//     });
// }
function loadLabaKotor(){
    $.get('/sales/report/laba-kotor', getDateFilter(), res=>{
        $('#laba-kotor').text(rupiah(res.laba_kotor));
    });
}


/* ================= LABA BERSIH ================= */
// function loadLabaBersih(){
//     $.get('/sales/report/laba-bersih',res=>{
//         $('#total-penjualan').text(rupiah(res.total_penjualan));
//         $('#hpp').text(rupiah(res.hpp));
//         $('#diskon').text(rupiah(res.diskon));
//         $('#ppn').text(rupiah(res.ppn));
//         $('#laba-bersih').text(rupiah(res.laba_bersih));
//     });
// }
function loadLabaBersih(){
    $.get('/sales/report/laba-bersih', getDateFilter(), res=>{
        $('#total-penjualan').text(rupiah(res.total_penjualan));
        $('#hpp').text(rupiah(res.hpp));
        $('#diskon').text(rupiah(res.diskon));
        $('#ppn').text(rupiah(res.ppn));
        $('#laba-bersih').text(rupiah(res.laba_bersih));
    });
}


$(document).ready(()=>loadSales());

const chartSalesEl = document.getElementById('chartSales');
const chartKasirEl = document.getElementById('chartKasir');
const chartLabaEl  = document.getElementById('chartLaba');


let chartSales, chartKasir, chartLaba;
function loadChartSales(){
    $.get("{{ route('sales.data') }}", getDateFilter(), res => {

        let labels = [];
        let totals = [];

        res.data.forEach(r => {
            labels.push(r.sales_date);
            totals.push(r.total);
        });

        if(chartSales) chartSales.destroy();

        chartSales = new Chart(chartSalesEl, {
            type: 'line',
            data: {
                labels,
                datasets: [{
                    label: 'Total Penjualan',
                    data: totals,
                    tension: 0.4,
                    fill: true
                }]
            }
        });
    });
}
function loadChartKasir(){
    $.get('/sales/report/kasir', getDateFilter(), res => {

        let labels = [];
        let totals = [];

        res.forEach(r => {
            labels.push(r.user?.name || 'Kasir');
            totals.push(r.total_penjualan);
        });

        if(chartKasir) chartKasir.destroy();

        chartKasir = new Chart(chartKasirEl, {
            type: 'bar',
            data: {
                labels,
                datasets: [{
                    label: 'Total Penjualan',
                    data: totals
                }]
            }
        });
    });
}
function loadChartLaba(){
    $.get('/sales/report/laba-bersih', getDateFilter(), res => {

        if(chartLaba) chartLaba.destroy();

        chartLaba = new Chart(chartLabaEl, {
            type: 'doughnut',
            data: {
                labels: ['Laba Kotor', 'Laba Bersih'],
                datasets: [{
                    data: [res.laba_kotor, res.laba_bersih]
                }]
            }
        });
    });
}


</script>
@endpush
