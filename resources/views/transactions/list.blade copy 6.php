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




//edit transaksi
 let products = @json($products ?? []);



$("#transactionTable").on("click", ".btn-edit", function () {
    const id = $(this).data("id");
    openEditModal(id);
});
let editModal;

$(function () {
    editModal = new bootstrap.Modal(
        document.getElementById('editTransactionModal')
    );
});

// Ganti fungsi openEditModal dengan yang ini
function openEditModal(transactionId) {
    // RESET TOTAL & CLEAR TABLE
    $('#editItemsTable tbody').empty();

    // RESET INPUT
    $('#editTransactionId').val('');
    $('#editCustomer').val('');
    $('#editKode').val('');
    $('#orderNote').val('');
    $('#payAmount').val(0);
    $('#discountValue').val(0);
    $('#discountType').val('rp');
    $('#ppnValue').val(11);

    // Reset summary display
    $('#subTotal').text('Rp 0');
    $('#afterDiscount').text('Rp 0');
    $('#afterPpn').text('Rp 0');
    $('#chargeAmount').text('Rp 0');
    $('#changeAmount').text('Rp 0');

    // Tampilkan modal dulu
    editModal.show();

    // Fetch data transaksi
    fetch(`/transactions/${transactionId}`)
        .then(res => res.json())
        .then(trx => {
            console.log('Transaction data:', trx); // Debug

            // Set form values
            $('#editTransactionId').val(trx.id);
            $('#editKode').val(trx.kode);
            $('#editCustomer').val(trx.customer_id || '');
            $('#editStatus').val(trx.status);
            $('#paymentMethod').val(trx.payment_method || 'cash');
            $('#orderNote').val(trx.note || '');

            // Set discount & PPN
            $('#discountValue').val(trx.discount_value || 0);
            $('#discountType').val(trx.discount_type || 'rp');
            $('#ppnValue').val(trx.ppn || 0);
            $('#payAmount').val(trx.pay_amount || 0);

            // Tambahkan items
            if (trx.items && trx.items.length > 0) {
                trx.items.forEach(item => {
                    addEditItemRow(item);
                });
            }

            // Hitung ulang summary
            calculateEditSummary();
        })
        .catch(err => {
            console.error('Error loading transaction:', err);
            alert('Gagal memuat data transaksi');
            editModal.hide();
        });
}

// Ganti fungsi addEditItemRow dengan yang ini
function addEditItemRow(item = null) {
    let rowId = Date.now() + Math.random();

    // Jika item sudah ada, skip
    if (item) {
        let exists = false;
        $('#editItemsTable tbody tr').each(function () {
            if ($(this).find('.product-select').val() == item.product_id) {
                exists = true;
                return false; // break loop
            }
        });
        if (exists) return;
    }

    // Build options untuk product select
    let productOptions = '<option value="">Pilih Produk</option>';
    @foreach($products as $p)
        productOptions += `<option value="{{ $p->id }}" data-price="{{ $p->harga_jual }}">{{ $p->nama }}</option>`;
    @endforeach

    let row = `
    <tr data-id="${rowId}">
        <td>
            <select class="form-select form-select-sm product-select">
                ${productOptions}
            </select>
        </td>
        <td>
            <input type="number" class="form-control form-control-sm qty" min="1" value="1">
        </td>
        <td>
            <input type="number" class="form-control form-control-sm price" min="0" value="0">
        </td>
        <td class="subtotal text-end fw-bold">Rp 0</td>
        <td>
            <button type="button" class="btn btn-sm btn-danger btn-remove">×</button>
        </td>
    </tr>`;

    $('#editItemsTable tbody').append(row);

    let tr = $('#editItemsTable tbody tr[data-id="' + rowId + '"]');

    // Jika ada data item, isi nilai-nilainya
    if (item) {
        tr.find('.product-select').val(item.product_id);
        tr.find('.qty').val(item.qty);
        tr.find('.price').val(item.price);

        console.log('Added item:', item); // Debug
    }

    // Hitung row
    calculateRow(tr);
}

// Ganti fungsi calculateRow dengan yang ini
function calculateRow(tr) {
    let qty = parseFloat(tr.find('.qty').val()) || 0;
    let price = parseFloat(tr.find('.price').val()) || 0;
    let subtotal = qty * price;

    tr.find('.subtotal').text(formatRupiah(subtotal));

    calculateEditSummary();
}


