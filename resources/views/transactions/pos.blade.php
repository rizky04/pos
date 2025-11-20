@extends('layouts.apps')

@section('content')
<div class="row">
    <div class="col-12 col-md-8 mb-3">
     <section>
        <div class="d-flex justify-content-between align-items-start mb-2">
            <div>
                <div class="menu-header-title">Produk Category</div>
                <div class="text-muted" style="font-size:11px;">Choose order items</div>
            </div>
            <div class="search-box" style="width:260px;">
                <i class="bi bi-search"></i>
                <input type="text" id="searchItem" placeholder="Cari nama produk...">
            </div>
        </div>

        <!-- Category Pills -->
        {{-- <div class="d-flex align-items-center gap-2 flex-wrap mb-2">
            <div class="category-pill active" data-category="all">
                <i class="bi bi-fire"></i> All
            </div>
            <div class="category-pill" data-category="burger">
                <i class="bi bi-egg-fried"></i> Burger
            </div>
            <div class="category-pill" data-category="pizza">
                <i class="bi bi-pie-chart"></i> Pizza
            </div>
            <div class="category-pill" data-category="snack">
                <i class="bi bi-bag-check"></i> Snack
            </div>
            <div class="category-pill" data-category="drink">
                <i class="bi bi-cup-hot"></i> Drink
            </div>
            <div class="category-pill" data-category="icecream">
                <i class="bi bi-emoji-smile"></i> Ice Cream
            </div>

            <div class="category-pill" data-category="snack">
                <i class="bi bi-bag-check"></i> Snack
            </div>
            <div class="category-pill" data-category="drink">
                <i class="bi bi-cup-hot"></i> Drink
            </div>
            <div class="category-pill" data-category="icecream">
                <i class="bi bi-emoji-smile"></i> Ice Cream
            </div>
        </div> --}}
        <div class="d-flex align-items-center gap-2 flex-wrap mb-2">
    <div class="category-pill active" data-category="all">
        <i class="bi bi-fire"></i> Semua
    </div>

    @foreach ($categories as $cat)
        <div class="category-pill" data-category="{{ $cat->id }}">
            <i class="bi bi-tag"></i> {{ $cat->nama }}
        </div>
    @endforeach
