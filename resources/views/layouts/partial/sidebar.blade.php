<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
        <a class="nav-link collapsed" onclick="redirect.dispatch(Redirect.Action.APP, '/');">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link collapsed" onclick="redirect.dispatch(Redirect.Action.APP, '/test-app-bridge');">
          <i class="bi bi-grid"></i>
          <span>Test Page</span>
        </a>
      </li>
      <!-- End Dashboard Nav -->

      {{-- <li class="nav-heading">Pages</li>

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-menu-button-wide"></i><span>Components</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="components-alerts.html">
              <i class="bi bi-circle"></i><span>Alerts</span>
            </a>
          </li>   
        </ul>
      </li> --}}
      <!-- End Components Nav -->
    </ul>

  </aside><!-- End Sidebar-->