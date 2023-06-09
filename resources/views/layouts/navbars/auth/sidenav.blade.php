<aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 "
    id="sidenav-main">
    @php
        $role = Auth::user()->role;
    @endphp
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0"
            target="_blank">
            <img src="./img/logo-ct-dark.png" class="navbar-brand-img h-100" alt="main_logo">
            <span class="ms-1 font-weight-bold">Toko Komputer</span>
        </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto h-auto " id="sidenav-collapse-main">
        <ul class="navbar-nav">
{{--            ---------------------------------------------------------------------------------------------------}}
{{--            <hr class="horizontal dark">--}}
{{--            ---------------------------------------------------------------------------------------------------}}
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
            @if($role == 0)
            <li class="nav-item">
                <a class="nav-link {{ str_contains(request()->url(), 'user_management') == true ? 'active' : '' }}" href="{{ route('user_management') }}">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-bullet-list-67 text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">User Management</span>
                </a>
            </li>
            @endif
            {{-----------------------------------------------------------------------------------------------------}}
            <hr class="horizontal dark">
            {{-----------------------------------------------------------------------------------------------------}}

            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Tansaction</h6>
            </li>

{{--            // Disable untuk Super Admin, memiliki tombol sendiri didalam laporan transaksi--}}
            @if($role == 1)
            <li class="nav-item">
                <a class="nav-link {{ str_contains(request()->url(), 'item-view') == true ? 'active' : '' }}" href="{{ route('transaksi-register') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-calendar-grid-58 text-warning text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Masukan Transaksi</span>
                </a>
            </li>
            @endif
            @if($role == 0)
            <li class="nav-item">
                <a class="nav-link {{ str_contains(request()->url(), 'item-view') == true ? 'active' : '' }}" href="{{ route('item-view') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-calendar-grid-58 text-warning text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Manajemen Barang</span>
                </a>
            </li>
            @endif
            @if($role == 0)
            <li class="nav-item">
                    <a class="nav-link {{  str_contains(request()->url(), 'transaksi-view') == true ? 'active' : '' }}" href="{{ route('transaksi-view') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-credit-card text-success text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Laporan Transaksi</span>
                </a>
            </li>
            @endif
            {{-----------------------------------------------------------------------------------------------------}}
{{--            <hr class="horizontal dark">--}}
            {{-----------------------------------------------------------------------------------------------------}}

{{--            <li class="nav-item mt-3">--}}
{{--                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">About</h6>--}}
{{--            </li>--}}

{{--            <li class="nav-item">--}}
{{--                <a class="nav-link {{  str_contains(request()->url(), 'credits') == true ? 'active' : '' }}" href="{{ route('credits') }}">--}}
{{--                    <div--}}
{{--                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">--}}
{{--                        <i class="ni ni-support-16 text-success text-sm opacity-10"></i>--}}
{{--                    </div>--}}
{{--                    <span class="nav-link-text ms-1">About</span>--}}
{{--                </a>--}}
{{--            </li>--}}
        </ul>
    </div>
</aside>
