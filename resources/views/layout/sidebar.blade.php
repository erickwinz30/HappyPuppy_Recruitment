<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">
  <ul class="sidebar-nav" id="sidebar-nav">
    <li class="nav-item">
      {{-- {{ dd(Request::route()) }} --}}
      <a class="nav-link {{ Request::is('stock') ? '' : 'collapsed' }}" href="/stock">
        <i class="bi bi-basket3"></i>
        <span>Stock</span>
      </a>
    </li><!-- End Dashboard Nav -->
    <li class="nav-item">
      {{-- {{ dd(Request::route()) }} --}}
      <a class="nav-link {{ Request::is('request-approval') ? '' : 'collapsed' }}" href="/request-approval">
        <i class="bi bi-database-exclamation"></i>
        <span>Req. Approval</span>
      </a>
    </li><!-- End Dashboard Nav -->

    @if (auth()->user()->is_admin)
      <li class="nav-heading">Admin</li>

      <li class="nav-item">
        {{-- {{ dd(Request::route()) }} --}}
        <a class="nav-link {{ Request::is('/') ? '' : 'collapsed' }}" href="/">
          <i class="bi bi-speedometer"></i>
          <span>Dashboard</span>
        </a>
      </li><!-- End Dashboard Nav -->

      <li class="nav-item">
        {{-- {{ dd(Request::route()) }} --}}
        <a class="nav-link {{ Request::is('users') ? '' : 'collapsed' }}" href="/users">
          <i class="bi bi-person-gear"></i>
          <span>User</span>
        </a>
      </li><!-- End Dashboard Nav -->
    @endif



    {{-- <li class="nav-item">
            <a class="nav-link {{ Request::is('dashboard/transaksi') || Request::is('dashboard/transaksi/*') ? '' : 'collapsed' }}"
                href="/dashboard/transaksi">
                <i class="bi bi-wallet2"></i>
                <span>Transaksi</span>
            </a>
        </li><!-- End Transaksi Page Nav -->

        <li class="nav-item">
            <a class="nav-link {{ Request::is('dashboard/layanan') || Request::is('dashboard/layanan/*') || Request::is('dashboard/layananLog') ? '' : 'collapsed' }}"
                href="/dashboard/layanan">
                <i class="bi bi-collection"></i>
                <span>Layanan</span>
            </a>
        </li><!-- End Layanan Page Nav -->

        <li class="nav-item">
            <a class="nav-link {{ Request::is('dashboard/pelanggan') || Request::is('dashboard/pelanggan/*') ? '' : 'collapsed' }}"
                href="/dashboard/pelanggan">
                <i class="bi bi-person"></i>
                <span>Pelanggan</span>
            </a>
        </li><!-- End Kasir Page Nav -->

        <li class="nav-item">
            <a class="nav-link {{ Request::is('dashboard/voucher') || Request::is('dashboard/voucher/*') || Request::is('dashboard/challenge') || Request::is('dashboard/challenge/*') || Request::is('dashboard/badge') || Request::is('dashboard/badge/*') ? '' : 'collapsed' }}"
                data-bs-target="#tables-gamification" data-bs-toggle="collapse" href="#">
                <i class="bi bi-controller"></i><span>Gamifikasi</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>

            <ul id="tables-gamification"
                class="nav-content collapse {{ Request::is('dashboard/voucher') || Request::is('dashboard/voucher/*') || Request::is('dashboard/challenge') || Request::is('dashboard/challenge/*') || Request::is('dashboard/badge') || Request::is('dashboard/badge/*') ? 'show' : '' }}"
                data-bs-parent="#sidebar-nav">

                <li>
                    <a class="{{ Request::is('dashboard/voucher') || Request::is('dashboard/voucher/*') ? 'active' : '' }}"
                        href="/dashboard/voucher">
                        <i class="bi bi-circle"></i>
                        <span>Voucher</span>
                    </a>
                </li><!-- End Voucher Page Nav -->

                <li>
                    <a class="{{ Request::is('dashboard/challenge') || Request::is('dashboard/challenge/*') ? 'active' : '' }}"
                        href="/dashboard/challenge">
                        <i class="bi bi-circle"></i>
                        <span>Challenge</span>
                    </a>
                </li><!-- End Challenge Page Nav -->

                <li>
                    <a class="{{ Request::is('dashboard/badge') || Request::is('dashboard/badge/*') ? 'active' : '' }}"
                        href="/dashboard/badge">
                        <i class="bi bi-circle"></i>
                        <span>Badge</span>
                    </a>
                </li><!-- End Badge Page Nav -->
            </ul>
        </li><!-- End Tables Nav -->

        <li class="nav-item">
            <a class="nav-link {{ Request::is('dashboard/kasir') || Request::is('dashboard/kasir/*') || Request::is('dashboard/feedback') || Request::is('dashboard/feedback/*') ? '' : 'collapsed' }}"
                data-bs-target="#tables-more" data-bs-toggle="collapse" href="#">
                <i class="bi bi-three-dots"></i><span>Lainnya</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>

            <ul id="tables-more"
                class="nav-content collapse {{ Request::is('dashboard/kasir') || Request::is('dashboard/kasir/*') || Request::is('dashboard/feedback') || Request::is('dashboard/feedback/*') ? 'show' : '' }}"
                data-bs-parent="#sidebar-nav">

                <li>
                    <a class="nav-link {{ Request::is('dashboard/kasir') || Request::is('dashboard/kasir/*') ? 'active' : '' }}"
                        href="/dashboard/kasir">
                        <i class="bi bi-circle"></i>
                        <span>Kasir</span>
                    </a>
                </li><!-- End Kasir Page Nav -->

                <li>
                    <a class="{{ Request::is('dashboard/feedback') || Request::is('dashboard/feedback/*') ? 'active' : '' }}"
                        href="/dashboard/feedback">
                        <i class="bi bi-circle"></i>
                        <span>Feedback</span>
                    </a>
                </li><!-- End Feedback Page Nav -->
            </ul>
        </li><!-- End Tables Nav --> --}}
  </ul>

</aside><!-- End Sidebar-->
