@extends('layouts.apps')

@section('content')

    <!-- MAIN CONTENT -->
    <section>
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <div class="page-header-title">Master Gudang</div>
                <div class="page-header-sub">
                    Kelola daftar gudang, outlet, dan lokasi penyimpanan stok.
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <button class="btn-soft light" id="btnRefresh">
                    <i class="bi bi-arrow-clockwise"></i> Reload
                </button>
                <button class="btn-soft" id="btnAdd">
                    <i class="bi bi-plus-circle"></i> Tambah Gudang
                </button>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="filter-bar mt-2">
            <div class="filter-label">Filter</div>

            <div class="filter-search-wrap">
                <i class="bi bi-search"></i>
                <input type="text" id="searchGudang" placeholder="Cari kode / nama / kota / PIC">
            </div>

            <div>
                <select id="filterType">
                    <option value="">Semua Tipe</option>
                    <option value="utama">Gudang Utama</option>
                    <option value="outlet">Outlet / Toko</option>
                    <option value="produksi">Produksi / Dapur</option>
                    <option value="lain">Lainnya</option>
                </select>
            </div>

            <div>
                <select id="filterStatus">
                    <option value="">Semua Status</option>
                    <option value="active">Aktif</option>
                    <option value="nonactive">Non Aktif</option>
                </select>
            </div>

            <div>
                <select id="filterDefault">
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
                <table class="table align-middle" id="gudangTable">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Kode</th>
                        <th>Nama Gudang</th>
                        <th>Tipe</th>
                        <th>Kota</th>
                        <th>Alamat</th>
                        <th>PIC</th>
                        <th>Telepon</th>
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

            <!-- Pagination -->
            <div class="pagination-container">
                <div id="paginationInfo"></div>
                <nav>
                    <ul class="pagination pagination-sm mb-0" id="pagination"></ul>
                </nav>
            </div>
        </div>
    </section>


<div class="modal fade" id="gudangModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="gudangModalTitle">Tambah Gudang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" style="font-size:8px;"></button>
            </div>
            <div class="modal-body">
                <form id="gudangForm">
                    <input type="hidden" id="gudangId">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <label class="form-label">Kode Gudang</label>
                            <input type="text" id="kode" class="form-control" required>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Nama Gudang</label>
                            <input type="text" id="nama" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Tipe</label>
                            <select id="tipe" class="form-select" required>
                                <option value="utama">Gudang Utama</option>
                                <option value="outlet">Outlet / Toko</option>
                                <option value="produksi">Produksi / Dapur</option>
                                <option value="lain">Lainnya</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Kota</label>
                            <input type="text" id="kota" class="form-control">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Alamat</label>
                            <input type="text" id="alamat" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Penanggung Jawab (PIC)</label>
                            <input type="text" id="pic" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Telepon</label>
                            <input type="text" id="telepon" class="form-control">
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
                                <input class="form-check-input" type="checkbox" id="isDefault">
                                <label class="form-check-label" for="isDefault" style="font-size:10px;">
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
                <button class="btn-soft" id="btnSaveGudang">Simpan</button>
            </div>
        </div>
    </div>
