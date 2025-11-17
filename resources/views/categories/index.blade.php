@extends('layouts.apps')

@section('content')

  <section>
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <div class="page-header-title">Master Kategori</div>
                <div class="page-header-sub">Kelola kategori barang untuk pengelompokan produk.</div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <button class="btn-soft light" id="btnRefresh">
                    <i class="bi bi-arrow-clockwise"></i> Reload
                </button>
                <button class="btn-soft" id="btnAddCategory">
                    <i class="bi bi-plus-circle"></i> Tambah Kategori
                </button>
            </div>
        </div>

        <!-- Filter / Tools -->
        <div class="filter-bar mt-2">
            <div class="filter-label">Filter</div>

            <div class="filter-search-wrap">
                <i class="bi bi-search"></i>
                <input type="text" id="searchCategory" placeholder="Cari kode / nama kategori">
            </div>

            <div>
                <select id="filterStatus">
                    <option value="">Semua Status</option>
                    <option value="active">Aktif</option>
                    <option value="nonactive">Non Aktif</option>
                </select>
            </div>

            <div class="ms-auto d-flex gap-1">
                <button class="btn-soft light" id="btnExport">
                    <i class="bi bi-download"></i> Export
                </button>
            </div>
        </div>

        <!-- Table -->
        <div class="table-wrap">
            <div class="table-responsive">
                <table class="table align-middle" id="categoryTable">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Kode</th>
                        <th>Nama Kategori</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    <!-- Filled by JS -->
                    </tbody>
                </table>
            </div>
            <div id="emptyState" class="empty-state d-none">
                Belum ada kategori yang sesuai filter.
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
    <!-- MODAL TAMBAH / EDIT KATEGORI -->
