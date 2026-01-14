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
<div class="modal fade" id="editTransactionModal" tabindex="-1">
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
                    <button class="btn-soft light btn-sm" id="btnAddEditItem">
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

                <!-- TOTAL -->

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
                    <input type="number" min="0" class="form-control form-control-sm text-end"
                           id="discountValue" value="0" style="width:90px;">
                    <select id="discountType" class="form-select form-select-sm" style="width:70px;">
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
                    <input type="number" min="0" class="form-control form-control-sm text-end"
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
                    <input type="number" min="0" class="form-control form-control-sm text-end"
                           id="payAmount" placeholder="0" style="width:120px;">
                </span>
            </div>

            <div class="summary-row">
                <span>Kembalian</span>
                <strong id="changeAmount">Rp 0</strong>
            </div>

              <div class="summary-row align-items-center">
                <span>Status Bayar</span>
               <select id="editStatus" class="form-select form-select-sm" style="width:70px;">
                        <option value="paid" >Lunas</option>
                        <option value="unpaid">Hutang</option>
                        <option value="void">Batal</option>
                    </select>
            </div>

             <div class="summary-row align-items-center">
                <span>Metode Bayar</span>
               <select id="paymentMethod" class="form-select form-select-sm" style="width:70px;">
                        <option value="cash">Cash</option>
                        <option value="transfer">Transfer</option>
                        <option value="qris">QRIS</option>
                        <option value="debit card">Kartu Debit</option>
                    </select>
            </div>
  <div class="summary-row">
                <span>Total</span>
               <strong id="chargeAmount">Rp 0</strong>
            </div>