</div>
    @push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    $(document).ready(function () {

        let currentPage = 1;

        // -----------------------------
        // LOAD DATA TABLE
        // -----------------------------
        function loadData(page = 1) {
            currentPage = page;

            $.ajax({
                url: `/warehouse?page=${page}`,
                method: "GET",
                success: function (res) {

                    let tbody = $("#gudangTable tbody");
                    tbody.empty();

                    if (res.data.length === 0) {
                        $("#emptyState").removeClass("d-none");
                        return;
                    } else {
                        $("#emptyState").addClass("d-none");
                    }

                    $.each(res.data, function (i, row) {
                        tbody.append(`
                            <tr>
                                <td>${res.from + i}</td>
                                <td>${row.kode}</td>
                                <td>${row.nama}</td>
                                <td><span class="badge-status badge-type">${row.type}</span></td>
                                <td>${row.kota ?? "-"}</td>
                                <td>${row.alamat ?? "-"}</td>
                                <td>${row.pic ?? "-"}</td>
                                <td>${row.telepon ?? "-"}</td>
                                <td>
                                    <span class="badge-status ${row.status === 'active' ? 'badge-active' : 'badge-nonactive'}">
                                        ${row.status}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <button class="action-btn btnEdit" data-id="${row.id}">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <button class="action-btn btnDelete" data-id="${row.id}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        `);
                    });

                    loadPagination(res);
                }
            });
        }


        // -----------------------------
        // PAGINATION
        // -----------------------------
        function loadPagination(res) {
            let pagination = $("#pagination");
            pagination.empty();

            $("#paginationInfo").html(
                `Menampilkan ${res.from} - ${res.to} dari ${res.total} data`
            );

            // Prev
            pagination.append(`
                <li class="page-item ${res.prev_page_url ? '' : 'disabled'}">
                    <a class="page-link" href="#" data-page="${res.current_page - 1}">Prev</a>
                </li>
            `);

            // Number
            for (let i = 1; i <= res.last_page; i++) {
                pagination.append(`
                    <li class="page-item ${res.current_page === i ? 'active' : ''}">
                        <a class="page-link" href="#" data-page="${i}">${i}</a>
                    </li>
                `);
            }

            // Next
            pagination.append(`
                <li class="page-item ${res.next_page_url ? '' : 'disabled'}">
                    <a class="page-link" href="#" data-page="${res.current_page + 1}">Next</a>
                </li>
            `);
        }


        // Pagination Action
        $(document).on("click", "#pagination .page-link", function (e) {
            e.preventDefault();
            let page = $(this).data("page");
            loadData(page);
        });


        // -----------------------------
        // OPEN MODAL ADD
        // -----------------------------
        $("#btnAdd").click(function () {
            $("#gudangModalTitle").text("Tambah Gudang");
            $("#gudangForm")[0].reset();
            $("#gudangId").val("");
            $("#gudangModal").modal("show");
        });


        // -----------------------------
        // SAVE DATA (Add/Update)
        // -----------------------------
        $("#gudangForm").submit(function (e) {
            e.preventDefault();

            let id = $("#gudangId").val();
            let method = id ? "PUT" : "POST";
            let url = id ? `/warehouse/${id}` : "/warehouse";

            let data = {
                kode: $("#kode").val(),
                nama: $("#nama").val(),
                type: $("#type").val(),
                kota: $("#kota").val(),
                alamat: $("#alamat").val(),
                pic: $("#pic").val(),
                telepon: $("#telepon").val(),
                status: $("#status").val(),
                is_default: $("#is_default").val(),
                is_active: $("#status").val() === "active" ? 1 : 0
            };

            $.ajax({
                url: url,
                method: method,
                data: data,
                success: function () {
                    $("#gudangModal").modal("hide");
                    loadData(currentPage);
                }
            });
        });


        // -----------------------------
        // EDIT
        // -----------------------------
        $(document).on("click", ".btnEdit", function () {
            let id = $(this).data("id");

            $.ajax({
                url: `/warehouse/${id}`,
                method: "GET",
                success: function (res) {

                    $("#gudangModalTitle").text("Edit Gudang");
                    $("#gudangId").val(res.id);

                    $("#kode").val(res.kode);
                    $("#nama").val(res.nama);
                    $("#type").val(res.type);
                    $("#kota").val(res.kota);
                    $("#alamat").val(res.alamat);
                    $("#pic").val(res.pic);
                    $("#telepon").val(res.telepon);
                    $("#status").val(res.status);
                    $("#is_default").val(res.is_default);

                    $("#gudangModal").modal("show");
                }
            });
        });


        // -----------------------------
        // DELETE
        // -----------------------------
        $(document).on("click", ".btnDelete", function () {
            let id = $(this).data("id");

            if (!confirm("Yakin ingin menghapus gudang ini?")) return;

            $.ajax({
                url: `/warehouse/${id}`,
                method: "DELETE",
                success: function () {
                    loadData(currentPage);
                }
            });
        });


        // -----------------------------
        // SEARCH + FILTER
        // -----------------------------
        $("#searchGudang, #filterType, #filterStatus, #filterDefault").on("input change", function () {
            loadData();
        });


        // -----------------------------
        // REFRESH BUTTON
        // -----------------------------
        $("#btnRefresh").click(function () {
            $("#searchGudang").val("");
            $("#filterType").val("");
            $("#filterStatus").val("");
            $("#filterDefault").val("");
            loadData(1);
        });


        // INITIAL LOAD
        loadData();

    });
</script>

    @endpush
@endsection
