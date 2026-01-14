@extends('layouts.apps')

@section('content')
<section>

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-start">
        <div>
            <div class="page-header-title">Daftar Transaksi</div>
            <div class="page-header-sub">
                Lihat transaksi penjualan / pembelian dalam sistem POS.
            </div>
        </div>

        <div class="d-flex align-items-center gap-2">
            <button class="btn-soft light" id="btnRefresh">
                <i class="bi bi-arrow-clockwise"></i> Reload
            </button>
        </div>
    </div>

    <!-- FILTER BAR -->
    <div class="filter-bar mt-2">
        <div class="filter-label">Filter</div>

        <!-- Search -->
        <div class="filter-search-wrap">
            <i class="bi bi-search"></i>
            <input type="text" id="searchTrans" placeholder="Cari kode transaksi">
        </div>

        <!-- Status -->
        <div>
            <select id="filterStatus">
                <option value="all">Semua Status</option>
                <option value="paid">Sudah Dibayar</option>
                <option value="unpaid">Hutang</option>
                <option value="void">Dibatalkan</option>
            </select>
        </div>

        <!-- Date From -->
        <div>
            <input type="date" id="dateFrom" class="form-control">
        </div>

        <!-- Date To -->
        <div>
            <input type="date" id="dateTo" class="form-control">
        </div>
    </div>

    <!-- TABLE -->
    <div class="table-wrap">
        <div class="table-responsive">
            <table class="table align-middle" id="transactionTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Kode</th>
                        <th>Customer</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- By JS -->
                </tbody>
            </table>
        </div>

        <div id="emptyState" class="empty-state d-none">
            Tidak ada transaksi ditemukan.
        </div>

        <!-- Pagination -->
        <div class="pagination-container">
            <div id="paginationInfo"></div>
            <nav>
                <ul class="pagination pagination-sm mb-0" id="pagination"></ul>
            </nav>
        </div>
    </div>

</section>

<!-- VIEW MODAL -->
<div class="modal fade" id="transactionModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Transaksi</h5>
                <button class="btn-close" data-bs-dismiss="modal" style="font-size:8px;"></button>
            </div>
            <div class="modal-body" id="transactionDetail">
                <!-- By JS -->
            </div>
            <div class="modal-footer">
                <button class="btn-soft light" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL STRUK CETAK -->
<div class="modal fade" id="printModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content" id="printArea" style="font-size:13px;">
            <div class="modal-header">
                <h6 class="modal-title">Struk Pembayaran</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="text-center mb-2">
                    <strong>{{ Auth::user()->tenant->outlets->first()?->outlet_name ?? '-' }}</strong><br>
                    <span style="font-size:11px">{{ Auth::user()->tenant->outlets->first()?->outlet_address ?? '-' }}</span>
                    <hr>
                </div>

                <div id="printItems"></div>

                <hr>
                <div class="d-flex justify-content-between">
                    <span>Total</span>
                    <span id="p_total"></span>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Bayar</span>
                    <span id="p_pay"></span>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Kembalian</span>
                    <span id="p_change"></span>
                </div>
                <hr>
                <div class="text-center" style="font-size:11px;">Terima kasih 🙏</div>
            </div>

            <div class="modal-footer no-print">
                <button class="btn-soft light btn-sm" data-bs-dismiss="modal">Tutup</button>
                <button class="btn-soft primary btn-sm" onclick="printMini()">Cetak</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL EDIT TRANSAKSI -->
