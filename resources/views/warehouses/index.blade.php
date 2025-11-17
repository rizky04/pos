@extends('layouts.apps')

@section('content')
<section>
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-start">
        <div>
            <div class="page-header-title">Master Warehouse</div>
            <div class="page-header-sub">
                Kelola data gudang untuk penyimpanan barang.
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <button class="btn-soft light" id="btnRefresh">
                <i class="bi bi-arrow-clockwise"></i> Reload
            </button>
            <button class="btn-soft" id="btnAddWarehouse">
                <i class="bi bi-plus-circle"></i> Tambah Gudang
            </button>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="filter-bar mt-2">
        <div class="filter-label">Filter</div>

        <div class="filter-search-wrap">
            <i class="bi bi-search"></i>
            <input type="text" id="searchWarehouse" placeholder="Cari kode / nama / penanggung jawab">
        </div>

        <div>
            <select id="filterStatus">
                <option value="">Semua Status</option>
                <option value="active">Aktif</option>
                <option value="nonactive">Non Aktif</option>
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
            <table class="table align-middle" id="warehouseTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Kode</th>
                        <th>Nama Gudang</th>
                        <th>Penanggung Jawab</th>
                        <th>Telepon</th>
                        <th>Kota</th>
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
            Belum ada gudang yang sesuai filter.
        </div>

        <div class="pagination-container">
            <div id="paginationInfo"></div>
            <nav>
                <ul class="pagination pagination-sm mb-0" id="pagination"></ul>
            </nav>
        </div>
    </div>
</section>

