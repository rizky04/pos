@extends('layouts.apps')

@section('content')
    <!-- MAIN CONTENT -->
    <section>
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <div class="page-header-title">Pembelian Barang</div>
                <div class="page-header-sub">Catat pembelian dari supplier & update stok secara rapi.</div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <button class="btn-soft light" id="btnRefresh">
                    <i class="bi bi-arrow-clockwise"></i> Reload
                </button>
                <button class="btn-soft" id="btnNewPurchase">
                    <i class="bi bi-plus-circle"></i> Pembelian Baru
                </button>
            </div>
        </div>

        <!-- Filter / Tools -->
        <div class="filter-bar mt-2">
            <div class="filter-label">Filter</div>

            <div class="d-flex align-items-center gap-1">
                <span class="filter-label">Tanggal</span>
                <input type="date" id="dateFrom" class="form-control">
                <span class="filter-label">s/d</span>
                <input type="date" id="dateTo" class="form-control">
            </div>

            <div class="filter-search-wrap">
                <i class="bi bi-search"></i>
                <input type="text" id="searchPurchase" placeholder="Cari no. invoice / supplier">
            </div>

            <div>
                <select id="filterStatus">
                    <option value="">Semua Status</option>
                    <option value="draft">Draft</option>
                    <option value="posted">Posted</option>
                    <option value="paid">Lunas</option>
                    <option value="unpaid">Hutang</option>
                </select>
            </div>

            <div class="ms-auto d-flex gap-1">
                <button class="btn-soft light" id="btnExport">
                    <i class="bi bi-download"></i> Export
                </button>
                <button class="btn-soft light" id="btnPrint">
                    <i class="bi bi-printer"></i> Print
                </button>
            </div>
        </div>

        <!-- Table -->
        <div class="table-wrap">
            <div class="table-responsive">
                <table class="table align-middle" id="purchaseTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tanggal</th>
                            <th>No. Invoice</th>
                            <th>Supplier</th>
                            <th>Jatuh Tempo</th>
                            <th>Item</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
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
    </section>
    <!-- MODAL TAMBAH / EDIT PEMBELIAN -->
    <div class="modal fade" id="purchaseModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="purchaseModalTitle">Pembelian Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        style="font-size:8px;"></button>
                </div>
                <div class="modal-body">
                    <form id="purchaseForm">
                        <input type="hidden" id="purchaseId">
                        <div class="row g-2">
                            <div class="col-md-4">
                                <label class="form-label">No. Invoice / Bill</label>
                                <input type="text" id="noInvoice" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Supplier</label>
                                <select id="supplier" class="form-select"></select>
                                {{-- <input type="text" id="supplier" class="form-control" required placeholder="Nama supplier"> --}}
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Tanggal</label>
                                <input type="date" id="tgl" class="form-control" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Jatuh Tempo</label>
                                <input type="date" id="jatuhTempo" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Status Pembelian</label>
                                <select id="statusPembelian" class="form-select">
                                    <option value="draft">Draft</option>
                                    <option value="posted">Posted</option>
                                    <option value="paid">Lunas</option>
                                    <option value="unpaid">Hutang</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Metode Bayar</label>
                                <select id="metodeBayar" class="form-select">
                                    <option value="Cash">Cash</option>
                                    <option value="Transfer">Transfer</option>
                                    <option value="Giro">Giro</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Catatan</label>
                                <input type="text" id="catatan" class="form-control" placeholder="Opsional">
                            </div>
                        </div>

                        <div class="purchase-items-header mt-2 d-flex justify-content-between align-items-center">
                            <span>Detail Item Pembelian</span>
                            <button type="button" class="btn-soft light btn-sm" id="btnAddRow">
                                <i class="bi bi-plus-circle"></i> Tambah Baris
                            </button>
                        </div>

                        <div class="table-responsive mt-1">
                            <table class="table table-sm purchase-items-table mb-1" id="purchaseItemsTable">
                                <thead>
                                    <tr>
                                        <th style="width:30%;">Nama Barang</th>
                                        <th style="width:10%;">Qty</th>
                                        <th style="width:18%;">Harga Beli</th>
                                        <th style="width:10%;">Disc (%)</th>
                                        <th style="width:18%;">Subtotal</th>
                                        <th style="width:6%;" class="text-center">#</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>

                        <div class="row g-1">
                            <div class="col-md-8"></div>
                            <div class="col-md-4">
                                <div class="d-flex justify-content-between">
                                    <div class="purchase-total-label">Sub Total</div>
                                    <div class="purchase-total-value" id="subTotalText">Rp 0</div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="purchase-total-label">PPN (%)</div>
                                    <div>
                                        <input type="number" id="ppnPercent"
                                            class="form-control form-control-sm text-end" value="11" min="0"
                                            style="width:70px;display:inline-block;">
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-1">
                                    <div class="purchase-total-label">Diskon Transaksi(Rp.)</div>
                                    <div>
                                        <input type="number" id="discountTransaction"
                                            class="form-control form-control-sm text-end" value="0" min="0"
                                            style="width:80px;display:inline-block;">
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between">
                                    <div class="purchase-total-label">Total + PPN</div>
                                    <div class="purchase-total-value" id="grandTotalText">Rp 0</div>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn-soft light" data-bs-dismiss="modal">Batal</button>
                    <button class="btn-soft" id="btnSavePurchase">Simpan</button>
                </div>
            </div>
        </div>
    </div>
    <!-- MODAL DETAIL PEMBELIAN -->
