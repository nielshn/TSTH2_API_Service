
<!-- Sidebar content -->
<div class="sidebar-content">
    <!-- Sidebar header -->
    <div class="sidebar-section">
        <div class="sidebar-section-body d-flex justify-content-center">
            <h5 class="sidebar-resize-hide flex-grow-1 my-auto">Navigation</h5>

            <div>
                <button type="button"
                    class="btn btn-flat-white btn-icon btn-sm rounded-pill border-transparent sidebar-control sidebar-main-resize d-none d-lg-inline-flex">
                    <i class="ph-arrows-left-right"></i>
                </button>

                <button type="button"
                    class="btn btn-flat-white btn-icon btn-sm rounded-pill border-transparent sidebar-mobile-main-toggle d-lg-none">
                    <i class="ph-x"></i>
                </button>
            </div>
        </div>
    </div>
    <!-- /sidebar header -->

    <!-- Main navigation -->
    <div class="sidebar-section">
        <ul class="nav nav-sidebar" data-nav-type="accordion">
            <!-- Main -->
            <li class="nav-item-header pt-0">
                <div class="text-uppercase fs-sm lh-sm opacity-50 sidebar-resize-hide">
                    Main
                </div>
                <i class="ph-dots-three sidebar-resize-show"></i>
            </li>
            <li class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link active">
                    <i class="ph-house"></i>
                    <span> Dashboard </span>
                </a>
            </li>
            @can('view_barang', 'view_satuan', 'view_jenis_barang', 'view_category_barang', 'view_gudang', 'view_transaction_type')
            <li class="nav-item nav-item-submenu">
                <a href="#" class="nav-link">
                    <i class="ph-squares-four"></i>
                    <span>Master Data</span>
                </a>
                <ul
                    class="nav-group-sub collapse {{ request()->routeIs(
                        'barangs.*',
                        'satuans.*',
                        'jenis-barangs.*',
                        'barang-categories.*',
                        'gudangs.*',
                        'transaction-types.*',
                    )
                        ? 'show'
                        : '' }}">
                    @can('view_barang')
                    <x-nav-item route="barangs" label="Barang" />
                    @endcan
                    @can('view_satuan')
                    <x-nav-item route="satuans" label="Satuan" />
                    @endcan
                    @can('view_jenis_barang')
                    <x-nav-item route="jenis-barangs" label="Jenis Barang" />
                    @endcan
                    @can('view_category_barang')
                    <x-nav-item route="barang-categories" label="Kategori Barang" />
                    @endcan
                    @can('view_gudang')
                    <x-nav-item route="gudangs" label="Gudang" />
                    @endcan
                    @can('view_transaction_type')
                    <x-nav-item route="transaction-types" label="Jenis Transaksi" />
                    @endcan
                </ul>
            </li>
            @endcan
            @can('view_transaction')
            <li class="nav-item">
                <a href="{{route('transactions.index')}}" class="nav-link">
                    <i class="fas fa-exchange-alt"></i>
                    <span>Transaksi</span>
                </a>
            </li>
            @endcan

            <li class="nav-item nav-item-submenu">

                <a href="#" class="nav-link">
                    <i class="mi-settings"></i>
                    <span>Laporan</span>
                </a>
                <ul class="nav-group-sub collapse">
                    <li class="nav-item">
                        <a href="{{route('laporan.transaksi')}}" class="nav-link">Laporan Transaksi</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('laporan.stok')}}" class="nav-link">Laporan Stok Barang</a>
                    </li>
                </ul>
            </li>

            <!-- Settings -->
            <li class="nav-item-header">
                <div class="text-uppercase fs-sm lh-sm opacity-50 sidebar-resize-hide">
                    Other
                </div>
                <i class="ph-dots-three sidebar-resize-show"></i>
            </li>

            @can('view_user','view_role', 'manage_permissions')
            <li class="nav-item nav-item-submenu">

                <a href="#" class="nav-link">
                    <i class="mi-settings"></i>
                    <span>Settings</span>
                </a>
                <ul class="nav-group-sub collapse">
                    @can('view_user')

                    <li class="nav-item">
                        <a href="{{route('users.index')}}" class="nav-link">User</a>
                    </li>
                    @endcan
                    @can('view_role')
                    <li class="nav-item">
                        <a href="{{route('roles.index')}}" class="nav-link">Role</a>
                    </li>
                    @endcan
                    @can('manage_permissions')
                    <li class="nav-item">
                        <a href="{{route('permissions.index')}}" class="nav-link">Akses</a>
                    </li>
                    @endcan
                </ul>
            </li>
            @endcan
            <li class="nav-item">
                <a href="{{route('webs.index')}}" class="nav-link">
                    <i class="ph-desktop"></i>
                    <span>Web</span>
                </a>
            </li>

            <!-- /forms -->
        </ul>
    </div>
    <!-- /main navigation -->
</div>
<!-- /sidebar content -->
