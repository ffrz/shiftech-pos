@php
  use App\Models\AclResource;

  if (!isset($menu_active)) {
      $menu_active = null;
  }
@endphp

<aside class="main-sidebar sidebar-light-primary elevation-4">
  <a class="brand-link" href="{{ url('admin/') }}">
    <img class="brand-image img-circle elevation-3" src="{{ url('dist/img/logo.png') }}" alt="App Logo" style="opacity: .8">
    <span class="brand-text font-weight-light">{{ App\Models\Setting::value('company.name', 'My Store') }}</span>
  </a>
  <div class="sidebar">
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column nav-flat nav-flat nav-collapse-hide-child" data-widget="treeview" data-accordion="false" role="menu">
        <li class="nav-item">
          <a class="nav-link {{ $nav_active == 'dashboard' ? 'active' : '' }}" href="{{ url('admin/') }}">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
          </a>
        </li>

        {{-- Sales Menu Begin --}}
        @if (Auth::user()->canAccess(AclResource::SALES_MENU))
          <li class="nav-item {{ $menu_active == 'sales' ? 'menu-open' : '' }}">
            <a class="nav-link {{ $menu_active == 'sales' ? 'active' : '' }}" href="#">
              <i class="nav-icon fas fa-store"></i>
              <p>
                Penjualan
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              @if (Auth::user()->canAccess(AclResource::SALES_ORDER_LIST))
                <li class="nav-item">
                  <a class="nav-link {{ $nav_active == 'sales-order' ? 'active' : '' }}" href="{{ url('/admin/sales-order') }}">
                    <i class="nav-icon fas fa-cart-arrow-down"></i>
                    <p>Order Penjualan</p>
                  </a>
                </li>
              @endif
              @if (Auth::user()->canAccess(AclResource::CUSTOMER_LIST))
                <li class="nav-item">
                  <a class="nav-link {{ $nav_active == 'customer' ? 'active' : '' }}" href="{{ url('/admin/customer') }}">
                    <i class="nav-icon fas fa-user"></i>
                    <p>Pelanggan</p>
                  </a>
                </li>
              @endif
            </ul>
          </li>
        @endif
        {{-- Sales Menu End --}}
        @if (Auth::user()->canAccess(AclResource::SERVICE_ORDER_LIST))
          <li class="nav-item">
            <a class="nav-link {{ $nav_active == 'service-order' ? 'active' : '' }}" href="{{ url('/admin/service-order') }}">
              <i class="nav-icon fas fa-hand-holding-medical"></i>
              <p>Order Servis</p>
            </a>
          </li>
        @endif
        {{-- Inventory Menu Begin --}}
        @if (Auth::user()->canAccess(AclResource::INVENTORY_MENU))
          <li class="nav-item {{ $menu_active == 'inventory' ? 'menu-open' : '' }}">
            <a class="nav-link {{ $menu_active == 'inventory' ? 'active' : '' }}" href="#">
              <i class="nav-icon fas fa-warehouse"></i>
              <p>
                Inventori
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              @if (true)
                <li class="nav-item">
                  <a class="nav-link {{ $nav_active == 'stock-adjustment' ? 'active' : '' }}" href="{{ url('/admin/stock-adjustment') }}">
                    <i class="nav-icon fas fa-right-left"></i>
                    <p>Penyesuaian Stok</p>
                  </a>
                </li>
              @endif
              @if (true)
                <li class="nav-item">
                  <a class="nav-link {{ $nav_active == 'stock-update' ? 'active' : '' }}" href="{{ url('/admin/stock-update') }}">
                    <i class="nav-icon fas fa-file-waveform"></i>
                    <p>Riwayat Stok</p>
                  </a>
                </li>
              @endif
              @if (Auth::user()->canAccess(AclResource::PRODUCT_LIST))
                <li class="nav-item">
                  <a class="nav-link {{ $nav_active == 'product' ? 'active' : '' }}" href="{{ url('/admin/product') }}">
                    <i class="nav-icon fas fa-box"></i>
                    <p>Produk</p>
                  </a>
                </li>
              @endif
              @if (Auth::user()->canAccess(AclResource::PRODUCT_CATEGORY_MANAGEMENT))
                <li class="nav-item">
                  <a class="nav-link {{ $nav_active == 'product-category' ? 'active' : '' }}" href="{{ url('/admin/product-category') }}">
                    <i class="nav-icon fas fa-boxes"></i>
                    <p>Kategori Produk</p>
                  </a>
                </li>
              @endif
            </ul>
          </li>
        @endif
        {{-- Inventory Menu End --}}

        {{-- Purchasing Menu Begin --}}
        @if (Auth::user()->canAccess(AclResource::PURCHASING_MENU))
          <li class="nav-item {{ $menu_active == 'purchasing' ? 'menu-open' : '' }}">
            <a class="nav-link {{ $menu_active == 'purchasing' ? 'active' : '' }}" href="#">
              <i class="nav-icon fas fa-truck"></i>
              <p>
                Pembelian
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              @if (Auth::user()->canAccess(AclResource::PURCHASE_ORDER_LIST))
                <li class="nav-item">
                  <a class="nav-link {{ $nav_active == 'purchase-order' ? 'active' : '' }}" href="{{ url('/admin/purchase-order') }}">
                    <i class="nav-icon fas fa-cart-shopping"></i>
                    <p>Order Pembelian</p>
                  </a>
                </li>
              @endif
              @if (Auth::user()->canAccess(AclResource::SUPPLIER_LIST))
                <li class="nav-item">
                  <a class="nav-link {{ $nav_active == 'supplier' ? 'active' : '' }}" href="{{ url('/admin/supplier') }}">
                    <i class="nav-icon fas fa-user"></i>
                    <p>Pemasok</p>
                  </a>
                </li>
              @endif
            </ul>
          </li>
        @endif
        {{-- End of Purchasing Menu --}}

        {{-- Expense Menu Begin --}}
        @if (Auth::user()->canAccess(AclResource::EXPENSE_MENU))
          <li class="nav-item {{ $menu_active == 'expense' ? 'menu-open' : '' }}">
            <a class="nav-link {{ $menu_active == 'expense' ? 'active' : '' }}" href="#">
              <i class="nav-icon fas fa-money-bill"></i>
              <p>
                Pengeluaran
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a class="nav-link {{ $nav_active == 'expense' ? 'active' : '' }}" href="{{ url('/admin/expense') }}">
                  <i class="nav-icon fas fa-money-bills"></i>
                  <p>Pengeluaran</p>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link {{ $nav_active == 'expense-category' ? 'active' : '' }}" href="{{ url('/admin/expense-category') }}">
                  <i class="nav-icon fas fa-boxes"></i>
                  <p>Kategori Pengeluaran</p>
                </a>
              </li>
            </ul>
          </li>
        @endif
        {{-- End of Expense Menu --}}

        {{-- Expense Menu Begin --}}
        @if (Auth::user()->canAccess(AclResource::FINANCE_MENU))
          <li class="nav-item {{ $menu_active == 'finance' ? 'menu-open' : '' }}">
            <a class="nav-link {{ $menu_active == 'finance' ? 'active' : '' }}" href="#">
              <i class="nav-icon fas fa-money-bill-transfer"></i>
              <p>
                Keuangan
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a class="nav-link {{ $nav_active == 'cash-transaction' ? 'active' : '' }}" href="{{ url('/admin/cash-transaction') }}">
                  <i class="nav-icon fas fa-money-bill-transfer"></i>
                  <p>Transaksi</p>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link {{ $nav_active == 'cash-transaction-category' ? 'active' : '' }}" href="{{ url('/admin/cash-transaction-category') }}">
                  <i class="nav-icon fas fa-boxes"></i>
                  <p>Kategori Transaksi</p>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link {{ $nav_active == 'cash-account' ? 'active' : '' }}" href="{{ url('/admin/cash-account') }}">
                  <i class="nav-icon fas fa-money-check"></i>
                  <p>Akun / Rek</p>
                </a>
              </li>
            </ul>
          </li>
        @endif
        {{-- End of Expense Menu --}}

        {{-- @if (Auth::user()->canAccess(AclResource::DEBT_MENU)) --}}
        <li class="nav-item {{ $menu_active == 'debt' ? 'menu-open' : '' }}">
          <a class="nav-link {{ $menu_active == 'debt' ? 'active' : '' }}" href="#">
            <i class="nav-icon fas fa-file-invoice-dollar"></i>
            <p>
              Utan Piutang
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a class="nav-link {{ $nav_active == 'customer_debt' ? 'active' : '' }}" href="{{ url('/admin/debt/customer') }}">
                <i class="nav-icon fas fa-file-waveform"></i>
                <p>Piutang Pelanggan</p>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ $nav_active == 'supplier_debt' ? 'active' : '' }}" href="{{ url('/admin/debt/supplier') }}">
                <i class="nav-icon fas fa-file-waveform"></i>
                <p>Utang Pemasok</p>
              </a>
            </li>
          </ul>
        </li>
        {{-- @endif --}}

        {{-- Report Menu --}}
        @if (Auth::user()->canAccess(AclResource::REPORT_MENU))
          <li class="nav-item {{ $menu_active == 'report' ? 'menu-open' : '' }}">
            <a class="nav-link {{ $menu_active == 'report' ? 'active' : '' }}" href="/admin/report/">
              <i class="nav-icon fas fa-file-waveform"></i>
              <p>Laporan</p>
            </a>
          </li>
        @endif
        {{-- End Report Menu --}}

        {{-- System Menu --}}
        @if (Auth::user()->canAccess(AclResource::SYSTEM_MENU))
          <li class="nav-item {{ $menu_active == 'system' ? 'menu-open' : '' }}">
            <a class="nav-link {{ $menu_active == 'system' ? 'active' : '' }}" href="#">
              <i class="nav-icon fas fa-gears"></i>
              <p>
                Sistem
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              @if (Auth::user()->canAccess(AclResource::USER_ACTIVITY))
                <li class="nav-item">
                  <a class="nav-link {{ $nav_active == 'user-activity' ? 'active' : '' }}" href="{{ url('/admin/user-activity') }}">
                    <i class="nav-icon fas fa-file-waveform"></i>
                    <p>Log Aktivitas</p>
                  </a>
                </li>
              @endif
              @if (Auth::user()->canAccess(AclResource::USER_MANAGEMENT))
                <li class="nav-item">
                  <a class="nav-link {{ $nav_active == 'user' ? 'active' : '' }}" href="{{ url('/admin/user') }}">
                    <i class="nav-icon fas fa-users"></i>
                    <p>Pengguna</p>
                  </a>
                </li>
              @endif
              @if (Auth::user()->canAccess(AclResource::USER_GROUP_MANAGEMENT))
                <li class="nav-item">
                  <a class="nav-link {{ $nav_active == 'user-group' ? 'active' : '' }}" href="{{ url('/admin/user-group') }}">
                    <i class="nav-icon fas fa-user-group"></i>
                    <p>Grup Pengguna</p>
                  </a>
                </li>
              @endif
              @if (Auth::user()->canAccess(AclResource::SETTINGS))
                <li class="nav-item">
                  <a class="nav-link {{ $nav_active == 'settings' ? 'active' : '' }}" href="{{ url('/admin/settings') }}">
                    <i class="nav-icon fas fa-gear"></i>
                    <p>Pengaturan</p>
                  </a>
                </li>
              @endif
            </ul>
          </li>
        @endif
        {{-- End of System  menu --}}

        <li class="nav-item">
          <a class="nav-link {{ $nav_active == 'profile' ? 'active' : '' }}" href="{{ url('/admin/user/profile/') }}">
            <i class="nav-icon fas fa-user"></i>
            <p>{{ Auth::user()->username }}</p>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ url('admin/logout') }}">
            <i class="nav-icon fas fa-right-from-bracket"></i>
            <p>Keluar</p>
          </a>
        </li>
      </ul>
    </nav>
  </div>
</aside>
