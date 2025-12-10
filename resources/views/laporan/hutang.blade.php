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
<!-- Modal Detail Supplier -->
<div class="modal fade" id="modalDetailSupplier" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <div class="modal-header d-flex justify-content-between">
                <div>
                    <h5 class="modal-title">Detail Hutang Supplier</h5>
                    <div id="supplierName" class="mt-1"></div>

                    <!-- SUMMARY -->
                    <div class="d-flex gap-4 mt-2">
                        <span>Total: <b id="sumTotal">Rp 0</b></span>
                        <span>Dibayar: <b id="sumPaid">Rp 0</b></span>
                        <span>Sisa Hutang: <b id="sumRemaining">Rp 0</b></span>
                    </div>
                </div>

                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <!-- FILTER TANGGAL -->
                <form id="filterDetailForm" class="row g-2 mb-3">
                    <input type="hidden" id="detailSupplierId">

                    <div class="col-md-3">
                        <label>Dari</label>
                        <input type="date" id="detailFrom" class="form-control">
                    </div>

                    <div class="col-md-3">
                        <label>Sampai</label>
                        <input type="date" id="detailTo" class="form-control">
                    </div>

                    <div class="col-md-2 align-self-end">
                        <button type="submit" class="btn btn-sm"><i class="bi bi-search"></i></button>
                    </div>
                </form>

                <!-- TABLE -->

  <div class="table-wrap">
            <div class="table-responsive">
                <table class="table align-middle" id="purchaseTable">
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
            <div id="emptyState" class="empty-state d-none">
                Belum ada pembelian yang sesuai filter.
            </div>

            <!-- Pagination -->
            <div class="pagination-container">
                <div id="paginationInfo"></div>
                <nav>
                    <ul class="pagination pagination-sm mb-0" id="pagination"></ul>
                </nav>
            </div>
        </div>
                 <!-- Table -->

            </div>
        </div>
    </div>
</div>