</div>

        <!-- Choose Order header -->
        <div class="d-flex justify-content-between align-items-center mt-2">
            <div class="fw-semibold" style="font-size:14px;">Choose Order</div>
            <div class="dropdown">
                <button class="btn btn-sm btn-light border-0 dropdown-toggle" type="button"
                        data-bs-toggle="dropdown" style="font-size:11px;">
                    Sort by: Popular
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#">Popular</a></li>
                    <li><a class="dropdown-item" href="#">Harga Terendah</a></li>
                    <li><a class="dropdown-item" href="#">Harga Tertinggi</a></li>
                </ul>
            </div>
        </div>

        <!-- Product Grid (harga Rupiah + Stok) -->
        {{-- <div class="product-grid" id="productGrid">
            <!-- ITEM CONTOH (semua item awal, nanti dipaginate lewat JS) -->
            <div class="product-card" data-name="Triple Burger" data-price="54000" data-category="burger" data-stock="10">
                <div class="product-thumb">🍔</div>
                <div class="product-name">Triple Burger</div>
                <div class="product-price">Rp 54.000</div>
                <div class="product-stock">Stok: <span class="stock-val">10</span></div>
            </div>

            <div class="product-card" data-name="Double Cheese" data-price="49000" data-category="burger" data-stock="8">
                <div class="product-thumb">🧀</div>
                <div class="product-name">Double Cheese</div>
                <div class="product-price">Rp 49.000</div>
                <div class="product-stock">Stok: <span class="stock-val">8</span></div>
            </div>

            <div class="product-card" data-name="Origin Burger" data-price="42000" data-category="burger" data-stock="5">
                <div class="product-thumb">🍔</div>
                <div class="product-name">Origin Burger</div>
                <div class="product-price">Rp 42.000</div>
                <div class="badge-new">Hot</div>
                <div class="product-stock">Stok: <span class="stock-val">5</span></div>
            </div>

            <div class="product-card" data-name="Chicken Pop" data-price="32000" data-category="snack" data-stock="12">
                <div class="product-thumb">🍗</div>
                <div class="product-name">Chicken Pop</div>
                <div class="product-price">Rp 32.000</div>
                <div class="product-stock">Stok: <span class="stock-val">12</span></div>
            </div>

            <div class="product-card" data-name="Happy Breakfast" data-price="38000" data-category="snack" data-stock="6">
                <div class="product-thumb">🥪</div>
                <div class="product-name">Happy Breakfast</div>
                <div class="product-price">Rp 38.000</div>
                <div class="badge-new">New</div>
                <div class="product-stock">Stok: <span class="stock-val">6</span></div>
            </div>

            <div class="product-card" data-name="Kebab Katsu" data-price="45900" data-category="snack" data-stock="7">
                <div class="product-thumb">🌯</div>
                <div class="product-name">Kebab Katsu</div>
                <div class="product-price">Rp 45.900</div>
                <div class="product-stock">Stok: <span class="stock-val">7</span></div>
            </div>

            <div class="product-card" data-name="Caffe Latte" data-price="31000" data-category="drink" data-stock="15">
                <div class="product-thumb">☕</div>
                <div class="product-name">Caffe Latte</div>
                <div class="product-price">Rp 31.000</div>
                <div class="product-stock">Stok: <span class="stock-val">15</span></div>
            </div>

            <div class="product-card" data-name="Chicken Nugget" data-price="24000" data-category="snack" data-stock="9">
                <div class="product-thumb">🍟</div>
                <div class="product-name">Chicken Nugget</div>
                <div class="product-price">Rp 24.000</div>
                <div class="product-stock">Stok: <span class="stock-val">9</span></div>
            </div>

            <div class="product-card" data-name="Caffe Latte" data-price="31000" data-category="drink" data-stock="15">
                <div class="product-thumb">☕</div>
                <div class="product-name">Caffe Latte</div>
                <div class="product-price">Rp 31.000</div>
                <div class="product-stock">Stok: <span class="stock-val">15</span></div>
            </div>

            <div class="product-card" data-name="Chicken Nugget" data-price="24000" data-category="snack" data-stock="9">
                <div class="product-thumb">🍟</div>
                <div class="product-name">Chicken Nugget</div>
                <div class="product-price">Rp 24.000</div>
                <div class="product-stock">Stok: <span class="stock-val">9</span></div>
            </div>

            <div class="product-card" data-name="Caffe Latte" data-price="31000" data-category="drink" data-stock="15">
                <div class="product-thumb">☕</div>
                <div class="product-name">Caffe Latte</div>
                <div class="product-price">Rp 31.000</div>
                <div class="product-stock">Stok: <span class="stock-val">15</span></div>
            </div>

            <div class="product-card" data-name="Chicken Nugget" data-price="24000" data-category="snack" data-stock="9">
                <div class="product-thumb">🍟</div>
                <div class="product-name">Chicken Nugget</div>
                <div class="product-price">Rp 24.000</div>
                <div class="product-stock">Stok: <span class="stock-val">9</span></div>
            </div>

            <div class="product-card" data-name="Caffe Latte" data-price="31000" data-category="drink" data-stock="15">
                <div class="product-thumb">☕</div>
                <div class="product-name">Caffe Latte</div>
                <div class="product-price">Rp 31.000</div>
                <div class="product-stock">Stok: <span class="stock-val">15</span></div>
            </div>

            <div class="product-card" data-name="Chicken Nugget" data-price="24000" data-category="snack" data-stock="9">
                <div class="product-thumb">🍟</div>
                <div class="product-name">Chicken Nugget</div>
                <div class="product-price">Rp 24.000</div>
                <div class="product-stock">Stok: <span class="stock-val">9</span></div>
            </div>

            <div class="product-card" data-name="Caffe Latte" data-price="31000" data-category="drink" data-stock="15">
                <div class="product-thumb">☕</div>
                <div class="product-name">Caffe Latte</div>
                <div class="product-price">Rp 31.000</div>
                <div class="product-stock">Stok: <span class="stock-val">15</span></div>
            </div>

            <div class="product-card" data-name="Chicken Nugget" data-price="24000" data-category="snack" data-stock="9">
                <div class="product-thumb">🍟</div>
                <div class="product-name">Chicken Nugget</div>
                <div class="product-price">Rp 24.000</div>
                <div class="product-stock">Stok: <span class="stock-val">9</span></div>
            </div>

            <div class="product-card" data-name="Chicken Nugget" data-price="24000" data-category="snack" data-stock="9">
                <div class="product-thumb">🍟</div>
                <div class="product-name">Chicken Nugget</div>
                <div class="product-price">Rp 24.000</div>
                <div class="product-stock">Stok: <span class="stock-val">9</span></div>
            </div>

            <div class="product-card" data-name="Caffe Latte" data-price="31000" data-category="drink" data-stock="15">
                <div class="product-thumb">☕</div>
                <div class="product-name">Caffe Latte</div>
                <div class="product-price">Rp 31.000</div>
                <div class="product-stock">Stok: <span class="stock-val">15</span></div>
            </div>

            <div class="product-card" data-name="Chicken Nugget" data-price="24000" data-category="snack" data-stock="9">
                <div class="product-thumb">🍟</div>
                <div class="product-name">Chicken Nugget</div>
                <div class="product-price">Rp 24.000</div>
                <div class="product-stock">Stok: <span class="stock-val">9</span></div>
            </div>

            <!-- Tambah produk baru cukup copy block di atas -->
        </div> --}}
        <div class="product-grid" id="productGrid">
