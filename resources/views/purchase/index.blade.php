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
                <input type="date" id="dateFrom">
                <span class="filter-label">s/d</span>
                <input type="date" id="dateTo">
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
                            <input type="text" id="supplier" class="form-control" required placeholder="Nama supplier">
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
                                <th style="width:32%;">Nama Barang</th>
                                <th style="width:12%;">Qty</th>
                                <th style="width:18%;">Harga Beli</th>
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
                                    <input type="number" id="ppnPercent" class="form-control form-control-sm text-end"
                                           value="11" min="0" style="width:70px;display:inline-block;">
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

@push('scripts')
<script>
    // Dummy data pembelian
    let purchases = [
        {
            id: 1,
            date: '2025-11-08',
            invoice: 'PB-20251108-001',
            supplier: 'PT Sumber Rasa',
            due_date: '2025-11-15',
            status: 'posted',
            pay_status: 'unpaid',
            method: 'Transfer',
            note: '',
            items: [
                {name: 'Daging Sapi 1kg', qty: 10, price: 85000},
                {name: 'Roti Burger', qty: 50, price: 2500}
            ]
        },
        {
            id: 2,
            date: '2025-11-07',
            invoice: 'PB-20251107-002',
            supplier: 'CV Minuman Segar',
            due_date: '2025-11-07',
            status: 'paid',
            pay_status: 'paid',
            method: 'Cash',
            note: '',
            items: [
                {name: 'Kopi Bubuk 1kg', qty: 5, price: 60000},
                {name: 'Susu UHT 1L', qty: 24, price: 12000}
            ]
        },
        {
            id: 3,
            date: '2025-11-06',
            invoice: 'PB-20251106-003',
            supplier: 'PT Snackindo',
            due_date: '2025-11-20',
            status: 'draft',
            pay_status: 'unpaid',
            method: 'Transfer',
            note: 'Belum dicek',
            items: [
                {name: 'Kentang Beku 1kg', qty: 20, price: 28000}
            ]
        }
    ];

    const pagination = {
        perPage: 8,
        currentPage: 1,
        filteredData: []
    };

    let purchaseModal;

    function formatRupiah(val) {
        val = isNaN(val) ? 0 : Math.floor(val);
        return 'Rp ' + val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function calcPurchaseTotal(p) {
        let sub = 0;
        (p.items || []).forEach(it => {
            sub += (parseFloat(it.qty) || 0) * (parseFloat(it.price) || 0);
        });
        return sub;
    }

    function renderStatusBadge(p) {
        if (p.status === 'draft') return '<span class="badge-status badge-draft">Draft</span>';
        if (p.status === 'posted' && p.pay_status === 'unpaid')
            return '<span class="badge-status badge-posted">Posted / Hutang</span>';
        if (p.pay_status === 'paid') return '<span class="badge-status badge-paid">Lunas</span>';
        if (p.pay_status === 'unpaid') return '<span class="badge-status badge-unpaid">Hutang</span>';
        return '<span class="badge-status badge-draft">Draft</span>';
    }

    function applyFilters() {
        const q = ($('#searchPurchase').val() || '').toLowerCase();
        const stat = $('#filterStatus').val();
        const df = $('#dateFrom').val();
        const dt = $('#dateTo').val();

        pagination.filteredData = purchases.filter(p => {
            if (df && p.date < df) return false;
            if (dt && p.date > dt) return false;

            if (stat) {
                if (stat === 'draft' && p.status !== 'draft') return false;
                if (stat === 'posted' && p.status !== 'posted') return false;
                if (stat === 'paid' && p.pay_status !== 'paid') return false;
                if (stat === 'unpaid' && p.pay_status !== 'unpaid') return false;
            }

            if (q) {
                const s = (p.invoice + ' ' + p.supplier).toLowerCase();
                if (!s.includes(q)) return false;
            }
            return true;
        });

        pagination.currentPage = 1;
        renderPage();
    }

    function renderPage() {
        const $tbody = $('#purchaseTable tbody');
        const $empty = $('#emptyState');
        const $info = $('#paginationInfo');
        const $pag = $('#pagination');

        $tbody.empty();
        $pag.empty();

        const total = pagination.filteredData.length;
        if (!total) {
            $empty.removeClass('d-none');
            $info.text('0 pembelian');
            return;
        } else {
            $empty.addClass('d-none');
        }

        const perPage = pagination.perPage;
        const totalPages = Math.max(1, Math.ceil(total / perPage));
        if (pagination.currentPage > totalPages) pagination.currentPage = totalPages;

        const start = (pagination.currentPage - 1) * perPage;
        const end = start + perPage;
        const slice = pagination.filteredData.slice(start, end);

        slice.forEach((p, idx) => {
            const itemCount = (p.items || []).length;
            const totalAmount = calcPurchaseTotal(p);
            const row = `
                <tr>
                    <td>${start + idx + 1}</td>
                    <td>${p.date}</td>
                    <td><strong>${p.invoice}</strong></td>
                    <td>${p.supplier}</td>
                    <td>${p.due_date || '-'}</td>
                    <td>${itemCount} Item</td>
                    <td>${formatRupiah(totalAmount)}</td>
                    <td>${renderStatusBadge(p)}</td>
                    <td class="text-end">
                        <button class="action-btn" data-action="view" data-id="${p.id}" title="Detail">
                            <i class="bi bi-eye-fill"></i>
                        </button>
                        <button class="action-btn" data-action="edit" data-id="${p.id}" title="Edit">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        <button class="action-btn" data-action="print" data-id="${p.id}" title="Cetak">
                            <i class="bi bi-printer-fill"></i>
                        </button>
                        <button class="action-btn" data-action="delete" data-id="${p.id}" title="Hapus">
                            <i class="bi bi-trash3-fill"></i>
                        </button>
                    </td>
                </tr>
            `;
            $tbody.append(row);
        });

        $info.text(
            `Menampilkan ${start + 1} - ${Math.min(end, total)} dari ${total} pembelian (Hal ${pagination.currentPage} / ${totalPages})`
        );

        const addPage = (label, page, disabled, active) => {
            const li = $(`
                <li class="page-item ${disabled ? 'disabled' : ''} ${active ? 'active' : ''}">
                    <a class="page-link" href="#" ${!disabled ? `data-page="${page}"` : ''}>${label}</a>
                </li>
            `);
            $pag.append(li);
        };

        addPage('«', pagination.currentPage - 1, pagination.currentPage === 1, false);
        for (let p = 1; p <= totalPages; p++) {
            addPage(p, p, false, p === pagination.currentPage);
        }
        addPage('»', pagination.currentPage + 1, pagination.currentPage === totalPages, false);
    }

    // ===== Modal Helpers (jQuery) =====

    function clearItemsTable() {
        $('#purchaseItemsTable tbody').empty();
    }

    function addItemRow(data) {
        data = data || {};
        const row = $(`
            <tr>
                <td>
                    <input type="text" class="form-control form-control-sm item-name" placeholder="Nama barang"
                        value="${data.name || ''}">
                </td>
                <td>
                    <input type="number" class="form-control form-control-sm item-qty text-end" min="1"
                        value="${data.qty || 1}">
                </td>
                <td>
                    <input type="number" class="form-control form-control-sm item-price text-end" min="0"
                        value="${data.price || 0}">
                </td>
                <td class="text-end">
                    <span class="item-subtotal">Rp 0</span>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-link p-0 text-danger btn-remove-row">
                        <i class="bi bi-x-circle-fill"></i>
                    </button>
                </td>
            </tr>
        `);
        $('#purchaseItemsTable tbody').append(row);
        updateItemRow(row);
    }

    function updateItemRow($tr) {
        const qty = parseFloat($tr.find('.item-qty').val()) || 0;
        const price = parseFloat($tr.find('.item-price').val()) || 0;
        const sub = qty * price;
        $tr.find('.item-subtotal').text(formatRupiah(sub));
    }

    function recalcModalTotals() {
        let sub = 0;
        $('#purchaseItemsTable tbody tr').each(function () {
            const qty = parseFloat($(this).find('.item-qty').val()) || 0;
            const price = parseFloat($(this).find('.item-price').val()) || 0;
            sub += qty * price;
        });
        const ppnPercent = parseFloat($('#ppnPercent').val()) || 0;
        const ppnAmount = sub * (ppnPercent / 100);
        const grand = sub + ppnAmount;
        $('#subTotalText').text(formatRupiah(sub));
        $('#grandTotalText').text(formatRupiah(grand));
    }

    function openNewPurchaseModal() {
        $('#purchaseModalTitle').text('Pembelian Baru');
        $('#purchaseForm')[0].reset();
        $('#purchaseId').val('');
        $('#statusPembelian').val('draft');
        $('#metodeBayar').val('Cash');
        clearItemsTable();
        addItemRow();
        recalcModalTotals();
        purchaseModal.show();
    }

    function openEditPurchaseModal(id) {
        const p = purchases.find(x => x.id == id);
        if (!p) return;

        $('#purchaseModalTitle').text('Edit Pembelian');
        $('#purchaseId').val(p.id);
        $('#noInvoice').val(p.invoice);
        $('#supplier').val(p.supplier);
        $('#tgl').val(p.date);
        $('#jatuhTempo').val(p.due_date || '');
        $('#statusPembelian').val(p.status || 'posted');
        $('#metodeBayar').val(p.method || 'Cash');
        $('#catatan').val(p.note || '');
        $('#ppnPercent').val(11);

        clearItemsTable();
        (p.items || []).forEach(it => addItemRow(it));
        if (!p.items || !p.items.length) addItemRow();

        recalcModalTotals();
        purchaseModal.show();
    }

    function savePurchase() {
        const id = $('#purchaseId').val();
        const invoice = $('#noInvoice').val().trim();
        const supplier = $('#supplier').val().trim();
        const tgl = $('#tgl').val();
        const jt = $('#jatuhTempo').val();
        const status = $('#statusPembelian').val();
        const metode = $('#metodeBayar').val();
        const note = $('#catatan').val().trim();
        const ppnPercent = parseFloat($('#ppnPercent').val()) || 0;

        if (!invoice || !supplier || !tgl) {
            Swal.fire('Lengkapi Data', 'No. Invoice, Supplier, dan Tanggal wajib diisi.', 'warning');
            return;
        }

        const items = [];
        $('#purchaseItemsTable tbody tr').each(function () {
            const name = ($(this).find('.item-name').val() || '').trim();
            const qty = parseFloat($(this).find('.item-qty').val()) || 0;
            const price = parseFloat($(this).find('.item-price').val()) || 0;
            if (name && qty > 0 && price >= 0) {
                items.push({name, qty, price});
            }
        });

        if (!items.length) {
            Swal.fire('Detail Kosong', 'Minimal 1 item pembelian harus diisi.', 'warning');
            return;
        }

        let pay_status = 'unpaid';
        if (status === 'paid') pay_status = 'paid';

        if (!id) {
            const newId = purchases.length ? Math.max.apply(null, purchases.map(p => p.id)) + 1 : 1;
            purchases.push({
                id: newId,
                date: tgl,
                invoice,
                supplier,
                due_date: jt,
                status,
                pay_status,
                method: metode,
                note,
                ppn: ppnPercent,
                items
            });
            Swal.fire('Berhasil', 'Pembelian baru disimpan (dummy).', 'success');
        } else {
            const idx = purchases.findIndex(p => p.id == id);
            if (idx === -1) return;
            purchases[idx] = {
                ...purchases[idx],
                date: tgl,
                invoice,
                supplier,
                due_date: jt,
                status,
                pay_status,
                method: metode,
                note,
                ppn: ppnPercent,
                items
            };
            Swal.fire('Berhasil', 'Data pembelian diperbarui (dummy).', 'success');
        }

        purchaseModal.hide();
        applyFilters();
    }

    $(function () {
        // Tooltip
        $('[data-bs-toggle="tooltip"]').each(function () {
            new bootstrap.Tooltip(this, {
                boundary: document.body,
                customClass: 'sidebar-tooltip',
                trigger: 'hover'
            });
        });

        // Modal init
        purchaseModal = new bootstrap.Modal($('#purchaseModal')[0]);

        // Filter events
        $('#searchPurchase').on('input', applyFilters);
        $('#filterStatus, #dateFrom, #dateTo').on('change', applyFilters);

        // Top buttons
        $('#btnRefresh').on('click', function () {
            $('#searchPurchase').val('');
            $('#filterStatus').val('');
            $('#dateFrom').val('');
            $('#dateTo').val('');
            applyFilters();
        });

        $('#btnNewPurchase').on('click', openNewPurchaseModal);

        $('#btnExport').on('click', function () {
            Swal.fire('Export', 'Integrasikan export data pembelian ke CSV/Excel.', 'info');
        });

        $('#btnPrint').on('click', function () {
            Swal.fire('Print', 'Integrasikan layout print daftar pembelian.', 'info');
        });

        // Modal events
        $('#btnAddRow').on('click', function () {
            addItemRow();
            recalcModalTotals();
        });

        $('#ppnPercent').on('input', recalcModalTotals);

        $('#purchaseItemsTable tbody').on('input', '.item-qty, .item-price', function () {
            const $tr = $(this).closest('tr');
            let qty = parseFloat($tr.find('.item-qty').val()) || 0;
            let price = parseFloat($tr.find('.item-price').val()) || 0;
            if (qty <= 0) { qty = 1; $tr.find('.item-qty').val(1); }
            if (price < 0) { price = 0; $tr.find('.item-price').val(0); }
            updateItemRow($tr);
            recalcModalTotals();
        });

        $('#purchaseItemsTable tbody').on('click', '.btn-remove-row', function () {
            $(this).closest('tr').remove();
            recalcModalTotals();
        });

        $('#btnSavePurchase').on('click', savePurchase);

        // Table actions
        $('#purchaseTable tbody').on('click', '.action-btn', function () {
            const id = $(this).data('id');
            const action = $(this).data('action');
            const p = purchases.find(x => x.id == id);
            if (!p) return;

            if (action === 'view') {
                const total = calcPurchaseTotal(p);
                const itemsHtml = (p.items || []).map(it =>
                    `${it.name} (${it.qty} x ${formatRupiah(it.price)})`
                ).join('<br>');
                Swal.fire({
                    title: 'Detail Pembelian',
                    html: `
                        <div style="font-size:11px;text-align:left">
                            <b>No Invoice:</b> ${p.invoice}<br>
                            <b>Tanggal:</b> ${p.date}<br>
                            <b>Supplier:</b> ${p.supplier}<br>
                            <b>Jatuh Tempo:</b> ${p.due_date || '-'}<br>
                            <b>Metode:</b> ${p.method}<br>
                            <b>Status:</b> ${p.status.toUpperCase()} / ${p.pay_status.toUpperCase()}<br>
                            <b>Items:</b><br>${itemsHtml || '-'}<br>
                            <b>Total:</b> ${formatRupiah(total)}
                        </div>
                    `
                });
            }

            if (action === 'edit') {
                openEditPurchaseModal(id);
            }

            if (action === 'print') {
                Swal.fire('Cetak', 'Integrasikan cetak dokumen pembelian untuk ' + p.invoice, 'info');
            }

            if (action === 'delete') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Hapus Pembelian',
                    text: 'Yakin menghapus ' + p.invoice + ' dari ' + p.supplier + '?',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus',
                    cancelButtonText: 'Batal'
                }).then(res => {
                    if (res.isConfirmed) {
                        purchases = purchases.filter(x => x.id != id);
                        Swal.fire('Dihapus', 'Data pembelian telah dihapus.', 'success');
                        applyFilters();
                    }
                });
            }
        });

        // Pagination click
        $('#pagination').on('click', '.page-link', function (e) {
            e.preventDefault();
            const page = parseInt($(this).data('page'));
            if (!page || page === pagination.currentPage) return;
            pagination.currentPage = page;
            renderPage();
        });

        // Initial load
        applyFilters();
    });
</script>
@endpush

@endsection
