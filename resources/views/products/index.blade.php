@extends('layouts.apps')

@section('content')
<section>
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-start">
        <div>
            <div class="page-header-title">Master Product</div>
            <div class="page-header-sub">Kelola daftar barang, kategori, unit, harga, stok dan status.</div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <button class="btn-soft light" id="btnRefresh">
                <i class="bi bi-arrow-clockwise"></i> Reload
            </button>
            <button class="btn-soft" id="btnAddProduct">
                <i class="bi bi-plus-circle"></i> Tambah Product
            </button>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="filter-bar mt-2">
        <div class="filter-label">Filter</div>

        <div class="filter-search-wrap">
            <i class="bi bi-search"></i>
            <input type="text" id="searchProduct" placeholder="Cari kode / nama">
        </div>

        <div>
            <select id="filterCategory">
                <option value="">Semua Kategori</option>
                @foreach($categories as $c)
                    <option value="{{ $c->id }}">{{ $c->nama }}</option>
                @endforeach
            </select>
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
            <table class="table align-middle" id="productTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Unit</th>
                        <th>Harga Jual</th>
                        <th>Harga Modal</th>
                        <th>Stok</th>
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
            Belum ada product yang sesuai filter.
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

<!-- MODAL ADD/EDIT -->
<div class="modal fade" id="productModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalTitle">Tambah Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" style="font-size:8px;"></button>
            </div>
            <div class="modal-body">
                <form id="productForm">
                    <input type="hidden" id="productId">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <label class="form-label">Kode</label>
                            <input type="text" id="kode" class="form-control" required>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Nama Product</label>
                            <input type="text" id="nama" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Kategori</label>
                            <select id="category_id" class="form-select" required>
                                <option value="">Pilih...</option>
                                @foreach($categories as $c)
                                    <option value="{{ $c->id }}">{{ $c->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Unit</label>
                            <select id="unit_id" class="form-select" required>
                                <option value="">Pilih...</option>
                                @foreach($units as $u)
                                    <option value="{{ $u->id }}">{{ $u->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Harga Modal</label>
                            <input type="number" id="harga_modal" class="form-control" min="0">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Harga Jual</label>
                            <input type="number" id="harga_jual" class="form-control" min="0">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Stok</label>
                            <input type="number" id="stok" class="form-control" min="0" value="0">
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
                <button class="btn-soft" id="btnSaveProduct">Simpan</button>
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

let productModal;

/* =====================================================
 * LOAD DATA
 * ===================================================== */
function loadProducts() {
    $.ajax({
        url: "{{ route('products.data') }}",
        method: "GET",
        data: {
            search: $("#searchProduct").val(),
            category_id: $("#filterCategory").val(),
            status: $("#filterStatus").val(),
            per_page: pagination.perPage,
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
    const tbody = $("#productTable tbody");
    tbody.empty();

    if (data.length === 0) {
        $("#emptyState").removeClass("d-none");
        return;
    }
    $("#emptyState").addClass("d-none");

    data.forEach((p, i) => {
        const badge = p.status === "active"
            ? '<span class="badge-status badge-active">Aktif</span>'
            : '<span class="badge-status badge-nonactive">Non Aktif</span>';

        tbody.append(`
            <tr>
                <td>${i + 1}</td>
                <td><strong>${p.kode}</strong></td>
                <td>${p.nama}</td>
                <td>${p.category.nama}</td>
                <td>${p.unit.nama}</td>
                <td>Rp ${Number(p.harga_jual).toLocaleString()}</td>
                <td>Rp ${Number(p.harga_modal).toLocaleString()}</td>
                <td>${p.stok}</td>
                <td>${badge}</td>
                <td class="text-end">
                    <button class="action-btn" data-action="edit" data-id="${p.id}">
                        <i class="bi bi-pencil-square"></i>
                    </button>
                    <button class="action-btn" data-action="delete" data-id="${p.id}">
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
    pag.empty();

    $("#paginationInfo").text(
        `Menampilkan ${res.from} - ${res.to} dari ${res.total} data (Hal ${res.current_page} / ${res.last_page})`
    );

    // Prev
    pag.append(`
        <li class="page-item ${res.current_page == 1 ? 'disabled' : ''}">
            <a class="page-link" href="#" data-page="${res.current_page - 1}">«</a>
        </li>`);

    // Numbering
    for (let p = 1; p <= res.last_page; p++) {
        pag.append(`
            <li class="page-item ${p == res.current_page ? 'active' : ''}">
                <a class="page-link" href="#" data-page="${p}">${p}</a>
            </li>`);
    }

    // Next
    pag.append(`
        <li class="page-item ${res.current_page == res.last_page ? 'disabled' : ''}">
            <a class="page-link" href="#" data-page="${res.current_page + 1}">»</a>
        </li>`);
}

/* =====================================================
 * ADD MODAL
 * ===================================================== */
function openAddModal() {
    $.get("{{ route('products.generate.code') }}", function(res) {
        $("#productModalTitle").text("Tambah Product");
        $("#productForm")[0].reset();
        $("#productId").val("");
        $("#kode").val(res.code);
        productModal.show();
    });
}

/* =====================================================
 * EDIT MODAL
 * ===================================================== */
function openEditModal(id) {
    $.get(`/products/${id}`, function(p) {
        $("#productModalTitle").text("Edit Product");
        $("#productId").val(p.id);
        $("#kode").val(p.kode);
        $("#nama").val(p.nama);
        $("#category_id").val(p.category_id);
        $("#unit_id").val(p.unit_id);
        $("#harga_modal").val(p.harga_modal);
        $("#harga_jual").val(p.harga_jual);
        $("#stok").val(p.stok);
        $("#status").val(p.status);
        productModal.show();
    });
}

/* =====================================================
 * SAVE DATA
 * ===================================================== */
function saveProduct() {
    const id = $("#productId").val();

    const payload = {
        kode: $("#kode").val(),
        nama: $("#nama").val(),
        category_id: $("#category_id").val(),
        unit_id: $("#unit_id").val(),
        harga_modal: $("#harga_modal").val(),
        harga_jual: $("#harga_jual").val(),
        stok: $("#stok").val(),
        status: $("#status").val(),
    };

    let url = "/products";
    let method = "POST";

    if (id) {
        url = `/products/${id}`;
        method = "PUT";
    }

    $.ajax({
        url, type: method, data: payload,
        success: function(res) {
            Swal.fire("Berhasil", res.message, "success");
            productModal.hide();
            loadProducts();
        },
        error: function(xhr) {
            Swal.fire("Error", xhr.responseJSON.message, "error");
        }
    });
}

/* =====================================================
 * DELETE DATA
 * ===================================================== */
function deleteProduct(id) {
    Swal.fire({
        icon: "warning",
        title: "Hapus Product",
        text: "Yakin ingin menghapus product ini?",
        showCancelButton: true,
        confirmButtonText: "Ya",
    }).then(res => {
        if (!res.isConfirmed) return;

        $.ajax({
            url: `/products/${id}`,
            type: "DELETE",
            success: function(res) {
                Swal.fire("Dihapus", res.message, "success");
                loadProducts();
            }
        });
    });
}

/* =====================================================
 * ON READY
 * ===================================================== */
$(function () {
    productModal = new bootstrap.Modal(document.getElementById("productModal"));

    loadProducts();

    $("#searchProduct, #filterStatus, #filterCategory").on("input change", function() {
        pagination.currentPage = 1;
        loadProducts();
    });

    $("#pagination").on("click", ".page-link", function(e) {
        e.preventDefault();
        const p = $(this).data("page");
        if (p) {
            pagination.currentPage = p;
            loadProducts();
        }
    });

    $("#btnAddProduct").on("click", openAddModal);
    $("#btnSaveProduct").on("click", saveProduct);

    $("#productTable").on("click", ".action-btn", function() {
        const id = $(this).data("id");
        const action = $(this).data("action");
        if (action === "edit") openEditModal(id);
        if (action === "delete") deleteProduct(id);
    });

    $("#btnRefresh").on("click", function() {
        $("#searchProduct").val('');
        $("#filterStatus").val('');
        $("#filterCategory").val('');
        pagination.currentPage = 1;
        loadProducts();
    });
});
</script>
@endpush

@endsection