<!-- MODAL PEMBAYARAN HUTANG (UNTUK LAPORAN HUTANG SUPPLIER) -->
<div class="modal fade" id="modalPay" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Pembayaran Hutang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <input type="hidden" id="paymentPurchaseId">

                <div class="mb-2">
                    <label class="form-label">Tanggal Bayar</label>
                    <input type="date" id="payDate" class="form-control" required>
                </div>

                <div class="mb-2">
                    <label class="form-label">Metode Bayar</label>
                    <select id="payMethod" class="form-select">
                        <option value="Cash">Cash</option>
                        <option value="Transfer">Transfer</option>
                        <option value="Giro">Giro</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>

                <div class="mb-2">
                    <label class="form-label">Nominal Bayar</label>
                    <input type="number" id="payAmount" class="form-control" required>
                    <small id="payRemainingInfo" class="text-muted"></small>
                </div>

                <div class="mb-2">
                    <label class="form-label">No Referensi</label>
                    <input type="text" id="payReference" class="form-control" placeholder="Opsional">
                </div>

                <div class="mb-2">
                    <label class="form-label">Catatan</label>
                    <input type="text" id="payNote" class="form-control" placeholder="Opsional">
                </div>

            </div>

            <div class="modal-footer">
                <button class="btn-soft light" data-bs-dismiss="modal">Batal</button>
                <button class="btn-soft" id="btnSubmitPayment">Simpan Pembayaran</button>
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



        // Detail Supplier Modal
        function loadDetailSupplier(supplierId) {
    const from = $("#detailFrom").val();
    const to = $("#detailTo").val();

    $.ajax({
        url: `/laporan/hutang/detail/${supplierId}`,
        method: "GET",
        data: { from, to },
        success: function(res) {
            $("#supplierName").text("Supplier: " + res.supplier.nama_supplier);

            $("#detailSupplierId").val(supplierId);

            let html = "";
            let total = 0;
            let paidSum = 0;

            res.invoices.forEach(inv => {
                total += inv.total;
                paidSum += inv.paid;

                html += `
                    <tr>
                        <td>${inv.tanggal}</td>
                        <td>${inv.invoice}</td>
                        <td>Rp ${inv.total.toLocaleString()}</td>
                        <td>Rp ${inv.paid.toLocaleString()}</td>
                        <td><b>Rp ${inv.remaining.toLocaleString()}</b></td>
                        <td>
                            <button class="btn btn-sm btn-pay" data-id="${inv.id}">
                                <i class="bi bi-cash"></i>
                            </button>



<div class="btn-group">
    <button type="button" class="btn btn-sm dropdown-toggle" data-bs-toggle="dropdown">
        <i class="bi bi-printer"></i> Print
    </button>
    <ul class="dropdown-menu">
        ${inv.payments.map(p => `
            <li>
                <a class="dropdown-item" href="/purchase-payments/${p.id}/print" target="_blank">
                    Pembayaran: Rp ${p.amount.toLocaleString()}
                </a>
            </li>
        `).join('')}
    </ul>
</div>





                        </td>
                    </tr>
                `;
            });

//                       ${inv.payments.map(p => `
//     <a href="/purchase-payments/${p.id}/print"
//         target="_blank"
//         class="btn btn-sm">
//         <i class="bi bi-printer"></i>
//     </a>
// `).join('')}

            $("#detailBody").html(html);

            // Summary
            $("#sumTotal").text("Rp " + total.toLocaleString());
            $("#sumPaid").text("Rp " + paidSum.toLocaleString());
            $("#sumRemaining").text("Rp " + (total - paidSum).toLocaleString());

            const modal = new bootstrap.Modal(document.getElementById('modalDetailSupplier'));
            modal.show();
        }
    });
}
$(document).on("click", ".btn-detail-supplier", function() {
    const supplierId = $(this).data("id");

    $("#detailFrom").val("");
    $("#detailTo").val("");

    loadDetailSupplier(supplierId);
});
$("#filterDetailForm").submit(function(e) {
    e.preventDefault();

    const supplierId = $("#detailSupplierId").val();
    loadDetailSupplier(supplierId);
});
// $(document).on("click", ".btn-pay", function() {
//     const purchaseId = $(this).data("id");

//     // Jika Anda punya modal bayar purchase dengan id: #modalPay
//     $("#paymentPurchaseId").val(purchaseId);

//     $("#modalPay").modal("show");

//     // Optionally: load detail invoice dulu
// });

// --- OPEN PAYMENT MODAL ---
$(document).on("click", ".btn-pay", function () {
    const purchaseId = $(this).data("id");

    $("#paymentPurchaseId").val(purchaseId);

    // Load detail untuk sisa hutang
    $.get(`/purchases/${purchaseId}/detail`, function (res) {
        const p = res.data;

        const totalPaid = p.payments?.reduce((acc, x) => acc + x.amount, 0) || 0;
        const remaining = p.grand_total - totalPaid;

        $("#payRemainingInfo").text(`Sisa hutang: Rp ${remaining.toLocaleString()}`);
        $("#payAmount").attr("max", remaining);
    });

    $("#modalPay").modal("show");
});


// --- SAVE PAYMENT ---
$("#btnSubmitPayment").click(function () {
    const payload = {
        purchase_id: $("#paymentPurchaseId").val(),
        payment_date: $("#payDate").val(),
        payment_method: $("#payMethod").val(),
        amount: $("#payAmount").val(),
        reference: $("#payReference").val(),
        note: $("#payNote").val(),
    };

    $.post("/purchase-payments/store", payload, function (res) {
        Swal.fire("Sukses", "Pembayaran berhasil disimpan", "success");
        $("#modalPay").modal("hide");

        // Reload detail supplier
        const sid = $("#detailSupplierId").val();
        loadDetailSupplier(sid);
    }).fail(function (err) {
        Swal.fire("Error", err.responseJSON.message ?? "Gagal menyimpan pembayaran", "error");
    });
});


    </script>
@endpush
