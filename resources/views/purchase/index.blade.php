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
                {{-- <a href="{{ route('dashboard.hutang') }}" class="btn-soft light">
                    <i class="fa-solid fa-grip"></i> Dashboard Hutang
                </a> --}}
                <a href="{{ route('laporan.hutang') }}" class="btn-soft light">
                    <i class="fa-solid fa-chart-line"></i> Laporan Hutang
                </a>
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
                        <b id="detailSubtotal"></b>
                        <br>
                        <b id="detailPpn"></b>
                        <br>
                        <b id="detailDiscount"></b>
                        <br>
                        <b id="detailTotal"></b>
                    </div>
                    <hr>
                    <h6>History Pembayaran</h6>

                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Metode</th>
                                    <th>Nominal</th>
                                    <th>Sisa Hutang</th>
                                </tr>
                            </thead>
                            <tbody id="paymentHistoryBody"></tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- END MAIN CONTENT -->

    <!-- MODAL PEMBAYARAN -->
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-hidden="true">
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
                        <input type="date" id="paymentDate" class="form-control" required>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Metode Bayar</label>
                        <select id="paymentMethod" class="form-select">
                            <option value="Cash">Cash</option>
                            <option value="Transfer">Transfer</option>
                            <option value="Giro">Giro</option>
                        </select>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Nominal Bayar</label>
                        <input type="number" id="paymentAmount" class="form-control" required>
                        <small id="paymentRemainingInfo" class="text-muted"></small>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">No. Referensi</label>
                        <input type="text" id="paymentReference" class="form-control" placeholder="Opsional">
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Catatan</label>
                        <input type="text" id="paymentNote" class="form-control" placeholder="Opsional">
                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn-soft light" data-bs-dismiss="modal">Batal</button>
                    <button class="btn-soft" id="btnSavePayment">Simpan Pembayaran</button>
                </div>

            </div>
        </div>
    </div>


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
                    data: {
                        search: $("#searchPurchase").val(),
                        status: $("#filterStatus").val(),
                        date_from: $("#dateFrom").val(),
                        date_to: $("#dateTo").val(),
                        page: pagination.currentPage,
                        per_page: pagination.perPage
                    },
                    success: function(res) {

                        const paginated = res.data; // <= ambil wrapper pagination Laravel

                        renderPurchaseTable(paginated.data);
                        renderPurchasePagination(paginated);
                    }
                });
            }

            function renderPurchaseTable(data) {
                let tbody = $("#purchaseTable tbody");
                tbody.empty();

                if (!data.length) {
                    $("#emptyState").removeClass("d-none");
                    return;
                }

                $("#emptyState").addClass("d-none");

                data.forEach((p, i) => {
                    let supplier = p.supplier ? p.supplier.nama_supplier : '-';
                    let total = parseFloat(p.grand_total ?? 0);

                    let badge = {
                        draft: '<span class="badge bg-secondary">Draft</span>',
                        posted: '<span class="badge bg-info">Posted</span>',
                        paid: '<span class="badge bg-success">Lunas</span>',
                        unpaid: '<span class="badge bg-warning">Hutang</span>',
                    } [p.status_pembelian] || '-';

                    tbody.append(`
            <tr>
                <td>${i + 1}</td>
                <td>${p.tanggal}</td>
                <td><strong>${p.invoice}</strong></td>
                <td>${supplier}</td>
                <td>${p.jatuh_tempo ?? '-'}</td>
                <td>${p.items.length} Item</td>
                <td>Rp ${total.toLocaleString()}</td>
                <td>${badge}</td>
                <td class="text-end">
                    <button class="btn btn-sm btn-view" data-id="${p.id}"><i class="bi bi-eye"></i></button>
                    <button class="btn btn-sm btn-edit" data-id="${p.id}"><i class="bi bi-pencil"></i></button>
                    <button class="btn btn-sm btn-print" data-id="${p.id}"><i class="bi bi-printer"></i></button>
                    <button class="btn btn-sm btn-delete" data-id="${p.id}"><i class="bi bi-trash"></i></button>
                    ${p.status_pembelian === 'unpaid' ?
        `<button class="btn btn-sm btn-payment" data-id="${p.id}">
                            <i class="bi bi-cash"></i>
                        </button>`
    : ''}
                </td>
            </tr>
        `);
                });
            }

            function renderPurchasePagination(res) {
                const pag = $("#pagination");
                const info = $("#paginationInfo");
                pag.empty();

                if (res.total === 0) {
                    info.text("Tidak ada data.");
                    return;
                }

                info.text(
                    `Menampilkan ${res.from} - ${res.to} dari ${res.total} data (Hal ${res.current_page} / ${res.last_page})`
                );

                // Prev
                pag.append(`
        <li class="page-item ${res.current_page == 1 ? 'disabled' : ''}">
            <a class="page-link" href="#" data-page="${res.current_page - 1}">«</a>
        </li>
    `);

                // Page numbers
                for (let p = 1; p <= res.last_page; p++) {
                    pag.append(`
            <li class="page-item ${p == res.current_page ? 'active' : ''}">
                <a class="page-link" href="#" data-page="${p}">${p}</a>
            </li>
        `);
                }

                // Next
                pag.append(`
        <li class="page-item ${res.current_page == res.last_page ? 'disabled' : ''}">
            <a class="page-link" href="#" data-page="${res.current_page + 1}">»</a>
        </li>
    `);
            }
            $("#pagination").on("click", ".page-link", function(e) {
                e.preventDefault();

                const page = $(this).data("page");
                if (!page || page < 1) return;

                pagination.currentPage = page;
                loadPurchases();
            });



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

            // function savePurchase() {
            //     const id = $("#purchaseId").val();

            //     const payload = {
            //         id,
            //         invoice: $("#noInvoice").val(),
            //         supplier_id: $("#supplier").val(),
            //         date: $("#tgl").val(),
            //         due_date: $("#jatuhTempo").val(),
            //         status: $("#statusPembelian").val(),
            //         method: $("#metodeBayar").val(),
            //         note: $("#catatan").val(),
            //         ppn_percent: $("#ppnPercent").val(),
            //         discount_transaction: $("#discountTransaction").val(),
            //         items: []
            //     };

            //     $("#purchaseItemsTable tbody tr").each(function() {
            //         payload.items.push({
            //             product_id: $(this).find(".item-product").val(),
            //             qty: $(this).find(".item-qty").val(),
            //             price: $(this).find(".item-price").val(),
            //             discount_percent: $(this).find(".item-disc").val()
            //         });
            //     });

            //     $.post("/purchases/store", payload, function() {
            //         Swal.fire("Sukses", "Pembelian berhasil disimpan", "success");
            //         purchaseModal.hide();
            //         loadPurchases();
            //     });
            // }

            function savePurchase() {
                const id = $("#purchaseId").val();

                const payload = {
                    invoice: $("#noInvoice").val(),
                    supplier_id: $("#supplier").val(),
                    date: $("#tgl").val(),
                    due_date: $("#jatuhTempo").val(),
                    status: $("#statusPembelian").val(),
                    method: $("#metodeBayar").val(),
                    note: $("#catatan").val(),
                    ppnPercent: $("#ppnPercent").val(),
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

                let url = "";
                let method = "";

                if (id) {
                    // EDIT
                    url = `/purchases/${id}`;
                    method = "PUT";
                } else {
                    // NEW
                    url = `/purchases/store`;
                    method = "POST";
                }

                $.ajax({
                    url: url,
                    method: method,
                    data: payload,
                    success: function(res) {
                        Swal.fire("Sukses", id ? "Pembelian berhasil diperbarui" : "Pembelian berhasil disimpan",
                            "success");
                        purchaseModal.hide();
                        loadPurchases();
                    },
                    error: function(err) {
                        console.log(err);
                        Swal.fire("Error", "Gagal menyimpan data", "error");
                    }
                });
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
                        $("#detailSubtotal").text(`Subtotal: ${formatRupiah(p.subtotal)}`);
                        $("#detailPpn").text(`ppn: ${p.ppn_percent}%`);
                        $("#detailDiscount").text(`diskon: ${formatRupiah(p.discount_transaction)}`);
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
                                    Swal.fire("Deleted", "Pembelian berhasil dihapus!",
                                        "success");
                                    loadPurchases();
                                }
                            });
                        }
                    });
                });

                $("#searchPurchase, #filterStatus, #dateFrom, #dateTo").on("input change", function() {
                    pagination.currentPage = 1;
                    loadPurchases();
                });

            });

            let paymentModal = new bootstrap.Modal($("#paymentModal")[0]);

            $("#purchaseTable").on("click", ".btn-payment", function() {
                const id = $(this).data("id");
                $("#paymentPurchaseId").val(id);

                $.get(`/purchases/${id}/detail`, function(res) {
                    const p = res.data;

                    const totalPaid = p.payments?.reduce((acc, x) => acc + x.amount, 0) || 0;
                    const remaining = p.grand_total - totalPaid;

                    $("#paymentRemainingInfo").text(`Sisa hutang: Rp ${remaining.toLocaleString()}`);
                    $("#paymentAmount").attr("max", remaining);
                });

                paymentModal.show();
            });
            $("#btnSavePayment").click(function() {
                const payload = {
                    purchase_id: $("#paymentPurchaseId").val(),
                    payment_date: $("#paymentDate").val(),
                    payment_method: $("#paymentMethod").val(),
                    amount: $("#paymentAmount").val(),
                    reference: $("#paymentReference").val(),
                    note: $("#paymentNote").val(),
                };

                $.post("/purchase-payments/store", payload, function(res) {
                    Swal.fire("Sukses", "Pembayaran berhasil disimpan", "success");
                    paymentModal.hide();
                    loadPurchases();
                }).fail(function(err) {
                    Swal.fire("Error", err.responseJSON.message ?? "Gagal menyimpan pembayaran", "error");
                });
            });
            $("#purchaseTable").on("click", ".btn-view", function() {
                const id = $(this).data("id");

                $.get(`/purchase-payments/${id}`, function(res) {
                    let html = "";

                    res.data.forEach(p => {
                        html += `
                <tr>
                    <td>${p.payment_date}</td>
                    <td>${p.payment_method}</td>
                    <td>Rp ${p.amount.toLocaleString()}</td>
                    <td>Rp ${p.remaining_amount.toLocaleString()}</td>
                </tr>
            `;
                    });

                    $("#paymentHistoryBody").html(html || "<tr><td colspan='4'>Belum ada pembayaran</td></tr>");
                });
            });
        </script>
    @endpush
@endsection