<div class="modal fade" id="detailPurchaseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Pembelian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div id="detailContent"></div>

                <hr>

                <h6>Daftar Item</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr>
                                <th>Nama Barang</th>
                                <th class="text-end">Qty</th>
                                <th class="text-end">Harga</th>
                                <th class="text-end">Disc (%)</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="detailItems"></tbody>
                    </table>
                </div>

                <div class="text-end mt-2">
                    <b id="detailTotal"></b>
                </div>
            </div>
        </div>
    </div>
</div>
    <!-- END MAIN CONTENT -->

   @push('scripts')
<script>

/* ============================================================
   GLOBAL VARIABLES
============================================================ */
let purchases = [];
let supplierList = [];
let productList = [];
let purchaseModal;

let pagination = {
    currentPage: 1,
    perPage: 10,
    filteredData: []
};

/* ============================================================
   LOAD DATA
============================================================ */
function loadPurchases() {
    $.ajax({
        url: "/purchases/data",
        method: "GET",
        data: { per_page: 100 },
        success: function(res) {
            purchases = res.data.data;
            applyFilters();
        },
        error: function() {
            Swal.fire("Error", "Gagal mengambil data pembelian", "error");
        }
    });
}

function loadSuppliers() {
    $.get("/select/suppliers", function(res) {
        supplierList = res;
        let opt = `<option value="">-- Pilih Supplier --</option>`;
        res.forEach(x => opt += `<option value="${x.id}">${x.nama_supplier}</option>`);
        $("#supplier").html(opt);
    });
}

function loadProducts() {
    $.get("/select/products", function(res) {
        productList = res;
    });
}

/* ============================================================
   UTILS
============================================================ */
function formatRupiah(v) {
    v = isNaN(v) ? 0 : parseFloat(v);
    return "Rp " + v.toLocaleString("id-ID");
}

function clearItemsTable() {
    $("#purchaseItemsTable tbody").empty();
}

