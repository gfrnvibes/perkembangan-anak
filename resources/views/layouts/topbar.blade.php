<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <a href="{{ url('index') }}" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="{{ URL::asset('/assets/images/logo-sm.png') }}" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ URL::asset('/assets/images/logo-dark.png') }}" alt="" height="20">
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
        </div>

        <div class="d-flex">

            <div class="dropdown d-none d-lg-inline-block ms-1">
                <button type="button" class="btn header-item noti-icon waves-effect" data-bs-toggle="fullscreen">
                    <i class="uil-minus-path"></i>
                </button>
            </div>

            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item noti-icon waves-effect"
                    id="page-header-notifications-dropdown" data-bs-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                    <i class="uil-bell"></i>
                    <span class="badge bg-danger rounded-pill">3</span>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                    aria-labelledby="page-header-notifications-dropdown">
                    <div class="p-3">
                        <div class="row align-items-center">
                            <div class="col">
                                <h5 class="m-0 font-size-16"> @lang('translation.Notifications') </h5>
                            </div>
                            <div class="col-auto">
                                <a href="#!" class="small"> @lang('translation.Mark_read')</a>
                            </div>
                        </div>
                    </div>
                    <div data-simplebar style="max-height: 230px;">
                        <a href="" class="text-dark notification-item">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar-xs">
                                        <span class="avatar-title bg-primary rounded-circle font-size-16">
                                            <i class="uil-shopping-basket"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mt-0 mb-1">@lang('translation.order_placed')</h6>
                                    <div class="font-size-12 text-muted">
                                        <p class="mb-1">@lang('translation.languages_grammar')</p>
                                        <p class="mb-0"><i class="mdi mdi-clock-outline"></i> @lang('translation.3_min_ago')</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a href="" class="text-dark notification-item">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0 me-3">
                                    <img src="{{ URL::asset('/assets/images/users/avatar-3.jpg') }}"
                                        class="rounded-circle avatar-xs" alt="user-pic">
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mt-0 mb-1">James Lemire</h6>
                                    <div class="font-size-12 text-muted">
                                        <p class="mb-1">@lang('translation.simplified_English')</p>
                                        <p class="mb-0"><i class="mdi mdi-clock-outline"></i> @lang('translation.1_hours_ago')</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a href="" class="text-dark notification-item">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar-xs">
                                        <span class="avatar-title bg-success rounded-circle font-size-16">
                                            <i class="uil-truck"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mt-0 mb-1">@lang('translation.item_shipped')</h6>
                                    <div class="font-size-12 text-muted">
                                        <p class="mb-1">@lang('translation.languages_grammar')</p>
                                        <p class="mb-0"><i class="mdi mdi-clock-outline"></i> @lang('translation.3_min_ago')</p>
                                    </div>
                                </div>
                            </div>
                        </a>

                        <a href="" class="text-dark notification-item">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0 me-3">
                                    <img src="{{ URL::asset('/assets/images/users/avatar-4.jpg') }}"
                                        class="rounded-circle avatar-xs" alt="user-pic">
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mt-0 mb-1">Salena Layfield</h6>
                                    <div class="font-size-12 text-muted">
                                        <p class="mb-1">@lang('translation.friend_occidental')</p>
                                        <p class="mb-0"><i class="mdi mdi-clock-outline"></i> @lang('translation.1_hours_ago')</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="p-2 border-top">
                        <div class="d-grid">
                            <a class="btn btn-sm btn-link font-size-14 text-center" href="javascript:void(0)">
                                <i class="uil-arrow-circle-right me-1"></i> @lang('translation.View_More')..
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="rounded-circle header-profile-user"
                        src="{{ URL::asset('/assets/images/users/avatar-4.jpg') }}" alt="Header Avatar">
                    <span class="d-none d-xl-inline-block ms-1 fw-medium font-size-15">Admin</span>
                    <i class="uil-angle-down d-none d-xl-inline-block font-size-15"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                    <!-- item-->
                    <a class="dropdown-item" href="{{ route('logout') }}">
                        <i class="uil uil-sign-out-alt font-size-18 align-middle me-1 text-muted"></i> 
                            <span class="align-middle">@lang('translation.Sign_out')</span>
                    </a>
                </div>
            </div>



        </div>
    </div>
</header>
<a class="dropdown-item" href="#"><i class="uil uil-wallet font-size-18 align-middle me-1 text-muted"></i>
    <span class="align-middle">@lang('translation.My_Wallet')</span></a>
