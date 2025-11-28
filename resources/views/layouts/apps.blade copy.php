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
            flex: 0 0 auto;
        }

        .side-btn {
            width: 40px;
            height: 40px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #9ca3af;
            cursor: pointer;
            transition: all .2s ease;
            flex: 0 0 auto;
        }

        .side-btn.active,
        .side-btn:hover {
            background: #ff3b5c10;
            color: #ff3b5c;
        }

        .side-spacer {
            flex: 1;
        }

        .tooltip.sidebar-tooltip .tooltip-inner {
            background-color: #111827;
            color: #f9fafb;
            padding: 4px 10px;
            font-size: 10px;
            border-radius: 10px;
        }

        .tooltip.sidebar-tooltip.bs-tooltip-end .tooltip-arrow::before {
            border-right-color: #111827 !important;
        }

        /* HEADER */
        .page-header-title {
            font-size: 20px;
            font-weight: 600;
            color: #111827;
        }

        .page-header-sub {
            font-size: 11px;
            color: #9ca3af;
        }

        .btn-soft {
            border-radius: 999px;
            padding: 6px 14px;
            font-size: 10px;
            border: none;
            background: #111827;
            color: #f9fafb;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all .18s ease;
        }

        .btn-soft.light {
            background: #ffffff;
            color: #111827;
            border: 1px solid #e5e7eb;
        }

        .btn-soft:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 14px rgba(15, 23, 42, 0.18);
        }

        /* STATS CARDS */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
            margin-top: 16px;
        }

        .stat-card {
            background: linear-gradient(135deg, #f9fafb 0%, #ffffff 100%);
            border-radius: 18px;
            padding: 14px 16px;
            box-shadow: 0 4px 12px rgba(148, 163, 253, 0.12);
            transition: all .2s ease;
            border: 1px solid #f3f4f6;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(148, 163, 253, 0.18);
        }

        .stat-card.primary {
            background: linear-gradient(135deg, #ff3b5c 0%, #e03450 100%);
            color: #fff;
            border: none;
        }

        .stat-card.success {
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
            color: #fff;
            border: none;
        }

        .stat-card.warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: #fff;
            border: none;
        }

        .stat-card.info {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: #fff;
            border: none;
        }

        .stat-icon {
            width: 36px;
            height: 36px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            margin-bottom: 8px;
        }

        .stat-card:not(.primary):not(.success):not(.warning):not(.info) .stat-icon {
            background: #f9fafb;
            color: #6b7280;
        }

        .stat-label {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            opacity: 0.85;
            margin-bottom: 2px;
        }

        .stat-value {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .stat-change {
            font-size: 9px;
            opacity: 0.9;
        }

        /* CHARTS & CONTENT AREA */
        .content-section {
            margin-top: 20px;
        }

        .section-title {
            font-size: 14px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 10px;
        }

        .chart-container {
            background: #f9fafb;
            border-radius: 20px;
            padding: 16px;
            box-shadow: 0 4px 12px rgba(148, 163, 253, 0.12);
        }

        .chart-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 14px;
        }

        .activity-list {
            background: #ffffff;
            border-radius: 14px;
            padding: 12px;
            max-height: 280px;
            overflow-y: auto;
        }

        .activity-item {
            display: flex;
            gap: 10px;
            padding: 8px;
            border-bottom: 1px solid #f3f4f6;
            font-size: 10px;
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-icon {
            width: 28px;
            height: 28px;
            border-radius: 8px;
            background: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .activity-content {
            flex: 1;
        }

        .activity-title {
            font-weight: 500;
            color: #111827;
            margin-bottom: 2px;
        }

        .activity-time {
            color: #9ca3af;
            font-size: 9px;
        }

        /* QUICK ACTIONS */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-top: 14px;
        }

        .quick-btn {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            padding: 12px;
            text-align: center;
            cursor: pointer;
            transition: all .2s ease;
            text-decoration: none;
            color: #111827;
        }

        .quick-btn:hover {
            background: #ff3b5c10;
            border-color: #ff3b5c;
            transform: translateY(-2px);
        }

        .quick-btn i {
            font-size: 20px;
            color: #ff3b5c;
            margin-bottom: 6px;
        }

        .quick-btn-label {
            font-size: 10px;
            font-weight: 500;
        }

        /* RESPONSIVE */
        @media (max-width: 992px) {
            .pos-wrapper {
                grid-template-columns: 64px 1fr;
                max-width: 100%;
                border-radius: 0;
                margin: 0;
                box-shadow: none;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .chart-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .pos-wrapper {
                display: flex;
                flex-direction: column;
                gap: 16px;
                max-width: 100%;
                margin: 0;
                padding: 12px 8px 16px;
                border-radius: 0;
                box-shadow: none;
            }

            .sidebar {
                flex-direction: row;
                align-items: center;
                justify-content: flex-start;
                gap: 14px;
                padding: 10px 12px;
                border-radius: 18px;
                overflow-x: auto;
                overflow-y: hidden;
                white-space: nowrap;
                -webkit-overflow-scrolling: touch;
            }

            .side-spacer {
                display: none;
            }

            .sidebar::-webkit-scrollbar {
                height: 4px;
            }

            .sidebar::-webkit-scrollbar-track {
                background: transparent;
            }

            .sidebar::-webkit-scrollbar-thumb {
                background: rgba(156, 163, 175, .4);
                border-radius: 999px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .quick-actions {
                grid-template-columns: repeat(2, 1fr);
            }
        }


        .category-pill {
            min-width: 68px;
            padding: 8px 14px;
            border-radius: 18px;
            background: #f9fafb;
            color: #6b7280;
            font-size: 12px;
            display: flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            border: 1px solid transparent;
            transition: all .2s ease;
            white-space: nowrap;
        }

        .category-pill i {
            font-size: 14px;
        }

        .category-pill.active,
        .category-pill:hover {
            background: #ff3b5c;
            color: #fff;
            box-shadow: 0 6px 16px rgba(255, 59, 92, 0.35);
        }

        .search-box {
            background: #f9fafb;
            border-radius: 16px;
            padding: 8px 12px;
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: #9ca3af;
        }

        .search-box input {
            border: none;
            background: transparent;
            font-size: 12px;
            width: 100%;
            outline: none;
        }

        .product-grid {
            margin-top: 14px;
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
        }

        .product-card {
            background: #f9fafb;
            border-radius: 18px;
            padding: 10px;
            text-align: center;
            cursor: pointer;
            transition: all .16s ease;
            border: 1px solid transparent;
            position: relative;
        }

        .product-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.08);
            border-color: #ff3b5c30;
        }

        .product-thumb {
            width: 60px;
            height: 60px;
            border-radius: 999px;
            margin: 0 auto 8px;
            background: radial-gradient(circle at 25% 0, #fff, #fde68a);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
        }

        .product-name {
            font-size: 12px;
            font-weight: 500;
            color: #111827;
        }

        .product-price {
            font-size: 11px;
            color: #6b7280;
        }

        .product-stock {
            font-size: 10px;
            color: #9ca3af;
            margin-top: 4px;
        }

        .badge-new {
            position: absolute;
            top: 6px;
            right: 8px;
            background: #ff3b5c;
            color: #fff;
            font-size: 9px;
            padding: 2px 7px;
            border-radius: 999px;
        }

        .product-card.out-stock {
            opacity: 0.4;
            cursor: not-allowed;
        }

        .product-card.out-stock::after {
            content: "Stok Habis";
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            font-weight: 600;
            color: #ff3b5c;
            background: rgba(255, 255, 255, 0.85);
            border-radius: 18px;
        }

        /* ORDER PANEL */
        .order-panel {
            background: #f9fafb;
            border-radius: 24px;
            padding: 18px 16px 16px;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .order-title {
            font-size: 16px;
            font-weight: 600;
            color: #111827;
        }

        .order-list {
            list-style: none;
            padding: 0;
            margin: 12px 0 0 0;
            flex: 1;
            overflow-y: auto;
        }

        .order-item {
            background: #fff;
            border-radius: 14px;
            padding: 8px 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            margin-bottom: 8px;
            box-shadow: 0 4px 12px rgba(148, 163, 253, 0.12);
            font-size: 11px;
        }

        .order-item-name {
            font-weight: 500;
            color: #111827;
        }

        .order-item-price {
            color: #6b7280;
        }

        .qty-box {
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            background: #f9fafb;
            overflow: hidden;
            border: 1px solid #e5e7eb;
        }

        .qty-btn {
            width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            cursor: pointer;
            user-select: none;
            color: #6b7280;
        }

        .qty-value {
            padding: 0 6px;
            font-size: 11px;
            color: #111827;
        }

        .remove-item {
            margin-left: 4px;
            cursor: pointer;
            color: #9ca3af;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            color: #6b7280;
            margin-top: 4px;
        }

        .summary-row strong {
            color: #111827;
        }

        .btn-charge {
            margin-top: 12px;
            border: none;
            width: 100%;
            padding: 12px 0;
            border-radius: 18px;
            font-size: 15px;
            font-weight: 600;
            background: #ff3b5c;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 12px 28px rgba(255, 59, 92, .5);
            transition: all .18s ease;
        }

        .btn-charge:hover {
            transform: translateY(-1px);
            box-shadow: 0 18px 40px rgba(255, 59, 92, .55);
        }

        .btn-charge span {
            font-size: 13px;
            font-weight: 400;
            opacity: .9;
        }

        .order-list::-webkit-scrollbar {
            width: 4px;
        }

        .order-list::-webkit-scrollbar-thumb {
            background: #e5e7eb;
            border-radius: 999px;
        }

        /* PAGINATION THEME MATCH */
        #pagination .page-item {
            margin: 0 2px;
        }

        #pagination .page-link {
            border-radius: 999px;
            border: none;
            padding: 4px 10px;
            font-size: 10px;
            background-color: #f9fafb;
            color: #ff3b5c;
            box-shadow: 0 2px 6px rgba(148, 163, 253, 0.18);
            transition: all .18s ease;
        }

        #pagination .page-link:hover {
            background-color: #ff3b5c10;
            color: #ff3b5c;
            box-shadow: 0 3px 10px rgba(255, 59, 92, 0.25);
        }

        #pagination .page-item.active .page-link {
            background-color: #ff3b5c;
            color: #ffffff;
            box-shadow: 0 4px 14px rgba(255, 59, 92, 0.45);
        }

        #pagination .page-item.disabled .page-link {
            background-color: #f3f4f6;
            color: #d1d5db;
            box-shadow: none;
        }
        /* ====== SELECT2 FIX AGAR SAMA DENGAN INPUT BOOTSTRAP ====== */