/* ============================================================
   ITEM ROW HANDLING
============================================================ */
function addItemRow(data = {}) {
    let productOptions = `<option value="">Pilih Barang</option>`;
    productList.forEach(x => productOptions += `<option value="${x.id}">${x.nama}</option>`);

    let row = $(`
        <tr>
            <td><select class="form-select form-select-sm item-product">${productOptions}</select></td>
            <td><input type="number" class="form-control form-control-sm item-qty" value="${data.qty ?? 1}"></td>
            <td><input type="number" class="form-control form-control-sm item-price" value="${data.price ?? 0}"></td>
            <td><input type="number" class="form-control form-control-sm item-disc" value="${data.discount_percent ?? 0}"></td>
            <td><span class="item-subtotal">Rp 0</span></td>
            <td><button class="btn btn-sm text-danger remove-row">X</button></td>
        </tr>
    `);

    $("#purchaseItemsTable tbody").append(row);

    if (data.product_id) row.find(".item-product").val(data.product_id);
    updateRow(row);
    calcTotals();
}

function updateRow(row) {
    const qty = parseFloat(row.find(".item-qty").val()) || 0;
    const price = parseFloat(row.find(".item-price").val()) || 0;
    const disc = parseFloat(row.find(".item-disc").val()) || 0;

    let subtotal = qty * price;
    subtotal -= subtotal * (disc / 100);

    row.find(".item-subtotal").text(formatRupiah(subtotal));
}

/* ============================================================
   TOTALS
============================================================ */
function calcTotals() {
    let total = 0;

    $("#purchaseItemsTable tbody tr").each(function() {
        let qty = parseFloat($(this).find(".item-qty").val()) || 0;
        let price = parseFloat($(this).find(".item-price").val()) || 0;
        let disc = parseFloat($(this).find(".item-disc").val()) || 0;

        let sub = qty * price;
        sub -= sub * (disc / 100);

        total += sub;
    });

    $("#subTotalText").text(formatRupiah(total));

    let transDisc = parseFloat($("#discountTransaction").val()) || 0;
    let nett = Math.max(total - transDisc, 0);

    const ppn = parseFloat($("#ppnPercent").val()) || 0;
    const grand = nett + (nett * ppn / 100);

    $("#grandTotalText").text(formatRupiah(grand));
}

/* ============================================================
   MODAL HANDLING
============================================================ */
function openNewPurchaseModal() {
    $("#purchaseModalTitle").text("Pembelian Baru");
    $("#purchaseForm")[0].reset();
    $("#purchaseId").val("");
    clearItemsTable();
    addItemRow();
    calcTotals();
    purchaseModal.show();
}

function openEditPurchaseModal(id) {
    $.get(`/purchases/${id}/detail`, function(res) {
        const p = res.data;

        $("#purchaseModalTitle").text("Edit Pembelian");
        $("#purchaseId").val(p.id);
        $("#noInvoice").val(p.invoice);
        $("#supplier").val(p.supplier_id);
        $("#tgl").val(p.tanggal);
        $("#jatuhTempo").val(p.jatuh_tempo);
        $("#statusPembelian").val(p.status_pembelian);
        $("#metodeBayar").val(p.metode_bayar);
        $("#catatan").val(p.catatan);
        $("#ppnPercent").val(p.ppn_percent || 11);
        $("#discountTransaction").val(p.discount_transaction || 0);

        clearItemsTable();
        (p.items || []).forEach(it => {
            addItemRow({
                product_id: it.product_id,
                qty: it.qty,
                price: it.harga_beli,
                discount_percent: it.discount_percent
            });
        });

        calcTotals();
        purchaseModal.show();
    });
}

function savePurchase() {
    const id = $("#purchaseId").val();

    const payload = {
        id,
        invoice: $("#noInvoice").val(),
        supplier_id: $("#supplier").val(),
        date: $("#tgl").val(),
        due_date: $("#jatuhTempo").val(),
        status: $("#statusPembelian").val(),
        method: $("#metodeBayar").val(),
        note: $("#catatan").val(),
        ppn_percent: $("#ppnPercent").val(),
        discount_transaction: $("#discountTransaction").val(),
        items: []
    };

    $("#purchaseItemsTable tbody tr").each(function() {
        payload.items.push({
            product_id: $(this).find(".item-product").val(),
            qty: $(this).find(".item-qty").val(),
            price: $(this).find(".item-price").val(),
            discount_percent: $(this).find(".item-disc").val()
        });
    });

    $.post("/purchases/store", payload, function() {
        Swal.fire("Sukses", "Pembelian berhasil disimpan", "success");
        purchaseModal.hide();
        loadPurchases();
    });
}