<!-- MODAL EDIT TRANSAKSI -->
{{-- <div class="modal fade" id="editTransactionModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Transaksi</h5>
                <button class="btn-close" data-bs-dismiss="modal" style="font-size:8px;"></button>
            </div>

            <div class="modal-body">
                <input type="hidden" id="editTransactionId">

                <div class="row g-2 mb-2">
                    <div class="col-md-4">
                        <label class="form-label">Customer</label>
                        <select id="editCustomer" class="form-select">
                            <option value="">Umum</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Kode Transaksi</label>
                        <input type="text" id="editKode" class="form-control" readonly>
                    </div>
                </div>

                <hr>

                <!-- ITEM TABLE -->
                <div class="d-flex justify-content-between mb-1">
                    <b>Item Transaksi</b>
                    <button type="button" class="btn-soft light btn-sm" id="btnAddEditItem">
                        <i class="bi bi-plus-circle"></i> Tambah Item
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table table-sm" id="editItemsTable">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th width="80">Qty</th>
                                <th width="150">Harga</th>
                                <th width="150">Subtotal</th>
                                <th width="40">#</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

                <hr>

                <!-- TOTAL SECTION -->
                <div class="row">
                    <div class="col-md-6"></div>
                    <div class="col-md-6">
                        <div class="mt-2">
                            <div class="summary-row">
                                <span>Sub Total</span>
                                <span id="subTotal">Rp 0</span>
                            </div>

                            <div class="summary-row align-items-center">
                                <span>Discount</span>
                                <span class="d-flex align-items-center gap-1">
                                    <input type="number" min="0" step="0.01"
                                           class="form-control form-control-sm text-end discount-input"
                                           id="discountValue" value="0" style="width:90px;">
                                    <select id="discountType" class="form-select form-select-sm discount-input" style="width:70px;">
                                        <option value="rp" selected>Rp</option>
                                        <option value="percent">%</option>
                                    </select>
                                </span>
                            </div>

                            <div class="summary-row">
                                <span>Setelah Discount</span>
                                <strong id="afterDiscount">Rp 0</strong>
                            </div>

                            <div class="summary-row align-items-center">
                                <span>PPN (%)</span>
                                <span>
                                    <input type="number" min="0" step="0.01"
                                           class="form-control form-control-sm text-end ppn-input"
                                           id="ppnValue" value="11" style="width:90px;">
                                </span>
                            </div>

                            <div class="summary-row">
                                <span>Total</span>
                                <strong id="afterPpn">Rp 0</strong>
                            </div>

                            <div class="summary-row align-items-center">
                                <span>Bayar</span>
                                <span>
                                    <input type="number" min="0" step="0.01"
                                           class="form-control form-control-sm text-end pay-input"
                                           id="payAmount" placeholder="0" style="width:120px;">
                                </span>
                            </div>

                            <div class="summary-row">
                                <span>Kembalian</span>
                                <strong id="changeAmount">Rp 0</strong>
                            </div>

                            <div class="summary-row align-items-center">
                                <span>Status Bayar</span>
                                <select id="editStatus" class="form-select form-select-sm">
                                    <option value="paid">Lunas</option>
                                    <option value="unpaid">Hutang</option>
                                    <option value="void">Batal</option>
                                </select>
                            </div>

                            <div class="summary-row align-items-center">
                                <span>Metode Bayar</span>
                                <select id="paymentMethod" class="form-select form-select-sm">
                                    <option value="cash">Cash</option>
                                    <option value="transfer">Transfer</option>
                                    <option value="qris">QRIS</option>
                                    <option value="debit card">Kartu Debit</option>
                                </select>
                            </div>

                            <div class="mt-2">
                                <label style="font-size:12px;font-weight:600;">Catatan</label>
                                <textarea id="orderNote" class="form-control form-control-sm" rows="2" style="font-size:12px; resize:none;"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn-soft light" data-bs-dismiss="modal">Batal</button>
                <button class="btn-soft" id="btnUpdateTransaction">Update Transaksi</button>
            </div>
        </div>
    </div>
</div> --}}


<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="modal fade" id="editTransactionModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Edit Transaksi</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <table class="table table-bordered" id="editItemsTable">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th width="120">Qty</th>
                            <th width="150">Harga</th>
                            <th width="150">Total</th>
                            <th width="80">Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>

                <button class="btn btn-sm btn-primary" id="addItemEdit">Tambah Item</button>

                <hr>

                <div class="row g-2">
                    <div class="col-md-3">
                        <label>Subtotal</label>
                        <input type="number" id="sub_total" class="form-control" readonly>
                    </div>

                    <div class="col-md-3">
                        <label>Diskon</label>
                        <div class="input-group">
                            <input type="number" id="discount_value" class="form-control" value="0">
                            <select id="discount_type" class="form-select">
                                <option value="nominal">Rp</option>
                                <option value="percent">%</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label>PPN (%)</label>
                        <input type="number" id="ppn" class="form-control" value="0">
                    </div>

                    <div class="col-md-3">
                        <label>Grand Total</label>
                        <input type="number" id="grand_total" class="form-control" readonly>
                    </div>

                    <div class="col-md-3">
                        <label>Bayar</label>
                        <input type="number" id="pay_amount" class="form-control" value="0">
                    </div>

                    <div class="col-md-3">
                        <label>Kembalian</label>
                        <input type="number" id="change_amount" class="form-control" readonly>
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-success" id="saveEditTransaction">Simpan</button>
            </div>

        </div>
    </div>