<div class="mt-2">
    <label style="font-size:12px;font-weight:600;">Catatan</label>
    <textarea
        id="orderNote"
        class="form-control form-control-sm"
        rows="2"
        style="font-size:12px; resize:none;"
    ></textarea>
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


    $(document).ready(function () {
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



let editModal = new bootstrap.Modal(document.getElementById("editTransactionModal"));
let products = @json($products ?? []);

/* ============================
   OPEN EDIT MODAL
============================ */
$("#transactionTable").on("click", ".btn-edit", function () {
    const id = $(this).data("id");

    $.get(`/transactions/${id}`, function (res) {
        $("#editTransactionId").val(res.id);
        $("#editStatus").val(res.status);
        $("#editCustomer").val(res.customer_id);
$("#editKode").val(res.kode);


$("#paymentMethod").val(res.payment_method);
$("#orderNote").val(res.note);

  isInitEdit = true;

    $("#editItemsTable tbody").empty();

    res.items.forEach(i => {
        addEditItemRow({
            product_id: i.product_id,
            product_name: i.product.nama,
            qty: i.qty,
            price: i.price,
            total: i.qty * i.price
        });
    });

    isInitEdit = false;
    calcEditTotals();
    editModal.show();
    });
});
///
/* ============================
   ADD ITEM ROW
============================ */

function addEditItemRow(data = {}) {

    let productOptions = `<option value="">Pilih Produk</option>`;
    products.forEach(p => {
        productOptions += `<option value="${p.id}">${p.nama}</option>`;
    });

    let row = $(`
        <tr>
            <td>
                <input type="hidden" class="edit-product" value="${data.product_id ?? ''}">

                <span class="product-name">
                    ${data.product_name ?? '<i class="text-muted">Pilih produk</i>'}
                </span>

                <select class="form-select form-select-sm edit-product-select d-none">
                    ${productOptions}
                </select>

                <button class="btn btn-xs text-primary btn-change-product">
                    <i class="bi bi-pencil"></i>
                </button>
            </td>

            <td>
                <input type="number" class="form-control form-control-sm edit-qty" value="${data.qty ?? 1}">
            </td>

            <td>
                <input type="number" class="form-control form-control-sm edit-price" value="${data.price ?? 0}" readonly>
            </td>

            <td class="edit-subtotal">Rp ${data.total ?? 0}</td>

            <td>
                <button class="btn btn-sm text-danger btn-remove">X</button>
            </td>
        </tr>
    `);

    $("#editItemsTable tbody").append(row);

    if (data.product_id) {
        row.find(".edit-product-select").val(data.product_id);
    }

    updateEditRow(row);
}

$("#editItemsTable").on("click", ".btn-change-product", function () {
    let row = $(this).closest("tr");

    row.find(".product-name").addClass("d-none");
    row.find(".edit-product-select").removeClass("d-none").focus();
});

$("#editItemsTable").on("change", ".edit-product-select", function () {

    let row = $(this).closest("tr");
    let productId = $(this).val();

    let product = products.find(p => p.id == productId);
    if (!product) return;

    // 🔥 RESET STATE LAMA
    row.find(".edit-product").val(product.id);
    row.find(".edit-qty").val(1);          // RESET QTY
    row.find(".edit-price").val(product.harga_jual);

    row.find(".product-name")
        .text(product.nama)
        .removeClass("d-none");

    row.find(".edit-product-select").addClass("d-none");

    updateEditRow(row);
    calcEditTotals();
});


$("#btnAddEditItem").click(() => {
    addEditItemRow({});
    let row = $("#editItemsTable tbody tr:last");
    row.find(".btn-change-product").click();
});



/* ============================
   UPDATE ROW
============================ */
function updateEditRow(row) {

    let productId = row.find(".edit-product").val();
    let qty = parseFloat(row.find(".edit-qty").val()) || 0;
    let price = parseFloat(row.find(".edit-price").val()) || 0;

    // ⛔ ROW INVALID → SUBTOTAL 0
    if (!productId || qty <= 0) {
        row.find(".edit-subtotal").text("Rp 0");
        return;
    }

    let subtotal = qty * price;
    row.find(".edit-subtotal").text("Rp " + subtotal.toLocaleString());
}

/* ============================
   TOTAL
============================ */

function calcEditTotals() {

    let subTotal = 0;

    $("#editItemsTable tbody tr").each(function () {

        let productId = $(this).find(".edit-product").val(); // 🔥 KUNCI
        let qty   = parseFloat($(this).find(".edit-qty").val()) || 0;
        let price = parseFloat($(this).find(".edit-price").val()) || 0;

        // ⛔ JANGAN HITUNG ROW TIDAK VALID
        if (!productId || qty <= 0) return;

        subTotal += qty * price;
    });

    let discountValue = parseFloat($("#discountValue").val()) || 0;
    let discountType  = $("#discountType").val();
    let ppnPercent    = parseFloat($("#ppnValue").val()) || 0;
    let payAmount     = parseFloat($("#payAmount").val()) || 0;

    let discountAmount = discountType === "percent"
        ? subTotal * (discountValue / 100)
        : discountValue;

    if (discountAmount > subTotal) discountAmount = subTotal;

    let afterDiscount = subTotal - discountAmount;
    let ppnAmount = afterDiscount * (ppnPercent / 100);
    let grandTotal = afterDiscount + ppnAmount;
    let changeAmount = payAmount - grandTotal;

    $("#subTotal").text("Rp " + subTotal.toLocaleString());
    $("#afterDiscount").text("Rp " + afterDiscount.toLocaleString());
    $("#afterPpn").text("Rp " + grandTotal.toLocaleString());
    $("#chargeAmount").text("Rp " + grandTotal.toLocaleString());
    $("#changeAmount").text(
        "Rp " + (changeAmount > 0 ? changeAmount : 0).toLocaleString()
    );

    $("#editTransactionModal").data({
        sub_total: subTotal,
        discount_value: discountAmount,
        discount_type: discountType,
        total_after_discount: afterDiscount,
        ppn: ppnAmount,
        total_after_ppn: grandTotal,
        pay_amount: payAmount,
        change_amount: changeAmount > 0 ? changeAmount : 0
    });
}



$("#discountValue, #discountType, #ppnValue, #payAmount")
    .on("input change", function () {
        calcEditTotals();
    });




/* ============================
   EVENTS
============================ */
// $("#btnAddEditItem").click(() => addEditItemRow());

$("#editItemsTable")
   .on("input", ".edit-qty, .edit-price", function () {
    if (isInitEdit) return;
    updateEditRow($(this).closest("tr"));
    calcEditTotals();
})
    .on("click", ".btn-remove", function () {
       const row = $(this).closest("tr");

    // 🔥 pastikan tidak ada value tersisa
    row.find("input").val("");

    // 🔥 HAPUS DULU
    row.remove();

    // 🔥 BARU HITUNG ULANG
    calcEditTotals();
    });


$("#payAmount").on("input", function () {
    let pay = parseFloat($(this).val()) || 0;
    let total = $("#editTransactionModal").data("total_after_ppn") || 0;

    if (pay < total) {
        $("#paymentWarning").remove();
        $(this).after(`
            <small id="paymentWarning" class="text-danger">
                ⚠ Pembayaran kurang
            </small>
        `);
    } else {
        $("#paymentWarning").remove();
    }
});


$("#btnUpdateTransaction").click(function () {
    const id = $("#editTransactionId").val();
    let modalData = $("#editTransactionModal").data();

    let payload = {
        customer_id: $("#editCustomer").val(),
        status: $("#editStatus").val(),
        payment_method: $("#paymentMethod").val(),
        note: $("#orderNote").val(),

        sub_total: modalData.sub_total,
        discount_value: modalData.discount_value,
        discount_type: modalData.discount_type,
        total_after_discount: modalData.total_after_discount,
        ppn: modalData.ppn,
        total_after_ppn: modalData.total_after_ppn,
        pay_amount: modalData.pay_amount,
        change_amount: modalData.change_amount,
        items: []
    };
    payload.items = [];

$("#editItemsTable tbody tr").each(function () {

    let productId = $(this).find(".edit-product").val();
    let qty   = parseFloat($(this).find(".edit-qty").val()) || 0;
    let price = parseFloat($(this).find(".edit-price").val()) || 0;

    // ⛔ SKIP jika row sudah tidak valid
    if (!productId || qty <= 0) return;

    payload.items.push({
        product_id: productId,
        qty: qty,
        price: price
    });
});


    $.ajax({
        url: `/transactions/${id}`,
        method: "PUT",
        data: payload,
        success: function () {
            Swal.fire("Sukses", "Transaksi berhasil diperbarui", "success");
            editModal.hide();
           loadTransactions();
        },
        error: function (err) {
            Swal.fire("Error", err.responseJSON?.message ?? "Gagal update", "error");
        }
    });
});


});
</script>

@endpush

@endsection
