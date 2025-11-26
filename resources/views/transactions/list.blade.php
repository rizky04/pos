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
                <option value="unpaid">Pending</option>
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
                paid: '<span class="badge-status badge-active">Paid</span>',
                pending: '<span class="badge-status badge-pending">Pending</span>',
                cancelled: '<span class="badge-status badge-nonactive">Cancelled</span>',
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

            // $("#transactionDetail").html(`
            //     <b>Kode:</b> ${t.kode}<br>
            //     <b>Tanggal:</b> ${t.created_at}<br>
            //     <b>Status:</b> ${t.status}<br>
            //     <hr>
            //     <b>Detail Item</b>
            //     <table class="table-responsive mt-2">
            //         <thead>
            //             <tr>
            //                 <th>#</th>
            //                 <th>Produk</th>
            //                 <th>Qty</th>
            //                 <th>Subtotal</th>
            //             </tr>
            //         </thead>
            //         <tbody>${items}</tbody>
            //     </table>
            //     <hr>
            //     <b>Subtotal:</b> Rp ${parseInt(t.sub_total).toLocaleString()}
            //     <br>
            //     <b>Discount:</b> Rp ${parseInt(t.discount_value).toLocaleString()} (${t.discount_type})
            //     <br>
            //     <b>Setelah Diskon:</b> Rp ${parseInt(t.total_after_discount).toLocaleString()}
            //     <br>
            //     <b>PPN:</b> Rp ${parseInt(t.ppn).toLocaleString()}
            //     <br>
            //     <b>Setelah PPN:</b> Rp ${parseInt(t.total_after_ppn).toLocaleString()}
            //     <br>
            //     <b>Bayar:</b> Rp ${parseInt(t.pay_amount).toLocaleString()}
            //     <br>
            //     <b>Kembalian:</b> Rp ${parseInt(t.change_amount).toLocaleString()}
            // `);
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

</script>
@endpush

@endsection
