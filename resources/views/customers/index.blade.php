@extends('layouts.apps')

@section('content')
  <!-- MAIN CONTENT -->
  <section>
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-start">
        <div>
            <div class="page-header-title">Master Customer</div>
            <div class="page-header-sub">
                Kelola data pelanggan, tipe member, limit piutang, dan kontak.
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <button class="btn-soft light" id="btnRefresh">
                <i class="bi bi-arrow-clockwise"></i> Reload
            </button>
            <button class="btn-soft" id="btnAddCustomer">
                <i class="bi bi-plus-circle"></i> Tambah Customer
            </button>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="filter-bar mt-2">
        <div class="filter-label">Filter</div>

        <div class="filter-search-wrap">
            <i class="bi bi-search"></i>
            <input type="text" id="searchCustomer" placeholder="Cari kode / nama / telepon / email">
        </div>

        <div>
            <select id="filterStatus">
                <option value="">Semua Status</option>
                <option value="active">Aktif</option>
                <option value="nonactive">Non Aktif</option>
            </select>
        </div>

        <div>
            <select id="filterType">
                <option value="">Semua Tipe</option>
                <option value="retail">Retail</option>
                <option value="wholesale">Grosir</option>
                <option value="corporate">Corporate</option>
            </select>
        </div>

        <div>
            <select id="filterMember">
                <option value="">Semua Member</option>
                <option value="none">Non Member</option>
                <option value="silver">Silver</option>
                <option value="gold">Gold</option>
                <option value="platinum">Platinum</option>
            </select>
        </div>

        <div class="ms-auto d-flex gap-1">
            <button class="btn-soft light" id="btnImport">
                <i class="bi bi-upload"></i> Import
            </button>
            <button class="btn-soft light" id="btnExport">
                <i class="bi bi-download"></i> Export
            </button>
        </div>
    </div>

    <!-- Table -->
    <div class="table-wrap">
        <div class="table-responsive">
            <table class="table align-middle" id="customerTable">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Telepon</th>
                    <th>Email</th>
                    <th>Tipe</th>
                    <th>Member</th>
                    <th>Kota</th>
                    <th>Limit</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <div id="emptyState" class="empty-state d-none">
            Belum ada customer yang sesuai filter.
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

<!-- MODAL CUSTOMER -->
<div class="modal fade" id="customerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="customerModalTitle">Tambah Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" style="font-size:8px;"></button>
            </div>
            <div class="modal-body">
                <form id="customerForm">
                    <input type="hidden" id="customerId">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <label class="form-label">Kode Customer</label>
                            <input type="text" id="kode" class="form-control" required>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Nama Customer</label>
                            <input type="text" id="nama" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Telepon / HP</label>
                            <input type="text" id="telepon" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" id="email" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Tipe Customer</label>
                            <select id="tipe" class="form-select">
                                <option value="retail">Retail</option>
                                <option value="wholesale">Grosir</option>
                                <option value="corporate">Corporate</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Level Member</label>
                            <select id="member" class="form-select">
                                <option value="none">Non Member</option>
                                <option value="silver">Silver</option>
                                <option value="gold">Gold</option>
                                <option value="platinum">Platinum</option>
                            </select>
                        </div>

                        <div class="col-md-8">
                            <label class="form-label">Alamat</label>
                            <input type="text" id="alamat" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Kota</label>
                            <input type="text" id="kota" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Limit Piutang</label>
                            <input type="number" id="limit" class="form-control" min="0" value="0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select id="status" class="form-select">
                                <option value="active">Aktif</option>
                                <option value="nonactive">Non Aktif</option>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Catatan</label>
                            <input type="text" id="catatan" class="form-control" placeholder="Catatan khusus / preferensi">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn-soft light" data-bs-dismiss="modal">Batal</button>
                <button class="btn-soft" id="btnSaveCustomer">Simpan</button>
            </div>
        </div>
    </div>
