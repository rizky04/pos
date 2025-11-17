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
    </style>
    <link rel="stylesheet" href="{{ asset('assets/assets/css/main.style.css') }}">
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