</div>


<style>
@media print {
    body * { visibility: hidden !important; }
    #printArea, #printArea * {
        visibility: visible !important;
    }
    #printArea {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
    }
    .no-print { display: none !important; }
}
</style>




@push('scripts')

<script>

    $(document).on('click', '.btn-edit', function () {
    const transactionId = $(this).data('id');
    editTransaction(transactionId);
});
let editProducts = [];
let editTransactionId = null;

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

/** OPEN EDIT */
function editTransaction(id) {
    editTransactionId = id;
    $('#editItemsTable tbody').html('');

    $.get(`/transaction/${id}/edit`, function(res) {
        editProducts = res.products;

        res.transaction.items.forEach(item => {
            addEditRow(item);
        });

        $('#discount_value').val(res.transaction.discount_value);
        $('#discount_type').val(res.transaction.discount_type);
        $('#ppn').val(res.transaction.ppn);
        $('#pay_amount').val(res.transaction.pay_amount);

        calculateAll();
        $('#editTransactionModal').modal('show');
    });
}

/** ADD ROW */
function addEditRow(item = null) {
    let options = editProducts.map(p =>
        `<option value="${p.id}" ${item && item.product_id === p.id ? 'selected' : ''}>${p.nama}</option>`
    ).join('');

    $('#editItemsTable tbody').append(`
        <tr>
            <td><select class="form-control product_id">${options}</select></td>
            <td><input type="number" class="form-control qty" value="${item ? item.qty : 1}"></td>
            <td><input type="number" class="form-control price" value="${item ? item.price : 0}"></td>
            <td class="row-total">0</td>
            <td><button class="btn btn-danger btn-sm removeRow">X</button></td>
        </tr>
    `);
}

/** AUTO HITUNG */
function calculateAll() {
    let subTotal = 0;

    $('#editItemsTable tbody tr').each(function () {
        let qty = parseFloat($(this).find('.qty').val()) || 0;
        let price = parseFloat($(this).find('.price').val()) || 0;
        let total = qty * price;

        $(this).find('.row-total').text(total);
        subTotal += total;
    });

    $('#sub_total').val(subTotal);

    let discountValue = parseFloat($('#discount_value').val()) || 0;
    let discountType = $('#discount_type').val();
    let discountAmount = discountType === 'percent'
        ? subTotal * (discountValue / 100)
        : discountValue;

    let afterDiscount = Math.max(subTotal - discountAmount, 0);

    let ppnPercent = parseFloat($('#ppn').val()) || 0;
    let ppnAmount = afterDiscount * (ppnPercent / 100);

    let grandTotal = afterDiscount + ppnAmount;
    $('#grand_total').val(grandTotal);

    let pay = parseFloat($('#pay_amount').val()) || 0;
    $('#change_amount').val(Math.max(pay - grandTotal, 0));
}

/** EVENTS */
$(document).on('input change',
    '.qty, .price, #discount_value, #discount_type, #ppn, #pay_amount',
    calculateAll
);

$(document).on('click', '.removeRow', function () {
    $(this).closest('tr').remove();
    calculateAll();
});

$('#addItemEdit').click(function () {
    addEditRow();
});

/** SAVE */
$('#saveEditTransaction').click(function () {
    let items = [];

    $('#editItemsTable tbody tr').each(function () {
        items.push({
            product_id: $(this).find('.product_id').val(),
            qty: $(this).find('.qty').val(),
            price: $(this).find('.price').val(),
        });
    });

    $.ajax({
        url: `/transaction/${editTransactionId}`,
        type: 'PUT',
        data: {
            items: items,
            sub_total: $('#sub_total').val(),
            discount_value: $('#discount_value').val(),
            discount_type: $('#discount_type').val(),
            total_after_discount: $('#grand_total').val(),
            ppn: $('#ppn').val(),
            total_after_ppn: $('#grand_total').val(),
            pay_amount: $('#pay_amount').val(),
            change_amount: $('#change_amount').val(),
        },
        success: function (res) {
            alert(res.message);
            location.reload();
        }
    });
});
</script>