/* ============================================================
   FILTERING + PAGINATION
============================================================ */
function applyFilters() {
    const q = ($("#searchPurchase").val() || "").toLowerCase();
    const stat = $("#filterStatus").val();
    const df = $("#dateFrom").val();
    const dt = $("#dateTo").val();

    pagination.filteredData = purchases.filter(p => {
        if (df && p.tanggal < df) return false;
        if (dt && p.tanggal > dt) return false;

        if (stat && p.status_pembelian !== stat) return false;

        if (q) {
            const supplierName = p.supplier?.nama_supplier?.toLowerCase() ?? "";
            const combined = (p.invoice + " " + supplierName).toLowerCase();
            if (!combined.includes(q)) return false;
        }

        return true;
    });

    pagination.currentPage = 1;
    renderPage();
}

function renderPage() {
    const tbody = $("#purchaseTable tbody");
    tbody.empty();

    const start = (pagination.currentPage - 1) * pagination.perPage;
    const end = start + pagination.perPage;
    const rows = pagination.filteredData.slice(start, end);

    $("#emptyState").toggleClass("d-none", rows.length > 0);

    rows.forEach((p, index) => {
        const supplierName = p.supplier?.nama_supplier ?? "-";
        const jatuhTempo = p.jatuh_tempo ?? "-";
        const totalAmount = parseFloat(p.grand_total ?? 0);
        const itemCount = (p.items || []).length;

        const statusBadge =
            p.status_pembelian === "draft" ? `<span class="badge bg-secondary">Draft</span>` :
            p.status_pembelian === "posted" && p.status_pembelian === "unpaid"
                ? `<span class="badge bg-warning">Posted/Hutang</span>` :
            p.status_pembelian === "paid"
                ? `<span class="badge bg-success">Lunas</span>` :
                `<span class="badge bg-danger">Hutang</span>`;

        tbody.append(`
            <tr>
                <td>${start + index + 1}</td>
                <td>${p.tanggal}</td>
                <td><strong>${p.invoice}</strong></td>
                <td>${supplierName}</td>
                <td>${jatuhTempo}</td>
                <td>${itemCount} Item</td>
                <td>${formatRupiah(totalAmount)}</td>
                <td>${statusBadge}</td>
                <td class="text-end">
                    <button class="btn btn-sm btn-view" data-id="${p.id}"><i class="bi bi-eye"></i></button>
                    <button class="btn btn-sm btn-edit" data-id="${p.id}"><i class="bi bi-pencil"></i></button>
                    <button class="btn btn-sm btn-print" data-id="${p.id}"><i class="bi bi-printer"></i></button>
                    <button class="btn btn-sm btn-delete" data-id="${p.id}"><i class="bi bi-trash"></i></button>
                </td>
            </tr>
        `);
    });

    renderPagination();
}

function renderPagination() {
    const pag = $("#pagination");
    pag.empty();

    const totalPages = Math.ceil(pagination.filteredData.length / pagination.perPage);
    if (totalPages <= 1) return;

    for (let i = 1; i <= totalPages; i++) {
        pag.append(`
            <li class="page-item ${pagination.currentPage === i ? 'active' : ''}">
                <a class="page-link page-num" data-page="${i}">${i}</a>
            </li>
        `);
    }

    $("#paginationInfo").text(`Menampilkan halaman ${pagination.currentPage} dari ${totalPages}`);
}

