@extends('layouts.apps')

@section('content')
  <section>
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <div class="page-header-title">Master Satuan</div>
                <div class="page-header-sub">
                    Atur daftar satuan (pcs, box, carton, cup, liter, dll) untuk digunakan di Master Barang.
                </div>
            </div>
            <div class="d-flex gap-2">
                <button class="btn-soft light" id="btnRefresh">
                    <i class="bi bi-arrow-clockwise"></i> Reload
                </button>
                <button class="btn-soft" id="btnAddUnit">
                    <i class="bi bi-plus-circle"></i> Tambah Satuan
                </button>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="filter-bar mt-2">
            <div class="filter-label">Filter</div>

            <div class="filter-search-wrap">
                <i class="bi bi-search"></i>
                <input type="text" id="searchUnit" placeholder="Cari nama / singkatan satuan">
            </div>

            <div>
                <select id="filterStatus">
                    <option value="">Semua Status</option>
                    <option value="active">Aktif</option>
                    <option value="nonactive">Non Aktif</option>
                </select>
            </div>

            <div>
                <select id="filterIsDefault">
                    <option value="">Default / Tidak</option>
                    <option value="yes">Hanya Default</option>
                    <option value="no">Tanpa Default</option>
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
                <table class="table align-middle" id="unitTable">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Satuan</th>
                        <th>Singkatan</th>
                        <th>Tipe</th>
                        <th>Deskripsi</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div id="emptyState" class="empty-state d-none">
                Belum ada satuan yang sesuai filter.
            </div>

            <div class="pagination-container">
                <div id="paginationInfo"></div>
                <nav>
                    <ul class="pagination pagination-sm mb-0" id="pagination"></ul>
                </nav>
            </div>
        </div>
    </section>
    <div class="modal fade" id="unitModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="unitModalTitle">Tambah Satuan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" style="font-size:8px;"></button>
            </div>
            <div class="modal-body">
                <form id="unitForm">
                    <input type="hidden" id="unitId">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="form-label">Nama Satuan</label>
                            <input type="text" id="nama" class="form-control" placeholder="Pieces" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Singkatan</label>
                            <input type="text" id="kode" class="form-control" placeholder="pcs" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tipe</label>
                            <select id="tipe" class="form-select">
                                <option value="unit">Unit</option>
                                <option value="volume">Volume</option>
                                <option value="berat">Berat</option>
                                <option value="lain">Lainnya</option>
                            </select>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Deskripsi</label>
                            <input type="text" id="deskripsi" class="form-control" placeholder="Opsional">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Status</label>
                            <select id="status" class="form-select">
                                <option value="active">Aktif</option>
                                <option value="nonactive">Non Aktif</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <div class="form-check mt-1">
                                <input class="form-check-input" type="checkbox" id="isDefault">
                                <label class="form-check-label" for="isDefault" style="font-size:10px;">
                                    Jadikan default (sering digunakan)
                                </label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn-soft light" data-bs-dismiss="modal">Batal</button>
                <button class="btn-soft" id="btnSaveUnit">Simpan</button>
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

    let unitModal;

    function loadUnits() {
        const search = $('#searchUnit').val();
        const status = $('#filterStatus').val();
        const isDefault = $('#filterIsDefault').val();

        $.ajax({
            url: "/units/data",
            method: "GET",
            data: {
                search,
                status,
                is_default: isDefault,
                per_page: pagination.perPage,
                page: pagination.currentPage
            },
            success: function(res) {
                renderTable(res.data);
                renderPagination(res);
            },
            error: function(xhr) {
                console.error(xhr);
            }
        });
    }

    function renderTable(data) {
        const tbody = $("#unitTable tbody");
        tbody.empty();

        if (data.length === 0) {
            $("#emptyState").removeClass("d-none");
            return;
        } else {
            $("#emptyState").addClass("d-none");
        }

        data.forEach((u, i) => {
            const badgeStatus = u.status === "active"
                ? `<span class="badge-status badge-active">Aktif</span>`
                : `<span class="badge-status badge-nonactive">Non Aktif</span>`;

            const def = u.is_default
                ? `<span class="badge-status" style="background:#f97316;color:#fff;font-size:8px;margin-left:4px;">Default</span>`
                : '';

            tbody.append(`
                <tr>
                    <td>${i + 1}</td>
                    <td>${u.nama}${def}</td>
                    <td><strong>${u.kode}</strong></td>
                    <td>${u.tipe}</td>
                    <td>${u.deskripsi || '-'}</td>
                    <td>${badgeStatus}</td>
                    <td class="text-end">
                        <button class="action-btn" data-action="edit" data-id="${u.id}">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        <button class="action-btn" data-action="delete" data-id="${u.id}">
                            <i class="bi bi-trash3-fill"></i>
                        </button>
                    </td>
                </tr>
            `);
        });
    }

    function renderPagination(res) {
        const pag = $("#pagination");
        const info = $("#paginationInfo");

        pag.empty();

        info.text(
            `Menampilkan ${res.from ?? 0} - ${res.to ?? 0} dari ${res.total} data (Hal ${res.current_page} / ${res.last_page})`
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

    function openAddModal() {
        $("#unitModalTitle").text("Tambah Satuan");
        $("#unitForm")[0].reset();
        $("#unitId").val("");
        $("#status").val("active");
        $("#isDefault").prop("checked", false);
        unitModal.show();
    }

    function openEditModal(id) {
        $.get(`/units/${id}`, function(u) {
            $("#unitModalTitle").text("Edit Satuan");
            $("#unitId").val(u.id);

            $("#nama").val(u.nama);
            $("#kode").val(u.kode);
            $("#tipe").val(u.tipe);
            $("#deskripsi").val(u.deskripsi);
            $("#status").val(u.status);
            $("#isDefault").prop('checked', u.is_default);

            unitModal.show();
        });
    }

    function saveUnit() {
        const id = $("#unitId").val();

        const payload = {
            nama: $("#nama").val(),
            kode: $("#kode").val(),
            tipe: $("#tipe").val(),
            deskripsi: $("#deskripsi").val(),
            status: $("#status").val(),
            is_default: $("#isDefault").is(':checked') ? 1 : 0,
        };

        let url = "/units";
        let method = "POST";

        if (id) {
            url = `/units/${id}`;
            method = "PUT";
        }

        $.ajax({
            url,
            type: method,
            data: payload,
            success: function(res) {
                Swal.fire("Berhasil", res.message, "success");
                unitModal.hide();
                loadUnits();
            },
            error: function(xhr) {
                Swal.fire("Error", xhr.responseJSON.message, "error");
            }
        });
    }

    function deleteUnit(id) {
        Swal.fire({
            icon: "warning",
            title: "Hapus Satuan",
            text: "Yakin ingin menghapus satuan ini?",
            showCancelButton: true,
            confirmButtonText: "Ya, hapus"
        }).then(res => {
            if (!res.isConfirmed) return;

            $.ajax({
                url: `/units/${id}`,
                type: "DELETE",
                success: function(result) {
                    Swal.fire("Dihapus", result.message, "success");
                    loadUnits();
                }
            });
        });
    }

    $(function() {
        unitModal = new bootstrap.Modal(document.getElementById("unitModal"));

        loadUnits();

        $("#searchUnit, #filterStatus, #filterIsDefault").on("input change", function() {
            pagination.currentPage = 1;
            loadUnits();
        });

        $("#pagination").on("click", ".page-link", function(e) {
            e.preventDefault();
            const page = $(this).data("page");
            if (page) {
                pagination.currentPage = page;
                loadUnits();
            }
        });

        $("#btnAddUnit").on("click", openAddModal);
        $("#btnSaveUnit").on("click", saveUnit);

        $("#unitTable").on("click", ".action-btn", function() {
            const id = $(this).data("id");
            const action = $(this).data("action");

            if (action === "edit") openEditModal(id);
            if (action === "delete") deleteUnit(id);
        });

        $("#btnRefresh").on("click", function() {
            $("#searchUnit").val('');
            $("#filterStatus").val('');
            $("#filterIsDefault").val('');
            pagination.currentPage = 1;
            loadUnits();
        });
    });
</script>


@endpush
@endsection
