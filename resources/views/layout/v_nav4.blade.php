<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-xl fixed-start bg-white my-3 shadow-sm" id="sidenav-main">
  <div class="d-flex align-items-center mb-3 ps-2">
    <span style="font-size: 28px; color:#6C63FF; font-weight: bold;">∞</span>
    <span class="ms-2 fw-semibold text-dark" style="font-size: 18px;">Infinity Wedding</span>
  </div>

  <hr class="horizontal dark mt-0 mb-2">

  <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
    <ul class="navbar-nav px-2">

      {{-- DASHBOARD --}}
      <li class="nav-item mb-1">
        <a class="nav-link {{ request()->is('klien/dashboard') ? 'active bg-gradient-primary text-white' : 'text-dark' }} d-flex align-items-center rounded-3 px-3 py-2" href="/klien/dashboard">
          <span class="material-symbols-rounded me-2">dashboard</span>
          <span class="nav-link-text">Dashboard</span>
        </a>
      </li>

      {{-- PROFILE --}}
      <li class="nav-item mb-1">
        <a class="nav-link {{ request()->is('klien/profile') ? 'active bg-gradient-primary text-white' : 'text-dark' }} d-flex align-items-center rounded-3 px-3 py-2" href="/klien/profile">
          <span class="material-symbols-rounded me-2">person</span>
          <span class="nav-link-text">Profil</span>
        </a>
      </li>

      {{-- BOOKING LIST --}}
      <li class="nav-item mb-1">
        <a class="nav-link {{ request()->is('klien/booking') ? 'active bg-gradient-primary text-white' : 'text-dark' }} d-flex align-items-center rounded-3 px-3 py-2" href="/klien/booking">
          <span class="material-symbols-rounded me-2">event</span>
          <span class="nav-link-text">Booking</span>
        </a>
      </li>

      {{-- PEMBAYARAN --}}
      <li class="nav-item mb-1">
        <a class="nav-link {{ request()->is('klien/pembayaran') ? 'active bg-gradient-primary text-white' : 'text-dark' }} d-flex align-items-center rounded-3 px-3 py-2" href="/klien/pembayaran">
          <span class="material-symbols-rounded me-2">receipt_long</span>
          <span class="nav-link-text">Riwayat</span>
        </a>
      </li>

      {{-- ACCOUNT --}}
      <li class="nav-item mt-4">
        <h6 class="ps-3 text-muted text-uppercase text-xs">Logout</h6>
      </li>

      <li class="nav-item">
        <a href="#"
          class="nav-link d-flex align-items-center rounded-3 px-3 py-2 text-danger"
          onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
          <span class="material-symbols-rounded me-2">logout</span> Log Out
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
          @csrf
        </form>
      </li>

    </ul>
  </div>
</aside>