.select2-container .select2-selection--single {
    height: calc(1.8125rem + 2px) !important; /* tinggi form-control-sm */
    padding: 4px 8px !important;
    border: 1px solid #ced4da !important;
    border-radius: 0.25rem !important;
    display: flex !important;
    align-items: center !important;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    font-size: 0.875rem; /* sama seperti input-sm */
    line-height: 24px !important;
}

.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 100% !important;
    top: 6px !important;
    right: 6px !important;
}

.select2-container--default.select2-container--focus .select2-selection--single {
    border-color: #86b7fe !important;
    box-shadow: 0 0 0 0.15rem rgba(13, 110, 253, 0.25) !important;
}

/* Placeholder */
.select2-selection__placeholder {
    color: #6c757d !important;
    font-size: 0.875rem !important;
}
/* =============== LABEL UNTUK SIDEBAR (DESKTOP) =============== */
.nav-label {
    display: block;
    font-size: 10px;
    margin-top: 4px;
    text-align: center;
    color: #9ca3af;
    font-weight: 500;
}

/* Active */
.side-btn.active .nav-label {
    color: #ff3b5c;
}

/* Hover */
.side-btn:hover .nav-label {
    color: #ff3b5c;
}

/* Center icon + text */
.side-btn {
    flex-direction: column;
    gap: 2px;
}