<script>
    function printMini() {
    window.print();
}
let isInitEdit = false;

    let pagination = {
        perPage: 10,
        currentPage: 1,
    };

    let transactionModal;

    /* =====================================================
     * LOAD DATA
     * ===================================================== */
    function loadTransactions() {
        $.ajax({
            url: "{{ route('transactions.data') }}",
            method: "GET",
            data: {
                search: $("#searchTrans").val(),
                status: $("#filterStatus").val(),
                date_from: $("#dateFrom").val(),
                date_to: $("#dateTo").val(),
                page: pagination.currentPage,
            },
            success: function(res) {
                renderTable(res.data);
                renderPagination(res);
            }
        });
    }

    /* =====================================================
     * RENDER TABLE
     * ===================================================== */
    function renderTable(data) {
        let tbody = $("#transactionTable tbody");
        tbody.empty();

        if (data.length === 0) {
            $("#emptyState").removeClass("d-none");
            return;
        }
        $("#emptyState").addClass("d-none");

        data.forEach((t, i) => {
            const badge = {
                paid: '<span class="badge-status badge-active">Lunas</span>',
                unpaid: '<span class="badge-status badge-nonactive">Hutang</span>',
                void: '<span class="badge-status badge-nonactive">Batal</span>',
            }[t.status] || "-";

            tbody.append(`
                <tr>
                    <td>${i + 1}</td>
                    <td><strong>${t.kode}</strong></td>
                    <td>${t.customer ? t.customer.nama : 'Umum'}</td>
                    <td>${t.created_at}</td>
                    <td>Rp ${parseInt(t.sub_total).toLocaleString()}</td>
                    <td>${badge}</td>
                    <td class="text-end">
                        <button class="action-btn" data-action="view" data-id="${t.id}">
                            <i class="bi bi-eye-fill"></i>
                        </button>
                        <button class="action-btn" data-action="print" data-id="${t.id}">
    <i class="bi bi-printer-fill"></i>
</button>
<button class="action-btn btn-edit" data-id="${t.id}"><i class="bi bi-pencil"></i></button>
                    </td>
                </tr>
            `);
        });
    }

    /* =====================================================
     * PAGINATION
     * ===================================================== */
    function renderPagination(res) {
        const pag = $("#pagination");
        const info = $("#paginationInfo");

        pag.empty();

        info.text(
            `Menampilkan ${res.from} - ${res.to} dari ${res.total} data (Hal ${res.current_page} / ${res.last_page})`
        );

        pag.append(`
            <li class="page-item ${res.current_page == 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="${res.current_page - 1}">«</a>
            </li>
        `);

        for (let p = 1; p <= res.last_page; p++) {
            pag.append(`
                <li class="page-item ${p == res.current_page ? 'active' : ''}">
                    <a class="page-link" href="#" data-page="${p}">${p}</a>
                </li>
            `);
        }

        pag.append(`
            <li class="page-item ${res.current_page == res.last_page ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="${res.current_page + 1}">»</a>
            </li>
        `);
    }

    /* =====================================================
     * VIEW DETAIL
     * ===================================================== */
    function openDetail(id) {
        $.get(`/transactions/${id}`, function(t) {

            let items = '';
            t.items.forEach((i, idx) => {
                items += `
                    <tr>
                        <td>${idx+1}</td>
                        <td>${i.product.nama}</td>
                        <td>${i.qty}</td>
                        <td>Rp ${parseInt(i.price).toLocaleString()}</td>
                    </tr>
                `;
            });
            $("#transactionDetail").html(`
    <div class="mb-2">
        <div><b>Customer</b>: ${t.customer ? t.customer.nama : 'Umum'}</div>
        <div><b>Kode</b>: ${t.kode}</div>
        <div><b>Tanggal</b>: ${t.created_at}</div>
        <div><b>Status</b>: ${t.status}</div>
    </div>

    <hr>

    <h6 class="fw-bold mt-2">Detail Item</h6>

    <div class="table-responsive mt-2 mb-3">
        <table class="table table-sm align-middle">
            <thead class="table-light">
                <tr>
                    <th style="width: 40px;">#</th>
                    <th>Produk</th>
                    <th style="width: 60px;">Qty</th>
                    <th style="width: 120px;">Subtotal</th>
                </tr>
            </thead>
            <tbody>${items}</tbody>
        </table>
    </div>

   <hr>

<div class="mt-2">

    <div class="d-flex justify-content-between mb-1">
        <span><b>Subtotal</b></span>
        <span>Rp ${parseInt(t.sub_total).toLocaleString()}</span>
    </div>

    <div class="d-flex justify-content-between mb-1">
        <span><b>Discount</b></span>
        <span>Rp ${parseInt(t.discount_value).toLocaleString()} (${t.discount_type})</span>
    </div>

    <div class="d-flex justify-content-between mb-1">
        <span><b>Setelah Diskon</b></span>
        <span>Rp ${parseInt(t.total_after_discount).toLocaleString()}</span>
    </div>

    <div class="d-flex justify-content-between mb-1">
        <span><b>PPN</b></span>
        <span>Rp ${parseInt(t.ppn).toLocaleString()}</span>
    </div>

    <div class="d-flex justify-content-between mb-1">
        <span><b>Total</b></span>
        <span>Rp ${parseInt(t.total_after_ppn).toLocaleString()}</span>
    </div>

    <div class="d-flex justify-content-between mb-1">
        <span><b>Bayar</b></span>
        <span>Rp ${parseInt(t.pay_amount).toLocaleString()}</span>
    </div>

    <div class="d-flex justify-content-between mb-1">
        <span><b>Kembalian</b></span>
        <span>Rp ${parseInt(t.change_amount).toLocaleString()}</span>
    </div>

</div>

`);


            transactionModal.show();
        });
    }

    /* =====================================================
     * DOCUMENT READY
     * ===================================================== */
    $(function() {

        transactionModal = new bootstrap.Modal(document.getElementById("transactionModal"));

        loadTransactions();

        $("#searchTrans, #filterStatus, #dateFrom, #dateTo").on("input change", function() {
            pagination.currentPage = 1;
            loadTransactions();
        });

        $("#pagination").on("click", ".page-link", function(e) {
            e.preventDefault();
            let page = $(this).data("page");
            if (page) {
                pagination.currentPage = page;
                loadTransactions();
            }
        });

       $("#transactionTable").on("click", "[data-action='view']", function () {
            const id = $(this).data("id");
            openDetail(id);
        });

        $("#btnRefresh").on("click", function() {
            $("#searchTrans").val("");
            $("#filterStatus").val("all");
            $("#dateFrom").val("");
            $("#dateTo").val("");
            pagination.currentPage = 1;
            loadTransactions();
        });

    });
    // PRINT STRUK
