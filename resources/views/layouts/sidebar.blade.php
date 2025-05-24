<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">

    <!-- LOGO -->
    <div class="navbar-brand-box">
        <a href="{{ url('index') }}" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ URL::asset('/assets/images/paud.png') }}" alt="" height="28">
            </span>
            <span class="logo-lg">
                <img src="{{ URL::asset('/assets/images/paud.png') }}" alt="" height="24">
                <span class="text-dark fw-bold ms-2 text-uppercase fs-5">RA nurul amin</span>
            </span>
        </a>

        <a href="{{ url('index') }}" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ URL::asset('/assets/images/logo-sm.png') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ URL::asset('/assets/images/logo-light.png') }}" alt="" height="20">
            </span>
        </a>
    </div>

    <button type="button" class="btn btn-sm px-3 font-size-16 header-item waves-effect vertical-menu-btn">
        <i class="fa fa-fw fa-bars"></i>
    </button>

    <div data-simplebar class="sidebar-menu-scroll">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title">@lang('translation.Menu')</li>

                <li>
                    <a href="{{ route('dashboard') }}" class="waves-effect">
                        <i class="uil-home-alt"></i><span class="badge rounded-pill bg-primary float-end">01</span>
                        <span>@lang('translation.Dashboard')</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('input-nilai') }}" class="waves-effect">
                        <i class="uil-edit"></i>
                        <span>Input Nilai</span>
                    </a>
                </li>

                <li class="menu-title">@lang('translation.Apps')</li>
                <li>
                    <a href="{{ route('daftar-nilai') }}" class=" waves-effect">
                        <i class="uil-list-ul"></i>
                        <span>Daftar Nilai</span>
                    </a>
                </li>

                {{-- Kelola Anak --}}
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="uil-users-alt"></i>
                        <span>Kelola Anak</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('daftar-anak') }}">Daftar Anak</a></li>
                        <li><a href="ecommerce-product-detail">Daftar Orang Tua</a></li>
                    </ul>
                </li>
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->