<!-- MODAL CREATE & EDIT -->
<div class="modal fade" id="warehouseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="warehouseModalTitle">Tambah Gudang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" style="font-size: 8px;"></button>
            </div>
            <div class="modal-body">
                <form id="warehouseForm">
                    <input type="hidden" id="warehouseId">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <label class="form-label">Kode Gudang</label>
                            <input type="text" id="code" class="form-control" required>
                        </div>

                        <div class="col-md-8">
                            <label class="form-label">Nama Gudang</label>
                            <input type="text" id="name" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Tipe</label>
                            <select id="type" class="form-select" required>
                                <option value="utama">Gudang Utama</option>
                                <option value="outlet">Outlet / Toko</option>
                                <option value="produksi">Produksi / Dapur</option>
                                <option value="lain">Lainnya</option>
                            </select>
                        </div>
                         <div class="col-md-6">
                            <label class="form-label">Kota</label>
                            <input type="text" id="city" class="form-control">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Alamat</label>
                            <input type="text" id="address" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Penanggung Jawab</label>
                            <input type="text" id="pic" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Telepon</label>
                            <input type="text" id="phone" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select id="status" class="form-select">
                                <option value="active">Aktif</option>
                                <option value="nonactive">Non Aktif</option>
                            </select>
                        </div>

                        <div class="col-md-6 d-flex align-items-center">
                            <div class="form-check mt-3">
                                <input class="form-check-input" type="checkbox" id="is_default">
                                <label class="form-check-label" for="is_default" style="font-size:10px;">
                                    Jadikan gudang utama (default)
                                </label>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Catatan</label>
                            <input type="text" id="catatan" class="form-control" placeholder="Jam operasional / keterangan lain">
                        </div>

                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button class="btn-soft light" data-bs-dismiss="modal">Batal</button>
                <button class="btn-soft" id="btnSaveWarehouse">Simpan</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let pagination = { perPage: 8, currentPage: 1 };
    let warehouseModal;

    /* ======================================================
     * LOAD DATA
     * ====================================================== */
    function loadWarehouses() {
        $.ajax({
            url: "{{ route('warehouses.data') }}",
            type: "GET",
            data: {
                search: $('#searchWarehouse').val(),
                status: $('#filterStatus').val(),
                per_page: pagination.perPage,
                page: pagination.currentPage,
            },
            success: function(res) {
                renderTable(res.data);
                renderPagination(res);
            }
        });
    }

    /* ======================================================
     * RENDER TABLE
     * ====================================================== */
    function renderTable(data) {
        const tbody = $("#warehouseTable tbody");
        tbody.empty();

        if (data.length === 0) {
            $("#emptyState").removeClass("d-none");
            return;
        } else {
            $("#emptyState").addClass("d-none");
        }

        data.forEach((w, i) => {
            const badge = w.status === "active"
                ? '<span class="badge-status badge-active">Aktif</span>'
                : '<span class="badge-status badge-nonactive">Non Aktif</span>';

            tbody.append(`
                <tr>
                    <td>${i + 1}</td>
                    <td><strong>${w.code}</strong></td>
                    <td>${w.name}</td>
                    <td>${w.pic ?? '-'}</td>
                    <td>${w.phone ?? '-'}</td>
                    <td>${w.city ?? '-'}</td>
                    <td>${badge}</td>
                    <td class="text-end">
                        <button class="action-btn" data-action="view" data-id="${w.id}">
                            <i class="bi bi-eye-fill"></i>
                        </button>
                        <button class="action-btn" data-action="edit" data-id="${w.id}">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        <button class="action-btn" data-action="delete" data-id="${w.id}">
                            <i class="bi bi-trash3-fill"></i>
                        </button>
                    </td>
                </tr>
            `);
        });
    }

    /* ======================================================
     * PAGINATION
     * ====================================================== */
    function renderPagination(res) {
        const pag = $("#pagination");
        const info = $("#paginationInfo");

        pag.empty();

        info.text(`Menampilkan ${res.from} - ${res.to} dari ${res.total} data (Hal ${res.current_page} / ${res.last_page})`);

        pag.append(`
            <li class="page-item ${res.current_page == 1 ? 'disabled' : ''}">
                <a class="page-link" data-page="${res.current_page - 1}">«</a>
            </li>
        `);

        for (let p = 1; p <= res.last_page; p++) {
            pag.append(`
                <li class="page-item ${p == res.current_page ? 'active' : ''}">
                    <a class="page-link" data-page="${p}">${p}</a>
                </li>
            `);
        }

        pag.append(`
            <li class="page-item ${res.current_page == res.last_page ? 'disabled' : ''}">
                <a class="page-link" data-page="${res.current_page + 1}">»</a>
            </li>
        `);
    }

    /* ======================================================
     * OPEN ADD MODAL
     * ====================================================== */
    function openAddModal() {
        // $.get("{{ route('warehouses.generate.code') }}", res => {
        //     $("#warehouseModalTitle").text("Tambah Gudang");
        //     $("#warehouseForm")[0].reset();
        //     $("#warehouseId").val('');
        //     $("#kode_warehouse").val(res.code);
        //     warehouseModal.show();
        // });

        $.ajax({
                    url: "{{ route('warehouses.generate.code') }}",
                    type: "GET",
                    success: function(res) {
                        console.log("Generate Code:", res);

                        $("#warehouseModalTitle").text("Tambah Gudang");
            $("#warehouseForm")[0].reset();
            $("#warehouseId").val('');
            $("#code").val(res.code);
            $("#is_default").prop("checked", false);
            warehouseModal.show();
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        alert("Gagal generate kode warehouse!");
                    }
                });
    }

    /* ======================================================
     * OPEN EDIT MODAL
     * ====================================================== */
    // function openEditModal(id) {
    //     $.get(`/warehouses/${id}`, w => {
    //         $("#warehouseModalTitle").text("Edit Gudang");

    //         $("#warehouseId").val(w.id);

    //         $("#code").val(res.code);
    //         $("#name").val(res.name);
    //         $("#type").val(res.type);
    //         $("#city").val(res.city);
    //         $("#address").val(res.address);
    //         $("#pic").val(res.pic);
    //         $("#phone").val(res.phone);
    //         $("#status").val(res.status);
    //         $("#is_default").prop('checked', res.is_default);

    //         warehouseModal.show();
    //     });
    // }
    function openEditModal(id) {
    $.get(`/warehouses/${id}`, function(w) {
        $("#warehouseModalTitle").text("Edit Gudang");

        $("#warehouseId").val(w.id);

        $("#code").val(w.code);
        $("#name").val(w.name);
        $("#type").val(w.type);
        $("#city").val(w.city);
        $("#address").val(w.address);
        $("#pic").val(w.pic);
        $("#phone").val(w.phone);
        $("#status").val(w.status);
        $("#is_default").prop("checked", w.is_default == 1);

        warehouseModal.show();
    }).fail(function(xhr){
        console.error(xhr.responseText);
        alert("Gagal mengambil data warehouse!");
    });
}


    /* ======================================================
     * SAVE
     * ====================================================== */
    function saveWarehouse() {
        const id = $("#warehouseId").val();

        const payload = {
            code: $("#code").val(),
            name: $("#name").val(),
            type: $("#type").val(),
            city: $("#city").val(),
            address: $("#address").val(),
            pic: $("#pic").val(),
            phone: $("#phone").val(),
            status: $("#status").val(),
            is_default: $("#is_default").is(':checked') ? 1 : 0,
        };

        let url = "/warehouses";
        let method = "POST";

        if (id) {
            url = `/warehouses/${id}`;
            method = "PUT";
        }

        $.ajax({
            url,
            type: method,
            data: payload,
            success: function(res) {
                Swal.fire("Berhasil", res.message, "success");
                warehouseModal.hide();
                loadWarehouses();
            }
        });
    }

    /* ======================================================
     * DELETE
     * ====================================================== */
    function deleteWarehouse(id) {
        Swal.fire({
            icon: "warning",
            title: "Hapus Gudang",
            text: "Yakin ingin menghapus data ini?",
            showCancelButton: true,
            confirmButtonText: "Ya, hapus"
        }).then(result => {
            if (!result.isConfirmed) return;

            $.ajax({
                url: `/warehouses/${id}`,
                type: "DELETE",
                success: function(res) {
                    Swal.fire("Dihapus", res.message, "success");
                    loadWarehouses();
                }
            });
        });
    }

    /* ======================================================
     * ON DOCUMENT READY
     * ====================================================== */
    $(function() {
        warehouseModal = new bootstrap.Modal(document.getElementById("warehouseModal"));

        loadWarehouses();

        $("#searchWarehouse, #filterStatus").on("input change", function() {
            pagination.currentPage = 1;
            loadWarehouses();
        });

        $("#pagination").on("click", ".page-link", function(e) {
            e.preventDefault();
            pagination.currentPage = $(this).data("page");
            loadWarehouses();
        });

        $("#btnAddWarehouse").on("click", openAddModal);
        $("#btnSaveWarehouse").on("click", saveWarehouse);
        $("#btnRefresh").on("click", function() {
            $("#searchWarehouse").val('');
            $("#filterStatus").val('');
            pagination.currentPage = 1;
            loadWarehouses();
        });

        $("#warehouseTable").on("click", ".action-btn", function() {
            const id = $(this).data("id");
            const action = $(this).data("action");

            if (action === "edit") openEditModal(id);
            if (action === "delete") deleteWarehouse(id);
            if (action === "view") {
                $.get(`/warehouses/${id}`, w => {
                    Swal.fire({
                        title: "Detail Warehouse",
                        html: `
                            <b>Kode:</b> ${w.code}<br>
                            <b>Nama:</b> ${w.name}<br>
                            <b>Tipe:</b> ${w.type ?? '-'}<br>
                            <b>Kota:</b> ${w.city ?? '-'}<br>
                             <b>Alamat:</b> ${w.address ?? '-'}<br>
                            <b>PIC:</b> ${w.pic ?? '-'}<br>
                            <b>Telepon:</b> ${w.phone ?? '-'}<br>
                            <b>Status:</b> ${w.status}`
                    });
                });
            }
        });
    });
</script>
@endpush

@endsection