$("#transactionTable").on("click", "[data-action='print']", function () {
    const id = $(this).data("id");

    $.ajax({
        url: "/transactions/" + id, // sesuaikan route
        method: "GET",
        success: function (res) {

            // Item struk
            let html = '';
            html += `
                <div class="d-flex justify-content-between">
                    <span><strong>Customer</strong></span
                    <span>${res.customer ? res.customer.nama : 'Umum'}</span>
                </div>
                  <div class="d-flex justify-content-between">
                    <span><strong>Kode</strong></span>
                    <span>${res.kode}</span>
                </div>
                 <div class="d-flex justify-content-between">
                    <span><strong>Tanggal</strong></span>
                    <span>${res.created_at}</span>
                </div>
                <hr>
            `;
            res.items.forEach(it => {
                html += `
                    <div class="d-flex justify-content-between">
                        <span>${it.product.nama} x${it.qty}</span>
                        <span>Rp ${Number(it.price * it.qty).toLocaleString()}</span>
                    </div>
                `;
            });

            $("#printItems").html(html);

            // Summary
            $("#p_total").text("Rp " + Number(res.total_after_ppn).toLocaleString());
            $("#p_pay").text("Rp " + Number(res.pay_amount).toLocaleString());
            $("#p_change").text("Rp " + Number(res.change_amount).toLocaleString());

            // Tampilkan modal struk
            new bootstrap.Modal("#printModal").show();
        }
    });
});




/* =====================================================
 * EDIT TRANSAKSI - FUNGSI UTAMA
 * ===================================================== */
/* =====================================================
 * EDIT TRANSAKSI - FUNGSI UTAMA
 * ===================================================== */

let editModal;
let editTransactionData = {};
let products = []; // Kosongkan array, akan diisi via AJAX