@foreach ($products as $p)
    <div class="product-card
        {{ $p->stok <= 0 ? 'out-stock' : '' }}"
        data-name="{{ $p->nama }}"
        data-price="{{ $p->harga_jual }}"
        data-category="{{ $p->category_id }}"
        data-stock="{{ $p->stok }}"
    >
        <div class="product-thumb">📦</div>

        <div class="product-name">{{ $p->nama }}</div>
        <div class="product-price">Rp {{ number_format($p->harga_jual, 0, ',', '.') }}</div>

        <div class="product-stock">
            Stok: <span class="stock-val">{{ $p->stok }}</span>
        </div>
    </div>
@endforeach
</div>

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-2">
            <div id="paginationInfo" class="small text-muted"></div>
            <nav>
                <ul class="pagination pagination-sm mb-0" id="pagination"></ul>
            </nav>
        </div>
    </section>
</div>
    <!-- ORDER PANEL -->
    <div class="col-12 col-md-4">
    <aside class="order-panel">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <div class="order-title">Order Produk</div>
                <div class="text-muted" style="font-size:10px;">Selected items</div>
            </div>
            <div class="rounded-circle bg-white d-flex align-items-center justify-content-center"
                 style="width:26px;height:26px;box-shadow:0 4px 10px rgba(15,23,42,0.08);">
                <i class="bi bi-person" style="font-size:14px;"></i>
            </div>
        </div>

        <ul class="order-list" id="orderList"></ul>

        <div class="mt-2">
            <div class="summary-row">
                <span>Sub Total</span>
                <span id="subTotal">Rp 0</span>
            </div>

            <div class="summary-row align-items-center">
                <span>Discount</span>
                <span class="d-flex align-items-center gap-1">
                    <input type="number" min="0" class="form-control form-control-sm text-end"
                           id="discountValue" value="0" style="width:90px;">
                    <select id="discountType" class="form-select form-select-sm" style="width:70px;">
                        <option value="rp" selected>Rp</option>
                        <option value="percent">%</option>
                    </select>
                </span>
            </div>

            <div class="summary-row">
                <span>Setelah Discount</span>
                <strong id="afterDiscount">Rp 0</strong>
            </div>

            <div class="summary-row align-items-center">
                <span>PPN (%)</span>
                <span>
                    <input type="number" min="0" class="form-control form-control-sm text-end"
                           id="ppnValue" value="11" style="width:90px;">
                </span>
            </div>

            <div class="summary-row">
                <span>Setelah PPN</span>
                <strong id="afterPpn">Rp 0</strong>
            </div>

            <div class="summary-row align-items-center">
                <span>Bayar</span>
                <span>
                    <input type="number" min="0" class="form-control form-control-sm text-end"
                           id="payAmount" placeholder="0" style="width:120px;">
                </span>
            </div>

            <div class="summary-row">
                <span>Kembalian</span>
                <strong id="changeAmount">Rp 0</strong>
            </div>
        </div>

        <button class="btn-charge" id="btnCharge">
            Charge <strong id="chargeAmount">Rp 0</strong>
            <span><i class="bi bi-arrow-right-circle-fill"></i></span>
        </button>
    </aside>
    </div>
    </div>
