<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-lg fixed-start bg-white my-2 shadow-sm" id="sidenav-main">
  <div class="d-flex align-items-center mb-3 ps-2">
    <span style="font-size: 28px; color:#6C63FF; font-weight: bold;">∞</span>
    <span class="ms-2 fw-semibold text-dark" style="font-size: 18px;">Infinity Wedding</span>
  </div>

  <hr class="horizontal dark mt-0 mb-2">

  <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
    <ul class="navbar-nav px-2">

      {{-- DASHBOARD --}}
      <li class="nav-item mb-1">
        <a class="nav-link d-flex align-items-center rounded-3 px-3 py-2 {{ request()->is('vendor/dashboard') ? 'bg-gradient-primary text-white' : 'text-dark' }}" href="/vendor/dashboard">
          <i class="material-symbols-rounded me-2">dashboard</i>
          <span class="nav-link-text">Dashboard</span>
        </a>
      </li>

      {{-- PROFILE --}}
      <li class="nav-item mb-1">
        <a class="nav-link d-flex align-items-center rounded-3 px-3 py-2 {{ request()->is('vendor/profile') ? 'bg-gradient-primary text-white' : 'text-dark' }}" href="/vendor/profile">
          <i class="material-symbols-rounded me-2">person</i>
          <span class="nav-link-text">Profil Saya</span>
        </a>
      </li>

      {{-- SERVICES --}}
      <li class="nav-item mb-1">
        <a class="nav-link d-flex align-items-center rounded-3 px-3 py-2 {{ request()->is('vendor/vendorservice') ? 'bg-gradient-primary text-white' : 'text-dark' }}" href="/vendor/vendorservice">
          <i class="material-symbols-rounded me-2">build</i>
          <span class="nav-link-text">Jasa & Layanan</span>
        </a>
      </li>

      

      {{-- SECTION AKUN --}}
      <li class="nav-item mt-4 mb-2">
        <h6 class="ps-3 text-muted text-uppercase text-xs fw-bold">Akun</h6>
      </li>

      {{-- LOGOUT --}}
      <li class="nav-item">
        <a class="nav-link d-flex align-items-center rounded-3 px-3 py-2 {{ request()->is('logout') ? 'bg-gradient-danger text-white' : 'text-dark' }}" href="/logout">
          <i class="material-symbols-rounded me-2">logout</i>
          <span class="nav-link-text">Keluar</span>
        </a>
      </li>

    </ul>
  </div>
</aside>