/* ==================================
   MOBILE MODE (BOTTOM NAV)
   ================================== */
@media (max-width: 768px) {

    .sidebar {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        z-index: 9999;
        background: #ffffff;
        border-radius: 18px 18px 0 0;
        padding: 8px 16px;
        height: 70px;
        box-shadow: 0 -6px 20px rgba(0,0,0,0.12);
        display: flex !important;
        justify-content: space-around;
        align-items: center;
    }

    /* Icon + Label */
    .side-btn {
        width: 64px !important;
        height: 54px !important;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        gap: 2px;
        color: #9ca3af;
        font-size: 20px;
    }

    .side-btn.active {
        background: #ff3b5c15;
        color: #ff3b5c;
    }

    /* Label di mobile */
    .nav-label {
        font-size: 9px;
        margin-top: 1px;
        color: #9ca3af;
    }

    .side-btn.active .nav-label {
        color: #ff3b5c;
        font-weight: 600;
    }

    /* Hilangkan logo */
    .sidebar-logo {
        display: none !important;
    }

    /* Content kasih jarak biar tidak ketutup */
    .pos-wrapper {
        padding-bottom: 100px !important;
    }


}
  /* ===== FIX POSISI TOOLTIP SIDEBAR ===== */
.tooltip.sidebar-tooltip {
    margin-top: -8px !important; /* naikkan tooltip */
}

.tooltip.sidebar-tooltip .tooltip-inner {
    background-color: #111827;
    color: #f9fafb;
    padding: 4px 10px;
    font-size: 10px;
    border-radius: 10px;
}

.tooltip.sidebar-tooltip.bs-tooltip-end .tooltip-arrow::before {
    border-right-color: #111827 !important;
}

.sidebar a .nav-label {
    text-decoration: none !important;
}
.tooltip-inner {
    text-decoration: none !important;
}

.tooltip.sidebar-tooltip .tooltip-inner {
    text-decoration: none !important;
}
.sidebar a {
    text-decoration: none !important;
}

.side-btn {
    text-decoration: none !important;
}
.tooltip-inner:hover {
    text-decoration: none !important;
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
      $(function () {
        // Initialize tooltips
        $('[data-bs-toggle="tooltip"]').each(function () {
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
