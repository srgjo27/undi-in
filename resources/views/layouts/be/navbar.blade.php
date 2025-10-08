<div class="app-menu navbar-menu">
    <div class="navbar-brand-box">
        @if (Auth::user()->role === 'admin')
            <a href="{{ route('admin.dashboard') }}" class="logo logo-dark">
            @else
                <a href="{{ route('seller.dashboard') }}" class="logo logo-dark">
        @endif
        <span class="logo-sm">
            <img src="{{ asset('template/be/dist/default/assets/images/logo-sm.png') }}" alt="" height="22">
        </span>
        <span class="logo-lg">
            <img src="{{ asset('template/be/dist/default/assets/images/logo-dark.png') }}" alt=""
                height="17">
        </span>
        </a>
        @if (Auth::user()->role === 'admin')
            <a href="{{ route('admin.dashboard') }}" class="logo logo-light">
            @else
                <a href="{{ route('seller.dashboard') }}" class="logo logo-light">
        @endif
        <span class="logo-sm">
            <img src="{{ asset('template/be/dist/default/assets/images/logo-sm.png') }}" alt="" height="22">
        </span>
        <span class="logo-lg">
            <img src="{{ asset('template/be/dist/default/assets/images/logo-light.png') }}" alt=""
                height="17">
        </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
            id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>
    <div id="scrollbar">
        <div class="container-fluid">
            <div id="two-column-menu"></div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span data-key="t-menu">Menu</span></li>
                <li class="nav-item">
                    @if (Auth::user()->role === 'admin')
                        <a class="nav-link menu-link" href="{{ route('admin.dashboard') }}">
                            <i class="ri-dashboard-2-line"></i> <span data-key="t-dashboards">Dashboard</span>
                        </a>
                    @else
                        <a class="nav-link menu-link" href="{{ route('seller.dashboard') }}">
                            <i class="ri-dashboard-2-line"></i> <span data-key="t-dashboards">Dashboard</span>
                        </a>
                    @endif
                </li>
                @if (Auth::user()->role === 'admin')
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="{{ route('admin.users.index') }}">
                            <i class="ri-account-circle-line"></i> <span data-key="t-users">User Management</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="{{ route('admin.properties.index') }}">
                            <i class="ri-building-line"></i> <span data-key="t-properties">Properties</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="{{ route('admin.transactions.index') }}">
                            <i class="ri-shopping-cart-line"></i> <span data-key="t-transactions">Transactions</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#sidebarCoupons" data-bs-toggle="collapse"
                            role="button" aria-expanded="false" aria-controls="sidebarCoupons">
                            <i class="ri-gift-line"></i> <span data-key="t-coupons">Coupons & Raffles</span>
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarCoupons">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('admin.coupons.index') }}" class="nav-link"
                                        data-key="t-all-coupons">Coupons</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.coupons.raffles') }}" class="nav-link"
                                        data-key="t-raffles">Raffles</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#sidebarReports" data-bs-toggle="collapse"
                            role="button" aria-expanded="false" aria-controls="sidebarReports">
                            <i class="ri-bar-chart-line"></i> <span data-key="t-reports">Reports</span>
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarReports">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('admin.transactions.report') }}" class="nav-link"
                                        data-key="t-transaction-report">Transaction Report</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.coupons.report') }}" class="nav-link"
                                        data-key="t-coupon-report">Coupon Report</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="{{ route('admin.system.config') }}">
                            <i class="ri-settings-3-line"></i> <span data-key="t-system">System Config</span>
                        </a>
                    </li>
                @endif
                @if (Auth::user()->role === 'seller')
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#sidebarMyProperties" data-bs-toggle="collapse"
                            role="button" aria-expanded="false" aria-controls="sidebarMyProperties">
                            <i class="ri-building-line"></i> <span data-key="t-my-properties">My Properties</span>
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarMyProperties">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('seller.properties.create') }}" class="nav-link"
                                        data-key="t-add-property">Tambah Properti</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('seller.properties.index') }}" class="nav-link"
                                        data-key="t-my-listings">Daftar Properti</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('seller.properties.index', ['status' => 'completed']) }}"
                                        class="nav-link" data-key="t-sold-properties">Properti Terjual</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#sidebarMySales" data-bs-toggle="collapse"
                            role="button" aria-expanded="false" aria-controls="sidebarMySales">
                            <i class="ri-money-dollar-circle-line"></i> <span data-key="t-my-sales">My Sales</span>
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarMySales">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="#" class="nav-link" data-key="t-orders">Orders</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link" data-key="t-earnings">Earnings</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</div>