// Fungsi untuk load produk dari API
function loadProducts(callback = null) {
    $.ajax({
        url: '{{ route("select.product") }}', // Pastikan route ini ada
        method: 'GET',
        success: function(data) {
            products = data;
            console.log('Products loaded successfully:', products.length);
            if (callback && typeof callback === 'function') {
                callback();
            }
        },
        error: function(err) {
            console.error('Failed to load products:', err);
            // Fallback: coba load dari variabel Blade jika ada
            try {
                products = @json($products ?? []);
                console.log('Using fallback products:', products.length);
            } catch (e) {
                console.error('Fallback also failed:', e);
                products = [];
            }
        }
    });
}

// Fungsi untuk membuat options produk
function buildProductOptions(selectedId = null) {
    let options = '<option value="">Pilih Produk</option>';

    if (products && products.length > 0) {
        products.forEach(product => {
            const selected = selectedId == product.id ? 'selected' : '';
            const price = product.harga_jual || product.harga_modal || 0;
            options += `<option value="${product.id}" data-price="${price}" ${selected}>${product.nama}</option>`;
        });
    } else {
        options = '<option value="">Produk tidak tersedia</option>';
    }

    return options;
}

$(function () {
    editModal = new bootstrap.Modal(document.getElementById('editTransactionModal'));

    // Pre-load produk saat halaman siap
    loadProducts();
});

// Fungsi untuk membuka modal edit
function openEditModal(transactionId) {
    // Tampilkan loading indicator
    $('#editItemsTable tbody').html('<tr><td colspan="5" class="text-center py-3"><div class="spinner-border spinner-border-sm text-primary"></div> Memuat...</td></tr>');

    // Reset data
    editTransactionData = {};

    // Reset form
    $('#editTransactionId').val('');
    $('#editCustomer').val('');
    $('#editKode').val('');
    $('#orderNote').val('');
    $('#payAmount').val(0);
    $('#discountValue').val(0);
    $('#discountType').val('rp');
    $('#ppnValue').val(11);
    $('#editStatus').val('paid');
    $('#paymentMethod').val('cash');

    // Reset tampilan total
    updateSummaryDisplay(0, 0, 0, 0, 0);

    // Tampilkan modal
    editModal.show();

    // Pastikan produk sudah diload sebelum melanjutkan
    if (products.length === 0) {
        loadProducts(function() {
            loadTransactionDetails(transactionId);
        });
    } else {
        loadTransactionDetails(transactionId);
    }
}

// Fungsi untuk load detail transaksi setelah produk tersedia
function loadTransactionDetails(transactionId) {
    $.ajax({
        url: `/transactions/${transactionId}`,
        method: 'GET',
        success: function(res) {
            console.log('Transaction data loaded:', res);

            // Simpan data untuk referensi
            editTransactionData = res;

            // Isi form
            $('#editTransactionId').val(res.id);
            $('#editKode').val(res.kode);
            $('#editCustomer').val(res.customer_id || '');
            $('#editStatus').val(res.status);
            $('#paymentMethod').val(res.payment_method || 'cash');
            $('#orderNote').val(res.note || '');

            // Isi discount dan PPN
            $('#discountValue').val(res.discount_value || 0);
            $('#discountType').val(res.discount_type || 'rp');
            $('#ppnValue').val(res.ppn || 0);
            $('#payAmount').val(res.pay_amount || 0);

            // Clear table
            $('#editItemsTable tbody').empty();

            // Tambahkan item ke tabel
            if (res.items && res.items.length > 0) {
                res.items.forEach(item => {
                    addItemToTable(item);
                });
            } else {
                // Tambahkan satu baris kosong jika tidak ada item
                addItemToTable();
            }

            // Hitung ulang total
            calculateTotals();
        },
        error: function(err) {
            console.error('Error loading transaction:', err);
            alert('Gagal memuat data transaksi');
            editModal.hide();
        }
    });
}

