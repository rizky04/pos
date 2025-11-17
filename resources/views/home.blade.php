@extends('layouts.apps')

@section('content')
    <section>
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <div class="page-header-title">Dashboard</div>
                <div class="page-header-sub">
                    Selamat datang kembali! Pantau performa bisnis Anda hari ini.
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <button class="btn-soft light" id="btnRefresh">
                    <i class="bi bi-arrow-clockwise"></i> Refresh
                </button>
                <button class="btn-soft" id="btnNewTransaction">
                    <i class="bi bi-plus-circle"></i> Transaksi Baru
                </button>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card primary">
                <div class="stat-icon">
                    <i class="bi bi-cash-stack"></i>
                </div>
                <div class="stat-label">Penjualan Hari Ini</div>
                <div class="stat-value" id="todaySales">Rp 8.450.000</div>
                <div class="stat-change">
                    <i class="bi bi-arrow-up"></i> +12.5% dari kemarin
                </div>
            </div>

            <div class="stat-card success">
                <div class="stat-icon">
                    <i class="bi bi-receipt"></i>
                </div>
                <div class="stat-label">Total Transaksi</div>
                <div class="stat-value" id="totalTransactions">127</div>
                <div class="stat-change">
                    <i class="bi bi-arrow-up"></i> +8.3% dari kemarin
                </div>
            </div>

            <div class="stat-card warning">
                <div class="stat-icon">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>
                <div class="stat-label">Rata-rata Transaksi</div>
                <div class="stat-value" id="avgTransaction">Rp 66.535</div>
                <div class="stat-change">
                    <i class="bi bi-arrow-up"></i> +3.2% dari kemarin
                </div>
            </div>

            <div class="stat-card info">
                <div class="stat-icon">
                    <i class="bi bi-people"></i>
                </div>
                <div class="stat-label">Customer Hari Ini</div>
                <div class="stat-value" id="todayCustomers">98</div>
                <div class="stat-change">
                    <i class="bi bi-arrow-up"></i> +15.7% dari kemarin
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="content-section">
            <div class="section-title">Quick Actions</div>
            <div class="quick-actions">
                <a href="#" class="quick-btn" id="btnQuickPOS">
                    <div><i class="bi bi-cart-plus"></i></div>
                    <div class="quick-btn-label">Buka POS</div>
                </a>
                <a href="#" class="quick-btn" id="btnQuickProduct">
                    <div><i class="bi bi-box-seam"></i></div>
                    <div class="quick-btn-label">Master Barang</div>
                </a>
                <a href="#" class="quick-btn" id="btnQuickReport">
                    <div><i class="bi bi-file-text"></i></div>
                    <div class="quick-btn-label">Laporan Harian</div>
                </a>
                <a href="#" class="quick-btn" id="btnQuickStock">
                    <div><i class="bi bi-boxes"></i></div>
                    <div class="quick-btn-label">Stok Opname</div>
                </a>
                <a href="#" class="quick-btn" id="btnQuickCustomer">
                    <div><i class="bi bi-person-badge"></i></div>
                    <div class="quick-btn-label">Data Customer</div>
                </a>
                <a href="#" class="quick-btn" id="btnQuickSettings">
                    <div><i class="bi bi-gear"></i></div>
                    <div class="quick-btn-label">Pengaturan</div>
                </a>
            </div>
        </div>

        <!-- Charts & Activity -->
        <div class="content-section">
            <div class="section-title">Ringkasan & Aktivitas</div>
            <div class="chart-grid">
                <!-- Chart Area -->
                <div class="chart-container">
                    <div style="text-align: center; padding: 60px 20px; color: #9ca3af;">
                        <i class="bi bi-bar-chart" style="font-size: 48px; margin-bottom: 12px; display: block;"></i>
                        <div style="font-size: 12px; font-weight: 500;">Grafik Penjualan 7 Hari Terakhir</div>
                        <div style="font-size: 10px; margin-top: 4px;">Integrasi Chart.js atau Recharts</div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="chart-container">
                    <div style="font-size: 11px; font-weight: 600; color: #111827; margin-bottom: 10px;">
                        Aktivitas Terbaru
                    </div>
                    <div class="activity-list">
                        <div class="activity-item">
                            <div class="activity-icon">
                                <i class="bi bi-cart-check" style="color: #22c55e;"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">Transaksi #TRX-2025-001234</div>
                                <div class="activity-time">5 menit yang lalu • Rp 245.000</div>
                            </div>
                        </div>

                        <div class="activity-item">
                            <div class="activity-icon">
                                <i class="bi bi-box-arrow-in-down" style="color: #3b82f6;"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">Stok Masuk: Nasi Goreng Spesial</div>
                                <div class="activity-time">12 menit yang lalu • +50 pcs</div>
                            </div>
                        </div>

                        <div class="activity-item">
                            <div class="activity-icon">
                                <i class="bi bi-person-plus" style="color: #f59e0b;"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">Customer Baru: Ahmad Rizki</div>
                                <div class="activity-time">25 menit yang lalu</div>
                            </div>
                        </div>

                        <div class="activity-item">
                            <div class="activity-icon">
                                <i class="bi bi-cart-check" style="color: #22c55e;"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">Transaksi #TRX-2025-001233</div>
                                <div class="activity-time">32 menit yang lalu • Rp 180.500</div>
                            </div>
                        </div>

                        <div class="activity-item">
                            <div class="activity-icon">
                                <i class="bi bi-exclamation-triangle" style="color: #ef4444;"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">Stok Rendah: Es Teh Manis</div>
                                <div class="activity-time">45 menit yang lalu • Sisa 5 pcs</div>
                            </div>
                        </div>

                        <div class="activity-item">
                            <div class="activity-icon">
                                <i class="bi bi-cart-check" style="color: #22c55e;"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">Transaksi #TRX-2025-001232</div>
                                <div class="activity-time">1 jam yang lalu • Rp 420.000</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Info Cards -->
        <div class="content-section">
            <div class="section-title">Informasi Tambahan</div>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="bi bi-box"></i>
                    </div>
                    <div class="stat-label">Total Produk</div>
                    <div class="stat-value" style="color: #111827;">342</div>
                    <div class="stat-change" style="color: #9ca3af;">18 kategori</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                    <div class="stat-label">Stok Menipis</div>
                    <div class="stat-value" style="color: #ef4444;">12</div>
                    <div class="stat-change" style="color: #9ca3af;">Perlu restock</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="bi bi-people"></i>
                    </div>
                    <div class="stat-label">Total Customer</div>
                    <div class="stat-value" style="color: #111827;">1.247</div>
                    <div class="stat-change" style="color: #9ca3af;">+23 bulan ini</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="bi bi-star"></i>
                    </div>
                    <div class="stat-label">Produk Terlaris</div>
                    <div class="stat-value" style="color: #111827; font-size: 12px;">Nasi Goreng</div>
                    <div class="stat-change" style="color: #9ca3af;">89 terjual hari ini</div>
                </div>
            </div>
        </div>
    </section>