<div class="modal fade" id="categoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="categoryModalTitle">Tambah Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        style="font-size:8px;"></button>
            </div>
            <div class="modal-body">
                <form id="categoryForm">
                    <input type="hidden" id="categoryId">
                    <div class="mb-2">
                        <label class="form-label">Kode Kategori</label>
                        <input type="text" id="kodeKategori" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Nama Kategori</label>
                        <input type="text" id="namaKategori" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Status</label>
                        <select id="statusKategori" class="form-select" required>
                            <option value="active">Aktif</option>
                            <option value="nonactive">Non Aktif</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn-soft light" data-bs-dismiss="modal">Batal</button>
                <button class="btn-soft" id="btnSaveCategory">Simpan</button>
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

    let categoryModal;

    /* =====================================================
     * LOAD DATA (AJAX)
     * ===================================================== */
    function loadCategories() {
        const search = $('#searchCategory').val();
        const status = $('#filterStatus').val();

        $.ajax({
            url: "{{ route('categories.data') }}",
            method: "GET",
            data: {
                search,
                status,
                per_page: pagination.perPage,
                page: pagination.currentPage
            },
            success: function(res) {
                console.log("Category Data:", res);
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
        const tbody = $("#categoryTable tbody");
        tbody.empty();

        if (data.length === 0) {
            $("#emptyState").removeClass("d-none");
            return;
        } else {
            $("#emptyState").addClass("d-none");
        }

        data.forEach((c, i) => {
            const badge = c.status === "active"
                ? '<span class="badge-status badge-active">Aktif</span>'
                : '<span class="badge-status badge-nonactive">Non Aktif</span>';

            tbody.append(`
                <tr>
                    <td>${i + 1}</td>
                    <td><strong>${c.kode}</strong></td>
                    <td>${c.nama}</td>
                    <td>${badge}</td>
                    <td class="text-end">
                        <button class="action-btn" data-action="edit" data-id="${c.id}">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        <button class="action-btn" data-action="delete" data-id="${c.id}">
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
            `Menampilkan ${res.from ?? 0} - ${res.to ?? 0} dari ${res.total} kategori (Hal ${res.current_page} / ${res.last_page})`
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
    // function openAddModal() {
    //     $.get("{{ route('categories.generate.code') }}", function(res) {
    //         console.log(res);
    //         $("#kodeKategori").val(res.code);
    //         $("#categoryModalTitle").text("Tambah Kategori");
    //         $("#categoryForm")[0].reset();
    //         $("#categoryId").val("");
    //         $("#statusKategori").val("active");
    //         categoryModal.show();
    //     });
    // }
    function openAddModal() {
    $.ajax({
        url: "{{ route('categories.generate.code') }}",
        type: "GET",
        success: function(res) {
            console.log("Generate Code:", res);

            $("#categoryForm")[0].reset();
            $("#categoryId").val("");
            $("#kodeKategori").val(res.code);
            $("#statusKategori").val("active");

            $("#categoryModalTitle").text("Tambah Kategori");
            categoryModal.show();
        },
        error: function(xhr) {
            console.error(xhr.responseText);
            alert("Gagal generate kode kategori!");
        }
    });
}



    /* =====================================================
     * MODAL EDIT
     * ===================================================== */
    function openEditModal(id) {
        $.get(`/categories/${id}`, function(c) {
            $("#categoryModalTitle").text("Edit Kategori");
            $("#categoryId").val(c.id);
            $("#kodeKategori").val(c.kode);
            $("#namaKategori").val(c.nama);
            $("#statusKategori").val(c.status);

            categoryModal.show();
        });
    }


    /* =====================================================
     * SAVE (CREATE / UPDATE)
     * ===================================================== */
    function saveCategory() {
        const id = $("#categoryId").val();

        const payload = {
            kode: $("#kodeKategori").val(),
            nama: $("#namaKategori").val(),
            status: $("#statusKategori").val(),
        };

        let url = "/categories";
        let method = "POST";

        if (id) {
            url = `/categories/${id}`;
            method = "PUT";
        }

        $.ajax({
            url,
            type: method,
            data: payload,
            success: function(res) {
                Swal.fire("Berhasil", res.message, "success");
                categoryModal.hide();
                loadCategories();
            },
            error: function(xhr) {
                Swal.fire("Error", xhr.responseJSON.message, "error");
            }
        });
    }


    /* =====================================================
     * DELETE CATEGORY
     * ===================================================== */
    function deleteCategory(id) {
        Swal.fire({
            icon: "warning",
            title: "Hapus Kategori",
            text: "Yakin ingin menghapus kategori ini?",
            showCancelButton: true,
            confirmButtonText: "Ya, hapus"
        }).then(res => {
            if (!res.isConfirmed) return;

            $.ajax({
                url: `/categories/${id}`,
                type: "DELETE",
                success: function(result) {
                    Swal.fire("Dihapus", result.message, "success");
                    loadCategories();
                }
            });
        });
    }


    /* =====================================================
     * INIT
     * ===================================================== */
    $(function() {
        categoryModal = new bootstrap.Modal(document.getElementById("categoryModal"));

        // Load awal
        loadCategories();

        // Filter
        $("#searchCategory, #filterStatus").on("input change", function() {
            pagination.currentPage = 1;
            loadCategories();
        });

        // Pagination click
        $("#pagination").on("click", ".page-link", function(e) {
            e.preventDefault();
            const page = $(this).data("page");
            if (page) {
                pagination.currentPage = page;
                loadCategories();
            }
        });

        // Add
        $("#btnAddCategory").on("click", openAddModal);

        // Save
        $("#btnSaveCategory").on("click", saveCategory);

        // Action buttons
        $("#categoryTable").on("click", ".action-btn", function() {
            const id = $(this).data("id");
            const action = $(this).data("action");

            if (action === "edit") openEditModal(id);
            if (action === "delete") deleteCategory(id);
        });

        // Refresh
        $("#btnRefresh").on("click", function() {
            $("#searchCategory").val('');
            $("#filterStatus").val('');
            pagination.currentPage = 1;
            loadCategories();
        });
    });
</script>


@endpush
@endsection
