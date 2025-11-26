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
        <div class="product-grid" id="productGrid">
@foreach ($products as $p)
    <div class="product-card
        {{ $p->stok <= 0 ? 'out-stock' : '' }}"
           data-id="{{ $p->id }}"
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
        <div class="mb-2">
    <label style="font-size:12px;font-weight:600;">Customer</label>
    <select id="customerSelect" class="form-select form-select-sm"></select>
</div>
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
                <span>Total</span>
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

    <!-- MODAL STRUK -->
<div class="modal fade" id="receiptModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
      <div class="modal-content" id="receiptArea" style="font-size:13px;">

        <div class="modal-header">
          <h5 class="modal-title">Struk Pembayaran</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

          <div class="text-center mb-2">
              <h6 class="fw-bold">{{ Auth::user()->tenant->outlets->first()?->outlet_name ?? '-' }}</h6>
              <div style="font-size:11px">{{ Auth::user()->tenant->outlets->first()?->outlet_address ?? '-' }}</div>
              <hr>
          </div>

          <div id="receiptItems"></div>

          <hr>

          <div class="d-flex justify-content-between">
              <span>Subtotal</span>
              <span id="r_subtotal"></span>
          </div>
          <div class="d-flex justify-content-between">
              <span>Discount</span>
              <span id="r_discount"></span>
          </div>
          <div class="d-flex justify-content-between">
              <span>PPN</span>
              <span id="r_ppn"></span>
          </div>

          <hr>
          <div class="d-flex justify-content-between fw-bold">
              <span>Total</span>
              <span id="r_total"></span>
          </div>
          <div class="d-flex justify-content-between">
              <span>Dibayar</span>
              <span id="r_paid"></span>
          </div>
          <div class="d-flex justify-content-between">
              <span>Kembalian</span>
              <span id="r_change"></span>
          </div>

          <hr>
          <div class="text-center" style="font-size:11px;">
              Terima kasih telah berbelanja 🙏
          </div>
        </div>

        <div class="modal-footer">
          <button class="btn-soft light no-print btn-sm" data-bs-dismiss="modal">Close</button>
          <button class="btn-soft primary no-print btn-sm" onclick="printReceipt()">Print</button>
        </div>

      </div>
    </div>
  </div>
  <style>
    @media print {
        .no-print {
        display: none !important;
        visibility: hidden !important;
    }
        body * {
            visibility: hidden;
        }
        #receiptArea, #receiptArea * {
            visibility: visible;
        }
        #receiptArea {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
    }
    </style>

@push('scripts')
<script>
    function printReceipt() {
    window.print();
}
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

    function addToCart(id,name, price) {
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
                <li class="order-item"  data-id="${id}" data-name="${name}" data-price="${price}">
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
            const id = $(this).data('id');
            const name = $(this).data('name');
            const price = parseFloat($(this).data('price'));
            addToCart(id, name, price);
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


        $('#btnCharge').on('click', function () {

if (grandTotal <= 0) {
    Swal.fire('Tidak ada item!', 'Silakan pilih barang terlebih dahulu.', 'warning');
    return;
}

const payAmount = parseFloat($('#payAmount').val()) || 0;
if (payAmount < grandTotal) {
    Swal.fire('Pembayaran kurang!', '', 'error');
    return;
}

let items = [];

$('#orderList .order-item').each(function () {
    items.push({
        product_id: $(this).data('id'),
        qty: parseInt($(this).find('.qty-value').text(), 10),
        price: parseFloat($(this).data('price')),
    });
});

let payload = {
    customer_id: $('#customerSelect').val() || null,
    items: items,

    sub_total: parseInt($('#subTotal').text().replace(/[Rp .]/g, '')) || 0,
    discount_type: $('#discountType').val(),
    discount_value: parseInt($('#discountValue').val()) || 0,
    total_after_discount: parseInt($('#afterDiscount').text().replace(/[Rp .]/g, '')) || 0,

    ppn: parseInt($('#ppnValue').val()) || 0,
    total_after_ppn: parseInt($('#afterPpn').text().replace(/[Rp .]/g, '')) || 0,

    pay_amount: payAmount,
    change_amount: payAmount - grandTotal,

    _token: "{{ csrf_token() }}"
};

$.ajax({
    url: "{{ route('pos.store') }}",
    type: "POST",
    data: payload,
    success: function (res) {
          // --- Generate item struk ---
    let itemHTML = '';
    let selectedCustomer = $('#customerSelect').select2('data')[0];
$('#r_customer').text(selectedCustomer ? selectedCustomer.text : '-');

    $('#orderList .order-item').each(function () {
        const name = $(this).data('name');
        const price = $(this).data('price');
        const qty    = parseInt($(this).find('.qty-value').text(), 10);
        const total  = price * qty;

        itemHTML += `
        <div class="d-flex justify-content-between">
    <span>Customer</span>
    <span id="r_customer"></span>
</div>

            <div class="d-flex justify-content-between">
                <div>${name} x${qty}</div>
                <div>${formatRupiah(total)}</div>
            </div>
        `;
    });
    $('#receiptItems').html(itemHTML);


    // Summary Struk
    $('#r_subtotal').text($('#subTotal').text());
    $('#r_discount').text($('#discountValue').val() + ($('#discountType').val() === 'percent' ? '%' : ' Rp'));
    $('#r_ppn').text($('#ppnValue').val() + '%');
    $('#r_total').text(formatRupiah(grandTotal));
    $('#r_paid').text(formatRupiah(payload.pay_amount));
    $('#r_change').text(formatRupiah(payload.change_amount));

    // Tampilkan modal struk
    const modal = new bootstrap.Modal('#receiptModal');
    modal.show();

    // Reset keranjang
    $('#orderList').empty();
    $('#customerSelect').val(null).trigger('change');
    $('#discountValue').val(0);
    $('#discountType').val('rp');
    $('#ppnValue').val(11);
    $('#payAmount').val('');
    $('#changeAmount').text('Rp 0');
    recalcTotals();

        // Swal.fire({
        //     icon: 'success',
        //     title: 'Transaksi berhasil disimpan!'
        // }).then(() => {
        //     location.reload();
        // });
            // 👉 SweetAlert muncul SETELAH modal ditutup
    $('#receiptModal').on('hidden.bs.modal', function () {
        Swal.fire({
            icon: 'success',
            title: 'Transaksi berhasil disimpan!'
        }).then(() => {
            location.reload();
        });
    });
    },
    error: function (xhr) {
        Swal.fire('Error', xhr.responseText, 'error');
    }
});

});

    });
    $('#customerSelect').select2({
    placeholder: "Pilih Customer...",
    allowClear: true,
    width: '100%',
    ajax: {
        url: "{{ route('customers.data') }}", // pastikan route benar
        dataType: 'json',
        delay: 250,
        data: function (params) {
            return {
                search: params.term || '',
                per_page: 10
            };
        },
        processResults: function (data) {
            return {
                results: data.data.map(c => ({
                    id: c.id,
                    text: c.nama + ' (' + c.telepon + ')',
                    raw: c
                }))
            };
        },
        cache: true
    }
});

</script>

@endpush
@endsection