// Fungsi untuk menambahkan item ke tabel
function addItemToTable(item = null) {
    const rowId = 'item-' + Date.now() + '-' + Math.random().toString(36).substr(2, 9);

    // Jika item sudah ada, update qty
    if (item && item.product_id) {
        let existingRow = null;
        $('#editItemsTable tbody tr').each(function() {
            if ($(this).data('product-id') == item.product_id) {
                existingRow = $(this);
                return false; // Break loop
            }
        });

        if (existingRow) {
            // Update qty jika item sudah ada
            const qtyInput = existingRow.find('.item-qty');
            const newQty = parseInt(qtyInput.val()) + parseInt(item.qty);
            qtyInput.val(newQty);
            calculateRow(existingRow);
            return;
        }
    }

    // Buat options produk
    const productOptions = buildProductOptions(item ? item.product_id : null);

    // Buat row baru
    const row = `
    <tr data-row-id="${rowId}" data-product-id="${item ? item.product_id : ''}">
        <td>
            <select class="form-select form-select-sm item-product" required>
                ${productOptions}
            </select>
        </td>
        <td>
            <input type="number" class="form-control form-control-sm item-qty"
                   min="1" value="${item ? item.qty : 1}" step="1">
        </td>
        <td>
            <input type="number" class="form-control form-control-sm item-price"
                   min="0" value="${item ? item.price : 0}" step="100">
        </td>
        <td class="item-subtotal text-end fw-bold">Rp 0</td>
        <td>
            <button type="button" class="btn btn-sm btn-danger btn-remove-item">
                <i class="bi bi-trash"></i>
            </button>
        </td>
    </tr>`;

    $('#editItemsTable tbody').append(row);

    const rowElement = $(`[data-row-id="${rowId}"]`);

    // Jika ada data item, set produk terpilih
    if (item && item.product_id) {
        rowElement.data('product-id', item.product_id);

        // Set harga otomatis berdasarkan produk yang dipilih
        const selectedProduct = products.find(p => p.id == item.product_id);
        if (selectedProduct) {
            const price = item.price || selectedProduct.harga_jual || selectedProduct.harga_modal || 0;
            rowElement.find('.item-price').val(price);
        }
    }

    // Hitung subtotal untuk row ini
    setTimeout(() => {
        calculateRow(rowElement);
    }, 100);
}

// Fungsi untuk menghitung subtotal per row
function calculateRow(row) {
    const qty = parseFloat(row.find('.item-qty').val()) || 0;
    const price = parseFloat(row.find('.item-price').val()) || 0;
    const subtotal = qty * price;

    row.find('.item-subtotal').text(formatRupiah(subtotal));

    // Hitung ulang total keseluruhan
    calculateTotals();
}

// Fungsi untuk menghitung total keseluruhan
function calculateTotals() {
    let subTotal = 0;

    // Hitung subtotal dari semua item
    $('#editItemsTable tbody tr').each(function() {
        const qty = parseFloat($(this).find('.item-qty').val()) || 0;
        const price = parseFloat($(this).find('.item-price').val()) || 0;
        subTotal += qty * price;
    });

    // Hitung discount
    const discountValue = parseFloat($('#discountValue').val()) || 0;
    const discountType = $('#discountType').val();
    let discountAmount = 0;

    if (discountType === 'percent') {
        discountAmount = subTotal * (discountValue / 100);
    } else {
        discountAmount = discountValue;
    }

    const afterDiscount = Math.max(0, subTotal - discountAmount);

    // Hitung PPN
    const ppnPercent = parseFloat($('#ppnValue').val()) || 0;
    const ppnAmount = afterDiscount * (ppnPercent / 100);
    const total = afterDiscount + ppnAmount;

    // Hitung kembalian
    const payAmount = parseFloat($('#payAmount').val()) || 0;
    const changeAmount = Math.max(0, payAmount - total);

    // Update tampilan
    updateSummaryDisplay(subTotal, discountAmount, afterDiscount, total, changeAmount);

    // Auto update status berdasarkan pembayaran
    updatePaymentStatus(total, payAmount);
}

// Fungsi untuk update tampilan summary
function updateSummaryDisplay(subTotal, discountAmount, afterDiscount, total, changeAmount) {
    $('#subTotal').text(formatRupiah(subTotal));
    $('#afterDiscount').text(formatRupiah(afterDiscount));
    $('#afterPpn').text(formatRupiah(total));
    $('#changeAmount').text(formatRupiah(changeAmount));
}

// Fungsi untuk update status pembayaran otomatis
function updatePaymentStatus(total, payAmount) {
    if (payAmount >= total) {
        $('#editStatus').val('paid');
    } else if (payAmount > 0) {
        $('#editStatus').val('unpaid');
    } else {
        $('#editStatus').val('unpaid');
    }
}