// Ganti fungsi calculateEditSummary dengan yang ini
function calculateEditSummary() {
    let subTotal = 0;

    $('#editItemsTable tbody tr').each(function () {
        let qty = parseFloat($(this).find('.qty').val()) || 0;
        let price = parseFloat($(this).find('.price').val()) || 0;
        subTotal += qty * price;
    });

    let discountVal = parseFloat($('#discountValue').val()) || 0;
    let discountType = $('#discountType').val();

    let discount = discountType === 'percent'
        ? subTotal * (discountVal / 100)
        : discountVal;

    let afterDiscount = subTotal - discount;

    let ppnPercent = parseFloat($('#ppnValue').val()) || 0;
    let ppnAmount = afterDiscount * (ppnPercent / 100);
    let total = afterDiscount + ppnAmount;

    let pay = parseFloat($('#payAmount').val()) || 0;
    let change = pay - total;

    $('#subTotal').text(formatRupiah(subTotal));
    $('#afterDiscount').text(formatRupiah(afterDiscount));
    $('#afterPpn').text(formatRupiah(total));
    $('#chargeAmount').text(formatRupiah(total));
    $('#changeAmount').text(formatRupiah(change > 0 ? change : 0));
}


// Event handlers - pastikan ini ada
$(document).on('input change', '.qty, .price', function () {
    let tr = $(this).closest('tr');
    calculateRow(tr);
});

$(document).on('change', '.product-select', function () {
    let price = $(this).find(':selected').data('price') || 0;
    let tr = $(this).closest('tr');
    tr.find('.price').val(price);
    calculateRow(tr);
});

$(document).on('click', '.btn-remove', function () {
    $(this).closest('tr').remove();
    calculateEditSummary();
});

// Event untuk discount, PPN, dan payment
$('#discountValue, #discountType, #ppnValue, #payAmount')
    .on('input change', function() {
        calculateEditSummary();
    });

// Button tambah item
$('#btnAddEditItem').on('click', function () {
    addEditItemRow();
});

// Helper function
function formatRupiah(number) {
    if (isNaN(number)) number = 0;
    return 'Rp ' + Math.round(number).toLocaleString('id-ID');
}

// Auto set status berdasarkan pembayaran
function autoSetStatus() {
    let total = parseCurrency($('#afterPpn').text());
    let pay = parseFloat($('#payAmount').val()) || 0;

    if (pay >= total) {
        $('#editStatus').val('paid');
    } else if (pay > 0) {
        $('#editStatus').val('unpaid');
    }
}

$('#payAmount').on('input', autoSetStatus);

// Submit update transaction
$('#btnUpdateTransaction').on('click', function () {
    let items = buildEditItemsPayload();

    if (items.length === 0) {
        alert('Minimal 1 item harus diisi');
        return;
    }

    let total = parseCurrency($('#afterPpn').text());
    let pay = parseFloat($('#payAmount').val()) || 0;

    if (pay <= 0 && $('#editStatus').val() === 'paid') {
        alert('Pembayaran belum diisi untuk status Lunas');
        return;
    }

    submitEditTransaction(items);
});

function buildEditItemsPayload() {
    let items = [];

    $('#editItemsTable tbody tr').each(function () {
        let productId = $(this).find('.product-select').val();
        let qty = parseInt($(this).find('.qty').val()) || 0;
        let price = parseFloat($(this).find('.price').val()) || 0;

        if (!productId || qty <= 0 || price <= 0) return;

        items.push({
            product_id: productId,
            qty: qty,
            price: price
        });
    });

    return items;
}
$(document).on('input', '.qty, .price', function () {
    calculateRow($(this).closest('tr'));
});

function submitEditTransaction(items) {
    let id = $('#editTransactionId').val();

    let payload = {
        customer_id: $('#editCustomer').val() || null,
        items: items,
        sub_total: parseCurrency($('#subTotal').text()),
        discount_value: $('#discountValue').val(),
        discount_type: $('#discountType').val(),
        total_after_discount: parseCurrency($('#afterDiscount').text()),
        ppn: $('#ppnValue').val(),
        total_after_ppn: parseCurrency($('#afterPpn').text()),
        pay_amount: $('#payAmount').val(),
        change_amount: parseCurrency($('#changeAmount').text()),
        status: $('#editStatus').val(),
        payment_method: $('#paymentMethod').val(),
        note: $('#orderNote').val(),
        _token: '{{ csrf_token() }}'
    };

    console.log('Submitting:', payload); // Debug

    $.ajax({
        url: `/transactions/${id}`,
        type: 'PUT',
        data: payload,
        success(res) {
            editModal.hide();
            alert('Transaksi berhasil diperbarui');
            loadTransactions();
        },
        error(err) {
            console.error(err);
            alert('Gagal update transaksi: ' + (err.responseJSON?.message || 'Unknown error'));
        }
    });
}

function parseCurrency(text) {
    return parseFloat(text.replace(/[^\d]/g, '')) || 0;
}
</script>


@endpush

@endsection
