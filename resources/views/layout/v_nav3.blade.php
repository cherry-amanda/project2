<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-lg fixed-start bg-white my-2" id="sidenav-main">
  <div class="sidenav-header">
    <a class="navbar-brand px-4 py-3 m-0" href="#">
      <span class="ms-1 text-sm text-dark">Wedding Organizer</span>
    </a>
  </div>
  <hr class="horizontal dark mt-0 mb-2">
  <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link {{ request()->is('user') ? 'active bg-gradient-dark text-white' : 'text-dark' }}" href="/user">
          <i class="material-symbols-rounded opacity-5">dashboard</i>
          <span class="nav-link-text ms-1">Dashboard</span>
        </a>
      </li>
      </li>
      <li class="nav-item">
        <a class="nav-link text-dark" href="/vendor/profile">
          <i class="material-symbols-rounded opacity-5">table_view</i>
          <span class="nav-link-text ms-1">Profile</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-dark" href="/vendor/vendorservice">
          <i class="material-symbols-rounded opacity-5">table_view</i>
          <span class="nav-link-text ms-1">Jasa & Layanan</span>
        </a>
      </li>
    </ul>
  </div>
</aside>