@push('scripts')
<script>
    $(function () {

        // Refresh button
        $('#btnRefresh').on('click', function () {
            Swal.fire({
                icon: 'info',
                title: 'Memuat Ulang Data',
                text: 'Dashboard akan diperbarui dengan data terbaru.',
                timer: 1500,
                showConfirmButton: false
            }).then(() => {
                location.reload();
            });
        });

        // New Transaction button
        $('#btnNewTransaction').on('click', function () {
            Swal.fire({
                icon: 'success',
                title: 'Buka POS',
                text: 'Mengarahkan ke halaman POS...',
                timer: 1000,
                showConfirmButton: false
            }).then(() => {
                window.location.href = 'pos.html';
            });
        });

        // Quick action buttons
        $('#btnQuickPOS').on('click', function (e) {
            e.preventDefault();
            window.location.href = 'pos.html';
        });

        $('#btnQuickProduct').on('click', function (e) {
            e.preventDefault();
            window.location.href = 'master-barang.html';
        });

        $('#btnQuickReport').on('click', function (e) {
            e.preventDefault();
            Swal.fire('Laporan', 'Halaman laporan akan segera tersedia.', 'info');
        });

        $('#btnQuickStock').on('click', function (e) {
            e.preventDefault();
            Swal.fire('Stok Opname', 'Halaman stok opname akan segera tersedia.', 'info');
        });

        $('#btnQuickCustomer').on('click', function (e) {
            e.preventDefault();
            Swal.fire('Customer', 'Halaman data customer akan segera tersedia.', 'info');
        });

        $('#btnQuickSettings').on('click', function (e) {
            e.preventDefault();
            Swal.fire('Pengaturan', 'Halaman pengaturan akan segera tersedia.', 'info');
        });

        // Simulate real-time updates (dummy)
        function updateStats() {
            const randomIncrease = (base, variance) => {
                return base + Math.floor(Math.random() * variance);
            };

            // Just for demo - in production, fetch from API
            setInterval(function () {
                const currentSales = parseInt($('#todaySales').text().replace(/[^0-9]/g, ''));
                const newSales = randomIncrease(currentSales, 100000);
                $('#todaySales').text('Rp ' + newSales.toLocaleString('id-ID'));
            }, 30000); // Update every 30 seconds
        }

        // Start stats animation
        updateStats();
    });
</script>
@endpush
@endsection