@push('scripts')
<script>
    let grandTotal = 0;

    const state = {
        perPage: 8,
        currentPage: 1,
        search: '',
        category: 'all'
    };

    const $grid = $('#productGrid');
    const allProducts = Array.from($grid.children('.product-card'));

    function formatRupiah(val) {
        val = isNaN(val) ? 0 : Math.floor(val);
        return 'Rp ' + val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function changeStock(name, delta) {
        const card = $('.product-card[data-name="' + name + '"]');
        if (!card.length) return false;

        let stock = parseInt(card.attr('data-stock')) || 0;
        const newStock = stock + delta;
        if (newStock < 0) return false;

        card.attr('data-stock', newStock);
        card.find('.stock-val').text(newStock);

        if (newStock === 0) card.addClass('out-stock');
        else card.removeClass('out-stock');
        return true;
    }

    function updateChange() {
        const pay = parseFloat($('#payAmount').val()) || 0;
        const change = pay - grandTotal;
        $('#changeAmount').text(formatRupiah(change > 0 ? change : 0));
    }

    function recalcTotals() {
        let sub = 0;
        $('#orderList .order-item').each(function () {
            const price = parseFloat($(this).data('price'));
            const qty = parseInt($(this).find('.qty-value').text(), 10);
            sub += price * qty;
        });

        const discVal = parseFloat($('#discountValue').val()) || 0;
        const discType = $('#discountType').val();
        let discAmount = (discType === 'rp')
            ? Math.min(discVal, sub)
            : sub * (discVal / 100);

        const afterDiscount = Math.max(sub - discAmount, 0);
        const ppnPercent = parseFloat($('#ppnValue').val()) || 0;
        const ppnAmount = afterDiscount * (ppnPercent / 100);
        const afterPpn = afterDiscount + ppnAmount;

        grandTotal = afterPpn;

        $('#subTotal').text(formatRupiah(sub));
        $('#afterDiscount').text(formatRupiah(afterDiscount));
        $('#afterPpn').text(formatRupiah(afterPpn));
        $('#chargeAmount').text(formatRupiah(afterPpn));

        updateChange();
    }

    function getFilteredProducts() {
        return allProducts.filter(card => {
            const $c = $(card);
            const cat = $c.data('category');
            const name = String($c.data('name')).toLowerCase();

            // if (state.category !== 'all' && cat !== state.category) return false;
            if (state.category !== 'all' && String(cat) !== String(state.category)) return false;

            if (state.search && !name.includes(state.search)) return false;
            return true;
        });
    }

    function renderPagination(totalPages) {
        const $pagination = $('#pagination');
        const $info = $('#paginationInfo');
        $pagination.empty();

        if (totalPages <= 1) {
            $info.text('');
            return;
        }

        $info.text('Halaman ' + state.currentPage + ' / ' + totalPages);

        function addBtn(label, page, disabled, active) {
            const li = $('<li class="page-item"></li>');
            if (disabled) li.addClass('disabled');
            if (active) li.addClass('active');
            const a = $('<a class="page-link" href="#"></a>').text(label);
            if (!disabled) a.attr('data-page', page);
            li.append(a);
            $pagination.append(li);
        }

        addBtn('«', state.currentPage - 1, state.currentPage === 1, false);

        for (let p = 1; p <= totalPages; p++) {
            addBtn(p, p, false, p === state.currentPage);
        }

        addBtn('»', state.currentPage + 1, state.currentPage === totalPages, false);
    }

    function applyFiltersAndPaginate() {
        const filtered = getFilteredProducts();
        const totalPages = Math.max(1, Math.ceil(filtered.length / state.perPage));
        if (state.currentPage > totalPages) state.currentPage = totalPages;

        $grid.empty();
        const start = (state.currentPage - 1) * state.perPage;
        const end = start + state.perPage;
        filtered.slice(start, end).forEach(card => $grid.append(card));

        renderPagination(totalPages);
    }

    function addToCart(name, price) {
        if (!changeStock(name, -1)) {
            Swal.fire('Stok habis', 'Stok ' + name + ' sudah habis.', 'warning');
            return;
        }

        const list = $('#orderList');
        const existing = list.find('.order-item[data-name="' + name + '"]');

        if (existing.length) {
            const qtySpan = existing.find('.qty-value');
            qtySpan.text(parseInt(qtySpan.text(), 10) + 1);
        } else {
            const item = $(`
                <li class="order-item" data-name="${name}" data-price="${price}">
                    <div>
                        <div class="order-item-name">${name}</div>
                        <div class="order-item-price">${formatRupiah(price)}</div>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="qty-box">
                            <div class="qty-btn qty-minus">-</div>
                            <div class="qty-value">1</div>
                            <div class="qty-btn qty-plus">+</div>
                        </div>
                        <i class="bi bi-x-lg remove-item"></i>
                    </div>
                </li>
            `);
            list.append(item);
        }
        recalcTotals();
    }

    $(function () {
        applyFiltersAndPaginate();
        recalcTotals();

        // Klik produk
        $('#productGrid').on('click', '.product-card', function () {
            if ($(this).hasClass('out-stock')) {
                Swal.fire('Stok habis', 'Produk ini sudah tidak tersedia.', 'warning');
                return;
            }
            const name = $(this).data('name');
            const price = parseFloat($(this).data('price'));
            addToCart(name, price);
        });

        // Pagination
        $('#pagination').on('click', '.page-link', function (e) {
            e.preventDefault();
            const page = parseInt($(this).data('page'));
            if (!page || page === state.currentPage) return;
            state.currentPage = page;
            applyFiltersAndPaginate();
        });

        // Category
        $('.category-pill').on('click', function () {
            $('.category-pill').removeClass('active');
            $(this).addClass('active');
            state.category = $(this).data('category');
            state.currentPage = 1;
            applyFiltersAndPaginate();
        });

        // Search
        $('#searchItem').on('input', function () {
            state.search = $(this).val().toLowerCase();
            state.currentPage = 1;
            applyFiltersAndPaginate();
        });

        // Qty plus / minus / remove
        $('#orderList').on('click', '.qty-plus', function (e) {
            e.stopPropagation();
            const item = $(this).closest('.order-item');
            const name = item.data('name');
            if (!changeStock(name, -1)) {
                Swal.fire('Stok habis', 'Stok ' + name + ' sudah habis.', 'warning');
                return;
            }
            const v = $(this).siblings('.qty-value');
            v.text(parseInt(v.text(), 10) + 1);
            recalcTotals();
        });

        $('#orderList').on('click', '.qty-minus', function (e) {
            e.stopPropagation();
            const item = $(this).closest('.order-item');
            const name = item.data('name');
            const v = $(this).siblings('.qty-value');
            let qty = parseInt(v.text(), 10);

            if (qty <= 1) {
                changeStock(name, +1);
                item.remove();
            } else {
                qty -= 1;
                v.text(qty);
                changeStock(name, +1);
            }
            recalcTotals();
        });

        $('#orderList').on('click', '.remove-item', function (e) {
            e.stopPropagation();
            const item = $(this).closest('.order-item');
            const name = item.data('name');
            const qty = parseInt(item.find('.qty-value').text(), 10) || 0;
            if (qty > 0) changeStock(name, +qty);
            item.remove();
            recalcTotals();
        });

        // Discount & PPN
        $('#discountValue, #discountType, #ppnValue').on('input change', recalcTotals);

        // Bayar
        $('#payAmount').on('input', updateChange);

        // Charge
        $('#btnCharge').on('click', function () {
            if (grandTotal <= 0) {
                Swal.fire('Tidak ada transaksi', 'Silakan pilih item terlebih dahulu.', 'warning');
                return;
            }

            const pay = parseFloat($('#payAmount').val()) || 0;
            if (pay < grandTotal) {
                Swal.fire(
                    'Pembayaran kurang',
                    'Total: ' + formatRupiah(grandTotal) +
                    '<br>Dibayar: ' + formatRupiah(pay),
                    'error'
                );
                return;
            }

            const change = pay - grandTotal;
            Swal.fire({
                icon: 'success',
                title: 'Transaksi berhasil',
                html:
                    'Total: <b>' + formatRupiah(grandTotal) + '</b><br>' +
                    'Dibayar: <b>' + formatRupiah(pay) + '</b><br>' +
                    'Kembalian: <b>' + formatRupiah(change) + '</b>'
            }).then(() => {
                $('#orderList').empty();
                $('#discountValue').val(0);
                $('#discountType').val('rp');
                $('#ppnValue').val(11);
                $('#payAmount').val('');
                $('#changeAmount').text('Rp 0');
                recalcTotals();
            });
        });
    });
</script>

@endpush
@endsection
