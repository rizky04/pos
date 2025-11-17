@extends('layouts.apps')

@section('content')
 <section>
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <div class="page-header-title">Master Supplier</div>
                <div class="page-header-sub">
                    Kelola data pemasok untuk pembelian & hutang usaha.
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <button class="btn-soft light" id="btnRefresh">
                    <i class="bi bi-arrow-clockwise"></i> Reload
                </button>
                <button class="btn-soft" id="btnAddSupplier">
                    <i class="bi bi-plus-circle"></i> Tambah Supplier
                </button>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="filter-bar mt-2">
            <div class="filter-label">Filter</div>

            <div class="filter-search-wrap">
                <i class="bi bi-search"></i>
                <input type="text" id="searchSupplier" placeholder="Cari kode / nama / telepon / email">
            </div>

            <div>
                <select id="filterStatus">
                    <option value="">Semua Status</option>
                    <option value="active">Aktif</option>
                    <option value="nonactive">Non Aktif</option>
                </select>
            </div>

            <div>
                <select id="filterTerm">
                    <option value="">Semua Termin</option>
                    <option value="0">COD / Cash</option>
                    <option value="7">7 Hari</option>
                    <option value="14">14 Hari</option>
                    <option value="30">30 Hari</option>
                    <option value="45">45 Hari</option>
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
                <table class="table align-middle" id="supplierTable">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Kode</th>
                        <th>Nama Supplier</th>
                        <th>Kontak</th>
                        <th>Telepon</th>
                        <th>Email</th>
                        <th>Kota</th>
                        <th>Termin (Hari)</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    <!-- by JS -->
                    </tbody>
                </table>
            </div>
            <div id="emptyState" class="empty-state d-none">
                Belum ada supplier yang sesuai filter.
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
    <!-- MODAL TAMBAH / EDIT SUPPLIER -->
