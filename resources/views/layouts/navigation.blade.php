<aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 " id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href="{{route('dashboard')}}">
            <img src="{{asset('images/logo.png')}}" class="navbar-brand-img h-100" alt="main_logo">
            <span class="ms-1 font-weight-bold">Exchange Calculator</span>
        </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link {{ Route::is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-tv-2 text-primary text-sm opacity-10 {{ Route::is('dashboard') ? 'active-i' : '' }}"></i>
                    </div>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ Route::is('profile.edit') ? 'active' : '' }}" href="{{route('profile.edit')}}">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-single-02 text-primary text-sm opacity-10 {{ Route::is('profile.edit') ? 'active-i' : '' }}"></i>
                    </div>
                    <span class="nav-link-text ms-1">Profile</span>
                </a>
            </li>
            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Buy - Sell</h6>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::is('buysell.index') ? 'active' : '' }}" href="{{ route('buysell.index') }}">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-calendar-grid-58 text-warning text-sm opacity-10 {{ Route::is('buysell.index') ? 'active-i' : '' }}"></i>
                    </div>
                    <span class="nav-link-text ms-1">Buy and Sell</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::is('sellbuy.index') ? 'active' : '' }}" href="{{ route('sellbuy.index') }}">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-calendar-grid-58 text-warning text-sm opacity-10 {{ Route::is('sellbuy.index') ? 'active-i' : '' }}"></i>
                    </div>
                    <span class="nav-link-text ms-1">Sell and Buy</span>
                </a>
            </li>
        </ul>
    </div>

</aside>