<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>POS - Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        /* ============================================================
   BASE STYLE (DEKSTOP)
============================================================ */
body {
    background: #eef2f7;
    font-family: system-ui, -apple-system, BlinkMacSystemFont, "SF Pro Display", sans-serif;
}

.pos-wrapper {
    width: 100%;
    max-width: 1300px;
    margin: 30px auto;
    background: #ffffff;
    border-radius: 28px;
    box-shadow: 0 20px 45px rgba(15, 23, 42, 0.08);
    padding: 24px;
    display: grid;
    grid-template-columns: 78px 1fr;
    gap: 24px;
}

/* SIDEBAR */
.sidebar {
    background: #f8fafc;
    border-radius: 24px;
    padding: 14px 10px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 16px;
}

.sidebar-logo {
    width: 32px;
    height: 32px;
    border-radius: 12px;
    background: #ff3b5c;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-weight: 700;
    margin-bottom: 10px;
    cursor: pointer;
}

.side-btn {
    width: 40px;
    height: 40px;
    border-radius: 14px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #9ca3af;
    cursor: pointer;
    transition: all .2s ease;
}

.side-btn.active,
.side-btn:hover {
    background: #ff3b5c10;
    color: #ff3b5c;
}

.nav-label {
    font-size: 10px;
    margin-top: 4px;
    color: #9ca3af;
}

.side-btn.active .nav-label,
.side-btn:hover .nav-label {
    color: #ff3b5c;
}

/* PRODUCT GRID */
.product-grid {
    margin-top: 14px;
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
}

.product-card {
    background: #f9fafb;
    border-radius: 18px;
    padding: 10px;
    text-align: center;
    border: 1px solid transparent;
    transition: all .16s ease;
}

.product-card:hover {
    transform: translateY(-3px);
    border-color: #ff3b5c40;
    box-shadow: 0 8px 20px rgba(15, 23, 42, 0.09);
}

