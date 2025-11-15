 <div class="sidebar" id="sidebar">
     <div class="sidebar-inner slimscroll">
         <div id="sidebar-menu" class="sidebar-menu">
             <ul>
                 <li class="{{ request()->routeIs('/') ? 'active' : '' }}">
                     <a href="{{ route('home') }}"><img src="{{ asset('assets/assets/img/icons/dashboard.svg') }}"
                             alt="img"><span>
                             Dashboard</span> </a>
                 </li>
                 @can('menu-master-data')
                     <li class="submenu">
                         <a href="javascript:void(0);"><img src="{{ asset('assets/assets/img/icons/users1.svg') }}"
                                 alt="img"><span>
                                 Master Data</span> <span class="menu-arrow"></span></a>
                         <ul>
                             @can('master-data-barang')
                             <li>
                                <a href="{{ route('barang.index') }}" class="{{ request()->routeIs('barang.*') ? 'active' : '' }}">Barang</a>
                            </li>
                            @endcan
                            @can('master-data-stok')
                            @can('stok-opname')
                                <li>
                                    <a href="{{ route('stok-opname.index') }}" class="{{ request()->routeIs('stok-opname.index') ? 'active' : '' }}">stok opname</a>
                                </li>
                            @endcan
                            @can('stok-opname-log')
                                <li>
                                    <a href="{{ route('stok-opname.logs') }}" class="{{ request()->routeIs('stok-opname.logs') ? 'active' : '' }}">history stok opname</a>
                                </li>
                            @endcan
                            @can('stok-keluar-masuk')
                                <li>
                                <a href="{{ route('stok-transaksi.index') }}" class="{{ request()->routeIs('stok-transaksi.index') ? 'active' : '' }}">keluar masuk barang</a>
                            </li>
                            @endcan
                            @endcan
                            @can('master-data-pembelian')
                            <li>
                                <a href="{{ route('pembelian.index') }}" class="{{ request()->routeIs('pembelian.index') ? 'active' : '' }}">pembelian barang</a>
                            </li>
                            @endcan
                         </ul>
                     </li>
                 @endcan
                 @can('menu-client')
                     <li class="{{ request()->routeIs('client.*') ? 'active' : '' }}">
                         <a href="{{ route('client.index') }}"><img
                                 src="{{ asset('assets/assets/img/icons/users1.svg') }}" alt="img"><span>
                                 Clients</span> </a>
                     </li>
                       @endcan

                       @can('menu-penjualan')
                     <li class="{{ request()->routeIs('sales.*') ? 'active' : '' }}">
                         <a href="{{ route('sales.index') }}"><i class="fa-solid fa-cart-plus"></i><span>
                                 Penjualan Barang</span> </a>
                     </li>
                @endcan
                 @can('menu-user')
                     <li class="submenu">
                         <a href="javascript:void(0);"><img src="{{ asset('assets/assets/img/icons/users1.svg') }}"
                                 alt="img"><span>
                                 Users</span> <span class="menu-arrow"></span></a>
                         <ul>
                             <li><a href="{{ route('users.index') }}"
                                     class="{{ request()->routeIs('users.*') ? 'active' : '' }}">Users List</a></li>
                             <li><a href="{{ route('roles.index') }}"
                                     class="{{ request()->routeIs('roles.*') ? 'active' : '' }}">Roles List</a></li>
                             <li><a href="{{ route('permissions.index') }}"
                                     class="{{ request()->routeIs('permissions.*') ? 'active' : '' }}">Permission</a></li>
                         </ul>
                     </li>
                 @endcan
                 @can('menu-laporan')
                     <li class="submenu">
                         <a href="javascript:void(0);"><i class="fa-solid fa-book"></i><span>
                                 report</span> <span class="menu-arrow"></span></a>
                         <ul>
                               <li><a href="{{ route('reports.sale') }}"
                                     class="{{ request()->routeIs('reports.sale') ? 'active' : '' }}">Report penjualan</a>
                             </li>

                             <li><a href="{{ route('sales-payments.index') }}"
                                     class="{{ request()->routeIs('sales-payments.index') ? 'active' : '' }}">pembayaran penjualan barang</a>
                             </li>
                             <li><a href="{{ route('reports.sold-items') }}"
                                     class="{{ request()->routeIs('reports.sold-items') ? 'active' : '' }}">
                                     Report Penjualan Barang</a>
                             </li>

                         </ul>
                     </li>
                 @endcan
             </ul>
         </div>
     </div>
 </div>