/* ============================================================
   EVENT HANDLERS
============================================================ */
$(function() {

    purchaseModal = new bootstrap.Modal($("#purchaseModal")[0]);

    loadPurchases();
    loadSuppliers();
    loadProducts();

    $("#btnNewPurchase").click(openNewPurchaseModal);
    $("#btnAddRow").click(() => addItemRow());
    $("#ppnPercent, #discountTransaction").on("input", calcTotals);

    // TABLE ITEM EVENTS
    $("#purchaseItemsTable")
        .on("input change", ".item-qty, .item-price, .item-disc", function() {
            updateRow($(this).closest("tr"));
            calcTotals();
        })
        .on("change", ".item-product", function() {
            const row = $(this).closest("tr");
            const selected = productList.find(x => x.id == $(this).val());
            if (selected) row.find(".item-price").val(selected.harga_modal);
            updateRow(row);
            calcTotals();
        })
        .on("click", ".remove-row", function() {
            $(this).closest("tr").remove();
            calcTotals();
        });

    // SAVE PURCHASE
    $("#btnSavePurchase").click(savePurchase);

    // VIEW DETAIL
    const detailModal = new bootstrap.Modal($("#detailPurchaseModal")[0]);

    $("#purchaseTable").on("click", ".btn-view", function() {
        const id = $(this).data("id");

        $.get(`/purchases/${id}/detail`, function(res) {
            const p = res.data;

            $("#detailContent").html(`
                <div style="font-size:14px;">
                    <div><b>No Invoice:</b> ${p.invoice}</div>
                    <div><b>Tanggal:</b> ${p.tanggal}</div>
                    <div><b>Supplier:</b> ${p.supplier.nama_supplier}</div>
                    <div><b>Jatuh Tempo:</b> ${p.jatuh_tempo ?? '-'}</div>
                    <div><b>Status:</b> ${p.status_pembelian}</div>
                </div>
            `);

            let itemsHTML = "";
            p.items.forEach(i => {
                let subtotal = i.qty * i.harga_beli;
                subtotal -= subtotal * (i.discount_percent / 100);

                itemsHTML += `
                    <tr>
                        <td>${i.product.nama}</td>
                        <td class="text-end">${i.qty}</td>
                        <td class="text-end">${formatRupiah(i.harga_beli)}</td>
                        <td class="text-end">${i.discount_percent}%</td>
                        <td class="text-end">${formatRupiah(subtotal)}</td>
                    </tr>
                `;
            });

            $("#detailItems").html(itemsHTML);
            $("#detailTotal").text(`Grand Total: ${formatRupiah(p.grand_total)}`);

            detailModal.show();
        });
    });

    // EDIT
    $("#purchaseTable").on("click", ".btn-edit", function() {
        openEditPurchaseModal($(this).data("id"));
    });

    // PRINT
    $("#purchaseTable").on("click", ".btn-print", function() {
        window.open(`/purchases/${$(this).data("id")}/print`, "_blank");
    });

    // DELETE
    $("#purchaseTable").on("click", ".btn-delete", function() {
        const id = $(this).data("id");

        Swal.fire({
            icon: "warning",
            title: "Hapus Pembelian?",
            text: "Data akan dihapus permanen!",
            showCancelButton: true,
            confirmButtonText: "Hapus",
            cancelButtonText: "Batal"
        }).then(res => {
            if (res.isConfirmed) {
                $.ajax({
                    url: `/purchases/${id}/delete`,
                    method: "DELETE",
                    success: function() {
                        Swal.fire("Deleted", "Pembelian berhasil dihapus!", "success");
                        loadPurchases();
                    }
                });
            }
        });
    });

    // PAGINATION CLICK
    $("#pagination").on("click", ".page-num", function() {
        pagination.currentPage = parseInt($(this).data("page"));
        renderPage();
    });

    // FILTER ON CHANGE
    $("#searchPurchase, #filterStatus, #dateFrom, #dateTo").on("input change", applyFilters);
});

</script>
@endpush

@endsection