</div>

    @push('scripts')
    <script>
        let customers = [];
        const pagination = {
            perPage: 8,
            currentPage: 1,
            filtered: []
        };

        let customerModal;

        function formatRupiah(val) {
            val = isNaN(val) ? 0 : Math.floor(val);
            return 'Rp ' + val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        // ==============================
        // LOAD DATA FROM SERVER
        // ==============================
        function loadCustomers() {
            $.get('/customers/data', function(res) {
                customers = res.data ?? res;
                applyFilters();
            });
        }

        // ==============================
        // FILTER + RENDER
        // ==============================
        function applyFilters() {
            const q = ($('#searchCustomer').val() || '').toLowerCase();
            const st = $('#filterStatus').val();
            const tp = $('#filterType').val();
            const mb = $('#filterMember').val();

            pagination.filtered = customers.filter(c => {
                if (st && c.status !== st) return false;
                if (tp && c.tipe !== tp) return false;
                if (mb) {
                    if (mb === 'none' && c.member !== 'none') return false;
                    if (mb !== 'none' && mb !== '' && c.member !== mb) return false;
                }
                if (q) {
                    const text = (
                        c.kode + ' ' +
                        c.nama + ' ' +
                        (c.telepon || '') + ' ' +
                        (c.email || '')
                    ).toLowerCase();
                    if (!text.includes(q)) return false;
                }
                return true;
            });

            pagination.currentPage = 1;
            renderPage();
        }

        function renderPage() {
            const $tbody = $('#customerTable tbody');
            const $empty = $('#emptyState');
            const $info = $('#paginationInfo');
            const $pag = $('#pagination');

            $tbody.empty();
            $pag.empty();

            const total = pagination.filtered.length;

            if (!total) {
                $empty.removeClass('d-none');
                $info.text('0 customer');
                return;
            } else {
                $empty.addClass('d-none');
            }

            const per = pagination.perPage;
            const totalPages = Math.ceil(total / per);
            const start = (pagination.currentPage - 1) * per;
            const slice = pagination.filtered.slice(start, start + per);

            slice.forEach((c, idx) => {
                const badgeStatus = c.status === 'active'
                    ? '<span class="badge-status badge-active">Aktif</span>'
                    : '<span class="badge-status badge-nonactive">Non Aktif</span>';

                const badgeType = `<span class="badge-type">${c.tipe}</span>`;
                const badgeMember = c.member !== 'none'
                    ? `<span class="badge-member">${c.member}</span>`
                    : '<span class="badge-member" style="background:#f3f4f6;color:#6b7280;">Non</span>';

                const tr = `
                    <tr>
                        <td>${start + idx + 1}</td>
                        <td><strong>${c.kode}</strong></td>
                        <td>${c.nama}</td>
                        <td>${c.telepon || '-'}</td>
                        <td>${c.email || '-'}</td>
                        <td>${badgeType}</td>
                        <td>${badgeMember}</td>
                        <td>${c.kota || '-'}</td>
                        <td>${c.limit ? formatRupiah(c.limit) : '-'}</td>
                        <td>${badgeStatus}</td>
                        <td class="text-end">
                            <button class="action-btn" data-action="view" data-id="${c.id}">
                                <i class="bi bi-eye-fill"></i>
                            </button>
                            <button class="action-btn" data-action="edit" data-id="${c.id}">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <button class="action-btn" data-action="delete" data-id="${c.id}">
                                <i class="bi bi-trash3-fill"></i>
                            </button>
                        </td>
                    </tr>
                `;
                $tbody.append(tr);
            });

            $info.text(
                `Menampilkan ${start + 1} - ${Math.min(start + per, total)} dari ${total} customer`
            );

            const addBtn = (label, page, disabled, active) => {
                const li = $(`
                    <li class="page-item ${disabled ? 'disabled' : ''} ${active ? 'active' : ''}">
                        <a class="page-link" href="#" ${!disabled ? `data-page="${page}"` : ''}>${label}</a>
                    </li>
                `);
                $pag.append(li);
            };

            addBtn('«', pagination.currentPage - 1, pagination.currentPage === 1, false);
            for (let p = 1; p <= totalPages; p++) addBtn(p, p, false, p === pagination.currentPage);
            addBtn('»', pagination.currentPage + 1, pagination.currentPage === totalPages, false);
        }

        // ==============================
        // OPEN MODAL ADD
        // ==============================
        function openAddModal() {
            // $('#customerModalTitle').text('Tambah Customer');
            // $('#customerForm')[0].reset();
            // $('#customerId').val('');

            // // Auto generate kode
            // $.get('/customers/generate/code', function(res) {
            //     $('#kode').val(res.kode);
            // });

            // customerModal.show();

            $.ajax({
                    url: "{{ route('customers.generate.code') }}",
                    type: "GET",
                    success: function(res) {
                        console.log("Generate Code:", res);

                        $('#customerModalTitle').text('Tambah Customer');
            $('#customerForm')[0].reset();
            $('#customerId').val('');
                        $("#kode").val(res.code);
                        customerModal.show();
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        alert("Gagal generate kode supplier!");
                    }
                });
        }

        // ==============================
        // EDIT CUSTOMER
        // ==============================
        function openEditModal(id) {
            $.get('/customers/' + id, function(c) {
                $('#customerModalTitle').text('Edit Customer');
                $('#customerId').val(c.id);
                $('#kode').val(c.kode);
                $('#nama').val(c.nama);
                $('#telepon').val(c.telepon);
                $('#email').val(c.email);
                $('#tipe').val(c.tipe);
                $('#member').val(c.member);
                $('#alamat').val(c.alamat);
                $('#kota').val(c.kota);
                $('#limit').val(c.limit || 0);
                $('#status').val(c.status);
                $('#catatan').val(c.catatan || '');

                customerModal.show();
            });
        }

        // ==============================
        // SAVE (STORE / UPDATE)
        // ==============================
        function saveCustomer() {
            const id = $('#customerId').val();
            // const form = $('#customerForm').serialize();
            const payload = {
    kode: $('#kode').val(),
    nama: $('#nama').val(),
    telepon: $('#telepon').val(),
    email: $('#email').val(),
    tipe: $('#tipe').val(),
    member: $('#member').val(),
    alamat: $('#alamat').val(),
    kota: $('#kota').val(),
    limit: $('#limit').val(),
    status: $('#status').val(),
    catatan: $('#catatan').val()
};

            const url = id ? '/customers/' + id : '/customers';
            const method = id ? 'PUT' : 'POST';

            $.ajax({
                url: url,
                type: method,
                data: payload,
                success: function() {
                    Swal.fire('Berhasil', 'Data customer disimpan.', 'success');
                    customerModal.hide();
                    loadCustomers();
                },
                error: function(err) {
                    Swal.fire('Error', 'Gagal menyimpan data.', 'error');
                }
            });
        }

        // ==============================
        // DELETE
        // ==============================
        function deleteCustomer(id, name) {
            Swal.fire({
                icon: 'warning',
                title: 'Hapus Customer?',
                text: 'Yakin menghapus ' + name + '?',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus'
            }).then(res => {
                if (!res.isConfirmed) return;

                $.ajax({
                    url: '/customers/' + id,
                    type: 'DELETE',
                    success: function() {
                        Swal.fire('Dihapus', 'Customer telah dihapus.', 'success');
                        loadCustomers();
                    }
                });
            });
        }

        // ==============================
        // DOCUMENT READY
        // ==============================
        $(function () {
            customerModal = new bootstrap.Modal($('#customerModal')[0]);

            $('#searchCustomer, #filterStatus, #filterType, #filterMember')
                .on('input change', applyFilters);

            $('#btnAddCustomer').on('click', openAddModal);
            $('#btnSaveCustomer').on('click', saveCustomer);

            $('#customerTable tbody').on('click', '.action-btn', function () {
                const id = $(this).data('id');
                const action = $(this).data('action');
                const c = customers.find(x => x.id == id);

                if (action === 'view') {
                    Swal.fire({
                        title: 'Detail Customer',
                        html: `
                            <div style="font-size:11px;text-align:left">
                                <b>Kode:</b> ${c.kode}<br>
                                <b>Nama:</b> ${c.nama}<br>
                                <b>Telepon:</b> ${c.telepon || '-'}<br>
                                <b>Email:</b> ${c.email || '-'}<br>
                                <b>Tipe:</b> ${c.tipe}<br>
                                <b>Member:</b> ${c.member}<br>
                                <b>Alamat:</b> ${c.alamat || '-'}<br>
                                <b>Kota:</b> ${c.kota || '-'}<br>
                                <b>Limit:</b> ${c.limit ? formatRupiah(c.limit) : '-'}<br>
                                <b>Status:</b> ${c.status}<br>
                                <b>Catatan:</b> ${c.catatan || '-'}
                            </div>
                        `
                    });
                }

                if (action === 'edit') openEditModal(id);
                if (action === 'delete') deleteCustomer(id, c.nama);
            });

            $('#pagination').on('click', '.page-link', function (e) {
                e.preventDefault();
                const page = $(this).data('page');
                if (!page) return;
                pagination.currentPage = page;
                renderPage();
            });

            loadCustomers();
        });
    </script>


    @endpush
@endsection
