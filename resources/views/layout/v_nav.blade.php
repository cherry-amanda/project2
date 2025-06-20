<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-xl fixed-start bg-white shadow p-3" id="sidenav-main" style="min-height: 100vh; font-family: 'Segoe UI', sans-serif;">
  <!-- Branding -->
  <div class="d-flex align-items-center mb-3 ps-2">
    <span style="font-size: 28px; color:#6C63FF; font-weight: bold;">∞</span>
    <span class="ms-2 fw-semibold text-dark" style="font-size: 18px;">Infinity Wedding</span>
  </div>

  <hr class="horizontal dark mt-0 mb-3">

  <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
    <ul class="navbar-nav">

      {{-- DASHBOARD --}}
      <li class="nav-item">
        <a class="nav-link {{ request()->is('admin/dashboard') ? 'active bg-gradient-primary text-white' : 'text-dark' }} d-flex align-items-center rounded-3 px-3 py-2" href="/admin/dashboard">
          <span class="material-symbols-rounded me-2">dashboard</span> Dashboard
        </a>
      </li>

      {{-- MENU UTAMA --}}
      <li class="nav-item mt-3">
        <h6 class="ps-3 text-muted text-uppercase text-xs">Menu Utama</h6>
      </li>

      @php
        $menuItems = [
          ['href' => '/admin/vendor', 'icon' => 'store', 'label' => 'Kelola Vendor'],
          ['href' => '/admin/event', 'icon' => 'event_note', 'label' => 'Kelola Kegiatan'],
          ['href' => '/admin/package', 'icon' => 'redeem', 'label' => 'Kelola Paket WO'],
          ['href' => '/admin/pesanan', 'icon' => 'shopping_cart', 'label' => 'Kelola Pesanan'],
          ['href' => '/admin/dates', 'icon' => 'calendar_month', 'label' => 'Kelola Tanggal'],
          ['href' => '/admin/invoice', 'icon' => 'receipt_long', 'label' => 'Invoice'],
          ['href' => '/admin/pengguna', 'icon' => 'group', 'label' => 'Kelola User'],
          ['href' => '/admin/keuangan', 'icon' => 'account_balance_wallet', 'label' => 'Kelola Keuangan'],
        ];
      @endphp

      @foreach($menuItems as $item)
        <li class="nav-item">
          <a class="nav-link {{ request()->is(ltrim($item['href'], '/')) ? 'active bg-gradient-primary text-white' : 'text-dark' }} d-flex align-items-center rounded-3 px-3 py-2" href="{{ $item['href'] }}">
            <span class="material-symbols-rounded me-2">{{ $item['icon'] }}</span> {{ $item['label'] }}
          </a>
        </li>
      @endforeach

      {{-- ACCOUNT --}}
      <li class="nav-item mt-4">
        <h6 class="ps-3 text-muted text-uppercase text-xs">Akun</h6>
      </li>

      

      <li class="nav-item">
        <a class="nav-link {{ request()->is('logout') ? 'active bg-gradient-primary text-white' : 'text-dark' }} d-flex align-items-center rounded-3 px-3 py-2" href="/logout">
          <span class="material-symbols-rounded me-2">logout</span> Log Out
        </a>
      </li>

      
    </ul>
  </div>

  
</aside>
