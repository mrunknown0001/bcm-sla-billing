      <div class="app-sidebar sidebar-shadow">
        <div class="app-header__logo">
          <div class="logo-src"></div>
          <div class="header__pane ml-auto">
            <div>
              <button type="button" class="hamburger close-sidebar-btn hamburger--elastic" data-class="closed-sidebar">
                <span class="hamburger-box">
                  <span class="hamburger-inner"></span>
                </span>
              </button>
            </div>
          </div>
        </div>
        <div class="app-header__mobile-menu">
          <div>
            <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
              <span class="hamburger-box">
                <span class="hamburger-inner"></span>
              </span>
            </button>
          </div>
        </div>
        <div class="app-header__menu">
          <span>
            <button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
              <span class="btn-icon-wrapper">
                <i class="fa fa-ellipsis-v fa-w-6"></i>
              </span>
            </button>
          </span>
        </div>
        <div class="scrollbar-sidebar">
          <div class="app-sidebar__inner">
            <ul class="vertical-nav-menu">
              <li class="app-sidebar__heading">Howdy, {{ strtoupper(Auth::user()->first_name) }}!</li>
              <li>
                <a href="{{ route('admin.dashboard') }}" class="{{ route('admin.dashboard') == url()->current() ? 'mm-active' : '' }}">
                  <i class="metismenu-icon pe-7s-graph3"></i>
                    Dashboard
                </a>
              </li>

              <li>
                <a href="{{ route('admin.users') }}" class="{{ route('admin.users') == url()->current() ? 'mm-active' : '' }}">
                  <i class="metismenu-icon pe-7s-user"></i>
                    Users
                </a>
              </li>

              <li>
                <a href="{{ route('admin.wro.setup') }}" class="{{ route('admin.wro.setup') == url()->current() ? 'mm-active' : '' }}">
                  <i class="metismenu-icon pe-7s-users"></i>
                    Approval Setup
                </a>
              </li>

              <li>
                <a href="{{ route('admin.farms') }}" class="{{ route('admin.farms') == url()->current() ? 'mm-active' : '' }}">
                  <i class="metismenu-icon pe-7s-culture"></i>
                    Farm Management
                </a>
              </li>

              <li>
                <a href="{{ route('admin.departments') }}" class="{{ route('admin.departments') == url()->current() ? 'mm-active' : '' }}">
                  <i class="metismenu-icon pe-7s-door-lock"></i>
                    Department Management
                </a>
              </li>

              <li>
                <a href="{{ route('admin.password.retention') }}" class="{{ route('admin.password.retention') == url()->current() ? 'mm-active' : '' }}">
                  <i class="metismenu-icon pe-7s-key"></i>
                    Password Retention
                </a>
              </li>

              <li class="{{ route('admin.db.backup') == url()->current() ? 'mm-active' : '' }}">
                <a href="javascript:void(0)">
                    <i class="metismenu-icon pe-7s-server"></i>
                    DB
                    <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                </a>
                <ul class="{{ route('admin.db.backup') == url()->current() || route('admin.db.restore') == url()->current() ? 'mm-show mm-collapse' : '' }}">
                  <li>
                    <a href="{{ route('admin.db.backup') }}" class="{{ route('admin.db.backup') == url()->current() ? 'mm-active' : '' }}">
                      <i class="metismenu-icon"></i>
                        Backup
                    </a>
                  </li>
                  {{-- <li>
                    <a href="{{ route('admin.db.restore') }}" class="{{ route('admin.db.restore') == url()->current() ? 'mm-active' : '' }}">
                      <i class="metismenu-icon">
                        </i>Restore
                    </a>
                  </li> --}}
                </ul>
              </li>
              <li>
                <a href="{{ route('admin.module') }}" class="{{ route('admin.module') == url()->current() ? 'mm-active' : '' }}">
                  <i class="metismenu-icon pe-7s-plugin"></i>
                    Modules
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>