<div class="modal fade" id="supplierModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="supplierModalTitle">Tambah Supplier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" style="font-size:8px;"></button>
            </div>
            <div class="modal-body">
                <form id="supplierForm">
                    <input type="hidden" id="supplierId">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <label class="form-label">Kode Supplier</label>
                            <input type="text" id="kode" class="form-control" required>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Nama Supplier</label>
                            <input type="text" id="nama" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Contact Person</label>
                            <input type="text" id="kontak" class="form-control">
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
                            <label class="form-label">Kota</label>
                            <input type="text" id="kota" class="form-control">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Alamat</label>
                            <input type="text" id="alamat" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Termin Pembayaran (hari)</label>
                            <input type="number" id="termin" class="form-control" min="0" value="0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">NPWP / Tax ID (opsional)</label>
                            <input type="text" id="npwp" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select id="status" class="form-select">
                                <option value="active">Aktif</option>
                                <option value="nonactive">Non Aktif</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn-soft light" data-bs-dismiss="modal">Batal</button>
                <button class="btn-soft" id="btnSaveSupplier">Simpan</button>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
    let pagination = {
        perPage: 8,
        currentPage: 1,
    };

    let supplierModal;

    function loadSuppliers() {
        const search = $('#searchSupplier').val();
        const status = $('#filterStatus').val();
        const termin = $('#filterTerm').val();

        $.ajax({
            url: "{{ route('suppliers.data') }}",
            method: "GET",
            data: {
                search,
                status,
                termin,
                per_page: pagination.perPage,
                page: pagination.currentPage
            },
            success: function(res) {
                console.log("datane",res);
                renderTable(res.data);
                renderPagination(res);
            },
            error: function(xhr) {
                console.error(xhr);
            }
        });
    }


    /* =====================================================
     * RENDER TABLE
     * ===================================================== */
    function renderTable(data) {
        const tbody = $("#supplierTable tbody");
        tbody.empty();

        if (data.length === 0) {
            $("#emptyState").removeClass("d-none");
            return;
        } else {
            $("#emptyState").addClass("d-none");
        }

        data.forEach((s, i) => {
            const badge = s.status === "active"
                ? '<span class="badge-status badge-active">Aktif</span>'
                : '<span class="badge-status badge-nonactive">Non Aktif</span>';

            tbody.append(`
                <tr>
                    <td>${i + 1}</td>
                    <td><strong>${s.kode_supplier}</strong></td>
                    <td>${s.nama_supplier}</td>
                    <td>${s.contact_person ?? '-'}</td>
                    <td>${s.telepon ?? '-'}</td>
                    <td>${s.email ?? '-'}</td>
                    <td>${s.kota ?? '-'}</td>
                    <td>${s.termin_pembayaran}</td>
                    <td>${badge}</td>
                    <td class="text-end">
                        <button class="action-btn" data-action="view" data-id="${s.id}">
                            <i class="bi bi-eye-fill"></i>
                        </button>
                        <button class="action-btn" data-action="edit" data-id="${s.id}">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        <button class="action-btn" data-action="delete" data-id="${s.id}">
                            <i class="bi bi-trash3-fill"></i>
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

        // prev
        pag.append(`
            <li class="page-item ${res.current_page == 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="${res.current_page - 1}">«</a>
            </li>
        `);

        // numbers
        for (let p = 1; p <= res.last_page; p++) {
            pag.append(`
                <li class="page-item ${p == res.current_page ? 'active' : ''}">
                    <a class="page-link" href="#" data-page="${p}">${p}</a>
                </li>
            `);
        }

        // next
        pag.append(`
            <li class="page-item ${res.current_page == res.last_page ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="${res.current_page + 1}">»</a>
            </li>
        `);
    }


    /* =====================================================
     * MODAL ADD
     * ===================================================== */
    function openAddModal() {
        $("#supplierModalTitle").text("Tambah Supplier");
        $("#supplierForm")[0].reset();
        $("#supplierId").val("");
        supplierModal.show();
    }

    /* =====================================================
     * MODAL EDIT
     * ===================================================== */
    function openEditModal(id) {
        $.get(`/suppliers/${id}`, function(s) {
            $("#supplierModalTitle").text("Edit Supplier");
            $("#supplierId").val(s.id);

            $("#kode").val(s.kode_supplier);
            $("#nama").val(s.nama_supplier);
            $("#kontak").val(s.contact_person);
            $("#telepon").val(s.telepon);
            $("#email").val(s.email);
            $("#alamat").val(s.alamat);
            $("#kota").val(s.kota);
            $("#termin").val(s.termin_pembayaran);
            $("#npwp").val(s.npwp);
            $("#status").val(s.status);

            supplierModal.show();
        });
    }


    /* =====================================================
     * SAVE (CREATE/UPDATE)
     * ===================================================== */
    function saveSupplier() {
        const id = $("#supplierId").val();

        const payload = {
            kode_supplier: $("#kode").val(),
            nama_supplier: $("#nama").val(),
            contact_person: $("#kontak").val(),
            telepon: $("#telepon").val(),
            email: $("#email").val(),
            alamat: $("#alamat").val(),
            kota: $("#kota").val(),
            termin_pembayaran: $("#termin").val(),
            npwp: $("#npwp").val(),
            status: $("#status").val(),
        };

        let url = "/suppliers";
        let method = "POST";

        if (id) {
            url = `/suppliers/${id}`;
            method = "PUT";
        }

        $.ajax({
            url,
            type: method,
            data: payload,
            success: function(res) {
                Swal.fire("Berhasil", res.message, "success");
                supplierModal.hide();
                loadSuppliers();
            },
            error: function(xhr) {
                Swal.fire("Error", xhr.responseJSON.message, "error");
            }
        });
    }


    /* =====================================================
     * DELETE
     * ===================================================== */
    function deleteSupplier(id) {
        Swal.fire({
            icon: "warning",
            title: "Hapus Supplier",
            text: "Yakin ingin menghapus data ini?",
            showCancelButton: true,
            confirmButtonText: "Ya, hapus"
        }).then(res => {
            if (!res.isConfirmed) return;

            $.ajax({
                url: `/suppliers/${id}`,
                type: "DELETE",
                success: function(result) {
                    Swal.fire("Dihapus", result.message, "success");
                    loadSuppliers();
                }
            });
        });
    }


    /* =====================================================
     * ON READY
     * ===================================================== */
    $(function() {

        supplierModal = new bootstrap.Modal(document.getElementById("supplierModal"));

        // LOAD AWAL
        loadSuppliers();

        // FILTER
        $("#searchSupplier, #filterStatus, #filterTerm").on("input change", function() {
            pagination.currentPage = 1;
            loadSuppliers();
        });

        // PAGINATION CLICK
        $("#pagination").on("click", ".page-link", function(e) {
            e.preventDefault();
            const page = $(this).data("page");
            if (page) {
                pagination.currentPage = page;
                loadSuppliers();
            }
        });

        // Add
        $("#btnAddSupplier").on("click", openAddModal);

        // Save
        $("#btnSaveSupplier").on("click", saveSupplier);

        // ACTION BUTTONS
        $("#supplierTable").on("click", ".action-btn", function() {
            const id = $(this).data("id");
            const action = $(this).data("action");

            if (action === "edit") openEditModal(id);
            if (action === "delete") deleteSupplier(id);

            if (action === "view") {
                $.get(`/suppliers/${id}`, function(s) {
                    Swal.fire({
                        title: "Detail Supplier",
                        html: `
                            <b>Kode:</b> ${s.kode_supplier}<br>
                            <b>Nama:</b> ${s.nama_supplier}<br>
                            <b>Kontak:</b> ${s.contact_person ?? '-'}<br>
                            <b>Telepon:</b> ${s.telepon ?? '-'}<br>
                            <b>Email:</b> ${s.email ?? '-'}<br>
                            <b>Alamat:</b> ${s.alamat ?? '-'}<br>
                            <b>Kota:</b> ${s.kota ?? '-'}<br>
                            <b>Termin:</b> ${s.termin_pembayaran} hari<br>
                            <b>NPWP:</b> ${s.npwp ?? '-'}<br>
                            <b>Status:</b> ${s.status}
                        `,
                    });
                });
            }
        });

        // REFRESH
        $("#btnRefresh").on("click", function() {
            $("#searchSupplier").val('');
            $("#filterStatus").val('');
            $("#filterTerm").val('');
            pagination.currentPage = 1;
            loadSuppliers();
        });
    });
</script>

@endpush
@endsection
