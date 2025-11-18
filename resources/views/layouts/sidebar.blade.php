   <aside class="sidebar">
       <div class="sidebar-logo" data-bs-toggle="tooltip" data-bs-placement="right" title="M POS - Main">
           M
       </div>
       <a href="{{ route('home') }}">
           <div class="side-btn {{ request()->routeIs('home') ? 'active' : '' }}" data-bs-toggle="tooltip"
               data-bs-placement="right" title="Dashboard">
               <i class="bi bi-house-door-fill"></i>
           </div>
       </a>
       <a href="{{ route('pos.index') }}">
       <div class="side-btn {{ request()->routeIs('pos.index') ? 'active' : '' }}" data-bs-toggle="tooltip" data-bs-placement="right" title="POS / Kasir">
           <i class="bi bi-speedometer2"></i>
       </div>
       </a>
       <div class="side-btn" data-bs-toggle="tooltip" data-bs-placement="right" title="Daftar Transaksi">
           <i class="bi bi-receipt"></i>
       </div>
       <a href="{{ route('suppliers.index') }}">
           <div class="side-btn {{ request()->routeIs('suppliers.*') ? 'active' : '' }}" data-bs-toggle="tooltip"
               data-bs-placement="right" title="Master Supplier">
               <i class="bi bi-people-fill"></i>
           </div>
       </a>

        <!-- MASTER CUSTOMER ACTIVE -->
        <a href="{{ route('customers.index') }}">
            <div class="side-btn {{ request()->routeIs('customers.*') ? 'active' : '' }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Master Customer">
                <i class="bi bi-person-badge-fill"></i>
            </div>
        </a>

       <a href="{{ route('units.index') }}">
           <div class="side-btn {{ request()->routeIs('units.*') ? 'active' : '' }}" data-bs-toggle="tooltip"
               data-bs-placement="right" title="Master Satuan">
               <i class="bi bi-rulers"></i>
           </div>
       </a>

       <!-- Master Kategori Aktif -->
       <a href="{{ route('categories.index') }}">
           <div class="side-btn {{ request()->routeIs('categories.*') ? 'active' : '' }}" data-bs-toggle="tooltip"
               data-bs-placement="right" title="Master Kategori">
               <i class="bi bi-tags"></i>
           </div>
       </a>
       <!-- MASTER GUDANG ACTIVE -->
       <a href="{{ route('warehouses.index') }}">
           <div class="side-btn {{ request()->routeIs('warehouses.*') ? 'active' : '' }}" data-bs-toggle="tooltip"
               data-bs-placement="right" title="Master Gudang">
               <i class="bi bi-building"></i>
           </div>
       </a>
        <a href="{{ route('products.index') }}">
        <div class="side-btn {{ request()->routeIs('products.*') ? 'active' : '' }}"
             data-bs-toggle="tooltip"
             data-bs-placement="right"
             title="Master Barang">
            <i class="bi bi-box-seam"></i>
        </div>
         </a>
       <div class="side-btn" data-bs-toggle="tooltip" data-bs-placement="right" title="Master Kategori">
           <i class="bi bi-tags"></i>
       </div>
       <div class="side-btn" data-bs-toggle="tooltip" data-bs-placement="right" title="Laporan">
           <i class="bi bi-graph-up"></i>
       </div>
       <div class="side-btn" data-bs-toggle="tooltip" data-bs-placement="right" title="Pengaturan">
           <i class="bi bi-gear"></i>
       </div>
       <div class="side-spacer"></div>
       <a href="{{ route('logout') }}"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
           <div class="side-btn" data-bs-toggle="tooltip" data-bs-placement="right" title="Logout">
               <i class="bi bi-box-arrow-right"></i>
           </div>
       </a>
       <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
           @csrf
       </form>
   </aside>