.product-thumb {
    width: 60px;
    height: 60px;
    border-radius: 999px;
    margin: 0 auto 8px;
    background: radial-gradient(circle at 25% 0, #fff, #fde68a);
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 26px;
}

.product-name {
    font-size: 12px;
    font-weight: 500;
}

.product-price {
    font-size: 11px;
    color: #6b7280;
}

.product-stock {
    font-size: 10px;
    color: #9ca3af;
}

/* ORDER PANEL */
.order-panel {
    background: #f9fafb;
    border-radius: 24px;
    padding: 18px 16px;
    display: flex;
    flex-direction: column;
    height: 100%;
    overflow: hidden;
}

/* ======================
   CHARGE BUTTON
====================== */
.btn-charge {
    margin-top: 16px;
    width: 100%;
    border: none;
    padding: 14px 0;
    font-size: 16px;
    font-weight: 600;
    background: #ff3b5c;
    color: #fff;
    border-radius: 18px;
    box-shadow: 0 12px 28px rgba(255, 59, 92, .45);
}

.btn-charge:hover {
    transform: translateY(-1px);
}

/* ============================================================
   RESPONSIVE — TABLET 992px–1199px (BOOTSTRAP LG)
============================================================ */
@media (min-width: 992px) and (max-width: 1199px) {

    .pos-wrapper {
        grid-template-columns: 70px 1fr !important;
        max-width: 100% !important;
        padding: 20px !important;
        gap: 20px !important;
    }

    .product-grid {
        grid-template-columns: repeat(3, 1fr) !important;
        gap: 16px !important;
    }

    .order-panel {
        min-width: 300px !important;
        max-height: calc(100vh - 200px);
    }

    .sidebar {
        padding: 10px !important;
        gap: 10px;
    }
}

/* ============================================================
   RESPONSIVE — IPAD LANDSCAPE 768px–991px (BOOTSTRAP MD)
============================================================ */
@media (min-width: 768px) and (max-width: 991px) {

    .pos-wrapper {
        display: flex !important;
        flex-direction: column !important;
        padding: 18px !important;
        gap: 20px !important;
    }

    .product-grid {
        grid-template-columns: repeat(2, 1fr) !important;
        gap: 16px;
    }

    .order-panel {
        width: 100% !important;
        order: 999;
    }

    /* Sidebar jadi horizontal scroll */
    .sidebar {
        width: 100%;
        flex-direction: row;
        justify-content: flex-start;
        overflow-x: auto;
        gap: 16px;
        padding: 10px 12px;
        -webkit-overflow-scrolling: touch;
    }
}

/* ============================================================
   RESPONSIVE — TABLET PORTRAIT (820–900px)
============================================================ */
@media (min-width: 820px) and (max-width: 900px) {

    .product-grid {
        grid-template-columns: repeat(2, 1fr) !important;
        gap: 18px !important;
    }

    .pos-wrapper {
        padding: 16px !important;
    }
}

/* ============================================================
   RESPONSIVE — TABLET KECIL 576px–767px (BOOTSTRAP SM-MD)
============================================================ */
@media (min-width: 576px) and (max-width: 767px) {

    .product-grid {
        grid-template-columns: repeat(2, 1fr) !important;
    }

    .order-panel {
        width: 100% !important;
    }

    /* Bottom navbar mode */
    .sidebar {
        position: fixed !important;
        bottom: 0;
        left: 0;
        right: 0;
        height: 72px;
        padding: 8px 14px !important;
        overflow-x: auto;
        display: flex;
        justify-content: space-between;
        z-index: 999;
    }

    .pos-wrapper {
        padding-bottom: 90px !important;
    }
}

/* ============================================================
   RESPONSIVE — MOBILE (<576px)
============================================================ */
@media (max-width: 575px) {

    .product-grid {
        grid-template-columns: repeat(2, 1fr) !important;
    }

    .pos-wrapper {
        padding-bottom: 110px !important;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .sidebar {
        position: fixed;
        bottom: 0; left: 0; right: 0;
        background: #fff;
        height: 72px;
        padding: 8px 16px;
        display: flex !important;
        justify-content: space-around;
        z-index: 9999;
    }
}

/* ============================================================
   FINISHING & BUGFIX
============================================================ */
.product-card { min-height: 130px; }
.order-list::-webkit-scrollbar { width: 6px; }
.order-list::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 999px; }

.tooltip.sidebar-tooltip { margin-top: -6px !important; }

@media (hover: none) {
    .product-card:hover { transform: none; box-shadow: none; }
    .btn-charge:hover { transform: none; }
}


/* ============================================================
   FORCE SIDEBAR BOTTOM NAV UNTUK TABLET & MOBILE (≤1200px)
============================================================ */
@media (max-width: 1200px) {

.pos-wrapper {
    display: flex !important;
    flex-direction: column !important;
    gap: 20px !important;
    padding-bottom: 100px !important; /* supaya tidak ketutup nav */
}

/* Sidebar jadi bottom nav */
.sidebar {
    position: fixed !important;
    bottom: 0;
    left: 0;
    right: 0;
    z-index: 9999;

    height: 72px !important;
    padding: 8px 16px !important;

    background: #ffffff;
    border-radius: 18px 18px 0 0;

    display: flex !important;
    flex-direction: row !important;
    justify-content: space-around !important;
    align-items: center !important;

    overflow-x: auto !important;
    overflow-y: hidden !important;
    white-space: nowrap !important;
    -webkit-overflow-scrolling: touch;
}

.sidebar-logo {
    display: none !important;
}

.side-btn {
    width: 64px !important;
    height: 54px !important;
    border-radius: 16px !important;
    font-size: 20px !important;
}

.nav-label {
    font-size: 9px !important;
}

/* grid produk di tablet */
.product-grid {
    grid-template-columns: repeat(2, 1fr) !important;
    gap: 16px !important;
}

/* order panel ke bawah */
.order-panel {
    width: 100% !important;
    order: 999 !important;
}
}


    </style>
    <link rel="stylesheet" href="{{ asset('assets/assets/css/main.style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/assets/css/pos.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>

    <div class="pos-wrapper">

        <!-- SIDEBAR -->
        @include('layouts.sidebar')

        <!-- MAIN CONTENT -->

        @yield('content')
    </div>

    <!-- JS -->

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(function() {
            // Initialize tooltips
            $('[data-bs-toggle="tooltip"]').each(function() {
                new bootstrap.Tooltip(this, {
                    boundary: document.body,
                    customClass: 'sidebar-tooltip',
                    trigger: 'hover'
                });
            });
        });
    </script>
    @stack('scripts')
</body>

</html>