// Fungsi format rupiah
function formatRupiah(number) {
    if (isNaN(number)) number = 0;
    return 'Rp ' + Math.round(number).toLocaleString('id-ID');
}

// Fungsi untuk mengumpulkan data item untuk submit
function getItemsData() {
    const items = [];

    $('#editItemsTable tbody tr').each(function() {
        const productId = $(this).find('.item-product').val();
        const qty = parseFloat($(this).find('.item-qty').val()) || 0;
        const price = parseFloat($(this).find('.item-price').val()) || 0;

        if (productId && qty > 0 && price > 0) {
            items.push({
                product_id: productId,
                qty: qty,
                price: price
            });
        }
    });

    return items;
}

/* =====================================================
 * EVENT HANDLERS
 * ===================================================== */

// Event handler untuk menambah item
$('#btnAddEditItem').on('click', function() {
    addItemToTable();
});

// Event handler untuk perubahan pada item
$(document).on('input change', '.item-qty, .item-price', function() {
    calculateRow($(this).closest('tr'));
});

// Event handler untuk perubahan produk
$(document).on('change', '.item-product', function() {
    const row = $(this).closest('tr');
    const selectedOption = $(this).find('option:selected');
    const price = parseFloat(selectedOption.data('price')) || 0;

    // Set harga otomatis ketika produk dipilih
    if (price > 0) {
        row.find('.item-price').val(price);
    }

    row.data('product-id', $(this).val());

    calculateRow(row);
});

// Event handler untuk menghapus item
$(document).on('click', '.btn-remove-item', function() {
    $(this).closest('tr').remove();
    calculateTotals();
});

// Event handler untuk perubahan discount, PPN, dan pembayaran
$(document).on('input change', '.discount-input, .ppn-input, .pay-input', function() {
    calculateTotals();
});

// Event handler untuk submit update transaksi
$('#btnUpdateTransaction').on('click', function() {
    const items = getItemsData();

    if (items.length === 0) {
        alert('Minimal 1 item harus diisi');
        return;
    }

    const transactionId = $('#editTransactionId').val();
    const subTotal = parseFloat($('#subTotal').text().replace(/[^\d]/g, '')) || 0;
    const total = parseFloat($('#afterPpn').text().replace(/[^\d]/g, '')) || 0;
    const payAmount = parseFloat($('#payAmount').val()) || 0;

    // Validasi pembayaran untuk status lunas
    if ($('#editStatus').val() === 'paid' && payAmount < total) {
        if (!confirm('Pembayaran kurang dari total. Tetap lanjutkan dengan status Lunas?')) {
            return;
        }
    }

    // Prepare data untuk submit
    const data = {
        customer_id: $('#editCustomer').val() || null,
        items: items,
        sub_total: subTotal,
        discount_value: $('#discountValue').val(),
        discount_type: $('#discountType').val(),
        total_after_discount: parseFloat($('#afterDiscount').text().replace(/[^\d]/g, '')) || 0,
        ppn: $('#ppnValue').val(),
        total_after_ppn: total,
        pay_amount: payAmount,
        change_amount: parseFloat($('#changeAmount').text().replace(/[^\d]/g, '')) || 0,
        status: $('#editStatus').val(),
        payment_method: $('#paymentMethod').val(),
        note: $('#orderNote').val(),
        _token: '{{ csrf_token() }}',
        _method: 'PUT'
    };

    console.log('Updating transaction:', data);

    // Submit update
    $.ajax({
        url: `/transactions/${transactionId}`,
        method: 'POST', // Menggunakan POST karena ada _method: 'PUT'
        data: data,
        success: function(response) {
            if (response.success) {
                alert('Transaksi berhasil diperbarui');
                editModal.hide();
                loadTransactions(); // Reload tabel transaksi
            } else {
                alert('Gagal update transaksi: ' + (response.message || 'Unknown error'));
            }
        },
        error: function(xhr) {
            console.error('Error updating transaction:', xhr.responseText);
            const errorMsg = xhr.responseJSON?.message || 'Terjadi kesalahan saat update transaksi';
            alert('Gagal update transaksi: ' + errorMsg);
        }
    });
});

// Event handler untuk membuka modal edit dari tabel
// $('#transactionTable').on('click', '.btn-edit', function() {
//     const id = $(this).data('id');
//     openEditModal(id);
// });
</script>


@endpush

@endsection
