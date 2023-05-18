<aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 "
    id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href="{{ route('home') }}"
            target="_blank">
            <img src="./img/logo-ct-dark.png" class="navbar-brand-img h-100" alt="main_logo">
            <span class="ms-1 font-weight-bold">Laravel Dashboard</span>
        </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link {{ Route::currentRouteName() == 'home' ? 'active' : '' }}" href="{{ route('home') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-tv-2 text-primary text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>

            {{-----------------------------------------------------------------------------------------------------}}
            <hr class="horizontal dark">
            {{-----------------------------------------------------------------------------------------------------}}
            <li class="nav-item mt-3 d-flex align-items-center">
                <div class="ps-4">
                    <i class="ni ni-badge" style="color: #f4645f;"></i>
                </div>
                <h6 class="ms-2 text-uppercase text-xs font-weight-bolder opacity-6 mb-0">Account Pages</h6>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::currentRouteName() == 'profile' ? 'active' : '' }}" href="{{ route('profile') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-single-02 text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Profile</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ str_contains(request()->url(), 'user_management') == true ? 'active' : '' }}" href="{{ route('user_management') }}">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-bullet-list-67 text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">User Management</span>
                </a>
            </li>

            {{-----------------------------------------------------------------------------------------------------}}
            <hr class="horizontal dark">
            {{-----------------------------------------------------------------------------------------------------}}

            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Pages</h6>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ str_contains(request()->url(), 'item-view') == true ? 'active' : '' }}" href="{{ route('page', ['page' => 'item-view']) }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-calendar-grid-58 text-warning text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Item Management</span>
                </a>
            </li>
            <li class="nav-item">
{{--                <a class="nav-link {{  str_contains(request()->url(), 'transaksiview') == true ? 'active' : '' }}" href="{{ route('page', ['page' => 'transaksi-view']) }}">--}}
                    <a class="nav-link {{  str_contains(request()->url(), 'transaksi-view') == true ? 'active' : '' }}" href="transaksi-view">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-credit-card text-success text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Transaksi</span>
                </a>
            </li>



            {{-----------------------------------------------------------------------------------------------------}}
            <hr class="horizontal dark">
            {{-----------------------------------------------------------------------------------------------------}}

            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">About</h6>
            </li>

            <li class="nav-item">
                <a class="nav-link {{  str_contains(request()->url(), 'credits') == true ? 'active' : '' }}" href="{{ route('page', ['page' => 'credits']) }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-support-16 text-success text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">About</span>
                </a>
            </li>
        </ul>
    </div>
</aside>
