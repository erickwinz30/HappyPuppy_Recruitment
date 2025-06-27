<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">
  <ul class="sidebar-nav" id="sidebar-nav">
    <li class="nav-heading">Admin</li>

    <li class="nav-item">
      {{-- {{ dd(Request::route()) }} --}}
      <a class="nav-link {{ Request::is('/') || Request::is('products') ? '' : 'collapsed' }}" href="/">
        <i class="bi bi-basket3"></i>
        <span>Products</span>
      </a>
    </li><!-- End Dashboard Nav -->
  </ul>

</aside><!-- End Sidebar-->
