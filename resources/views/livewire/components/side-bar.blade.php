@php
    $user = Auth::user();
@endphp

<aside
    class=" sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-end me-4 rotate-caret  sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 ps ps--active-y"
    id="sidenav-main" data-color="primary">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href="{{ route('admin.dashboard') }}">
            {{-- <img src="{{ asset(getSiteLogo()) }}" class="navbar-brand-img h-100" alt="main_logo"> --}}
            <span class="ms-2 h6 font-weight-bold text-uppercase">BDcoder - VMM
            </span>
        </a>
    </div>
    <hr class="horizontal mt-0">
    <div class="collapse navbar-collapse  w-auto h-auto h-100" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            @if (Auth::user()->user_type == 'admin')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('admin/dashboard*') ? 'active' : '' }}"
                        href="{{ route('admin.dashboard') }}">
                        <div
                            class="icon icon-shape icon-sm text-center d-flex align-items-center justify-content-center">
                            <i class="ni ni-shop text-info text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Dashboard</span>
                    </a>
                </li>


                <li class="nav-item">
                    <a class="nav-link {{ Request::is('admin/vmm') ? 'active' : '' }}" href="{{ route('admin.vmm') }}">
                        <div
                            class="icon icon-shape icon-sm text-center d-flex align-items-center justify-content-center">
                            <i class="fas fa-cog text-danger text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Manage VMM</span>
                    </a>
                </li>



                <li class="nav-item">
                    <a class="nav-link {{ Request::is('admin/manage-widthdrawals') ? 'active' : '' }}"
                        href="{{ route('admin.manage-widthdrawals') }}">
                        <div
                            class="icon icon-shape icon-sm text-center d-flex align-items-center justify-content-center">
                            <i class="fas fa-money-bill text-success text-sm opacity-10"></i>
                        </div>

                        <span class="nav-link-text ms-1">Manage Withdrawals</span>
                    </a>
                </li>


                <li class="nav-item">
                    <a class="nav-link {{ Request::is('admin/invests') ? 'active' : '' }}"
                        href="{{ route('admin.invests') }}">
                        <div
                            class="icon icon-shape icon-sm text-center d-flex align-items-center justify-content-center">
                            <i class="fas fa-chart-line text-primary text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Investment Management</span>
                    </a>
                </li>
            @else
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <div
                            class="icon icon-shape icon-sm text-center d-flex align-items-center justify-content-center">
                            <i class="ni ni-shop text-info text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Dashboard</span>
                    </a>
                </li>


                <li class="nav-item">
                    <a class="nav-link {{ Request::is('dashboard/vmm') ? 'active' : '' }}"
                        href="{{ route('dashboard.vmm') }}">
                        <div
                            class="icon icon-shape icon-sm text-center d-flex align-items-center justify-content-center">
                            <i class="fas fa-cog text-danger text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">VMM List</span>
                    </a>
                </li>


                <li class="nav-item">
                    <a class="nav-link {{ Request::is('dashboard/invest-history') ? 'active' : '' }}"
                        href="{{ route('dashboard.invest-history') }}">
                        <div
                            class="icon icon-shape icon-sm text-center d-flex align-items-center justify-content-center">
                            <i class="fas fa-chart-line text-success text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Invest History</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ Request::is('dashboard/withdraw-history') ? 'active' : '' }}"
                        href="{{ route('dashboard.withdraw-history') }}">
                        <div
                            class="icon icon-shape icon-sm text-center d-flex align-items-center justify-content-center">
                            <i class="fas fa-history text-warning text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Withdraw History</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ Request::is('dashboard/withdraw') ? 'active' : '' }}"
                        href="{{ route('dashboard.withdraw') }}">
                        <div
                            class="icon icon-shape icon-sm text-center d-flex align-items-center justify-content-center">
                            <i class="fas fa-wallet text-info text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Withdraw</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ Request::is('dashboard/win-history') ? 'active' : '' }}"
                        href="{{ route('dashboard.win-history') }}">
                        <div
                            class="icon icon-shape icon-sm text-center d-flex align-items-center justify-content-center">
                            <i class="fas fa-trophy text-info text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Winning History</span>
                    </a>
                </li>
            @endif

            <li class="nav-item">
                <a class="nav-link" wire:click.prevent="logout" href="#">
                    <div class="icon icon-shape icon-sm text-center d-flex align-items-center justify-content-center">
                        <i class="ni ni-button-power text-secondary text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Logout</span>
                </a>
            </li>
        </ul>
    </div>
    <hr class="horizontal dark mt-2">
</aside>
