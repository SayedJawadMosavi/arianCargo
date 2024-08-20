<!DOCTYPE HTML>
<html lang="{{App::getLocale()}}" dir="{{App::getLocale() == 'en' ? 'ltr' : 'rtl'}}">

@include('layouts.partials.head')
<body class="app sidebar-mini ltr light-mode">

    <!-- GLOBAL-LOADER -->
    <!-- <div id="global-loader">
        <img src="{{asset('/back/images/loader.svg')}}" class="loader-img" alt="Loader">
    </div> -->
    <!-- /GLOBAL-LOADER -->

    <!-- PAGE -->
    <div class="page">
        <div class="page-main">
            {{-- @php dd($settings); @endphp --}}

            <!-- app-Header -->
            <div class="app-header header sticky">
                <div class="container-fluid main-container">
                    <div class="d-flex">
                        <a aria-label="Hide Sidebar" class="app-sidebar__toggle" data-bs-toggle="sidebar" href="javascript:void(0)"></a>
                        <!-- sidebar-toggle-->
                        <a class="logo-horizontal " href="{{route('dashboard')}}">
                            <img src="/{{isset($settings) ? $settings->logo : ''}}" class="header-brand-img desktop-logo" alt="logo">
                            <img src="/{{isset($settings) ? $settings->logo : ''}}" class="header-brand-img light-logo1" alt="logo">

                        </a>
                        <!-- LOGO -->
                        {{-- <div class="main-header-center ms-3 d-none d-lg-block">
                            <input type="text" class="form-control" id="typehead" placeholder="Search for results...">
                            <button class="btn px-0 pt-2"><i class="fe fe-search" aria-hidden="true"></i></button>
                        </div> --}}
                        @can('sell.create')
                            <a href="{{ route('sell.create') }}" class="mx-3">
                            <i class="fe fe-shopping-cart"></i> {{ __('home.new_sell') }}
                            </a>
                        @endcan

                        <div class="d-flex order-lg-2 ms-auto header-right-icons">
                            <!-- SEARCH -->
                            <button class="navbar-toggler navresponsive-toggler d-lg-none ms-auto" type="button"
                                data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent-4"
                                aria-controls="navbarSupportedContent-4" aria-expanded="false"
                                aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon fe fe-more-vertical"></span>
                            </button>
                            @include('layouts.partials.header')
                        </div>
                    </div>
                </div>
            </div>
            <!-- /app-Header -->

            <!--APP-SIDEBAR-->
            <div class="sticky">
                <div class="app-sidebar__overlay" data-bs-toggle="sidebar"></div>
                <div class="app-sidebar">
                    <div class="side-header">
                        <a class="header-brand1" href="{{route('dashboard')}}">
                            <img src="/{{isset($settings) ? $settings->logo : ''}}" class="header-brand-img desktop-logo" alt="logo">
                            <img src="/{{isset($settings) ? $settings->logo : ''}}" class="header-brand-img toggle-logo"
                                alt="logo">
                            <img src="/{{isset($settings) ? $settings->logo : ''}}" class="header-brand-img light-logo" alt="logo">
                            <img src="/{{isset($settings) ? $settings->logo : ''}}" class="header-brand-img light-logo1"
                                alt="logo">
                        </a>
                        <!-- LOGO -->
                    </div>

                    @include('layouts.partials.nav')
                </div>
                <!--/APP-SIDEBAR-->
            </div>

            <!--app-content open-->
            <div class="main-content app-content mt-0">
                <div class="side-app">

                    <!-- CONTAINER -->
                    <div class="main-container container-fluid">

                        <!-- PAGE-HEADER -->
                        {{-- <div class="page-header">
                            <h1 class="page-title">Dashboard 01</h1>
                            <div>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Dashboard 01</li>
                                </ol>
                            </div>
                        </div> --}}
                        <!-- PAGE-HEADER END -->

                        <!-- ROW-1 -->
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
                                @yield('content')
                            </div>
                        </div>


                    </div>
                    <!-- CONTAINER END -->
                </div>
            </div>
            <!--app-content close-->

        </div>

        <!-- Sidebar-right -->

        <!-- Country-selector modal-->
        <div class="modal fade" id="country-selector">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content country-select-modal">
                    <div class="modal-header">
                        <h6 class="modal-title">{{ __('home.language') }}</h6><button aria-label="Close" class="btn-close"
                            data-bs-dismiss="modal" type="button"><span aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <ul class="row p-3">
                            <li class="col-lg-6 mb-2">
                                <a href="{{ url('locale/en') }}" onclick="LTRStyle()" class="btn btn-country btn-lg btn-block {{App::getLocale()=='en' ? 'active' : '' }}">
                                    <span class="country-selector"><img alt="" src="/images/flags/us_flag.jpg"
                                            class="me-3 language"></span>{{ __('home.english') }}
                                </a>
                            </li>
                            <li class="col-lg-6 mb-2">
                                <a href="{{url('locale/fa')}}" onclick="RTLStyle()" class="btn btn-country btn-lg btn-block {{App::getLocale()=='fa' ? 'active' : '' }}">
                                    <span class="country-selector"><img alt=""
                                        src="/images/flags/af.svg"
                                        class="me-3 language"></span>{{ __('home.dari') }}
                                </a>
                            </li>
                            <li class="col-lg-6 mb-2">
                                <a href="{{url('locale/pa')}}" onclick="RTLStyle()" class="btn btn-country btn-lg btn-block {{App::getLocale()=='pa' ? 'active' : '' }}">
                                    <span class="country-selector"><img alt=""
                                        src="/images/flags/af.svg"
                                        class="me-3 language"></span>{{ __('home.pashto') }}
                                </a>
                            </li>
                            <input type="hidden" name="lang" id="lang" value="{{App::getLocale()}}">

                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- Country-selector modal-->


        <!-- FOOTER -->
        @include('layouts.partials.footer')
        <!-- FOOTER END -->

        @hasSection('pagescript')
            @yield('pagescript')
        @endif

        <script>
            $('#dates, #dates1').persianDatepicker({
                // minDate: new persianDate().subtract('day', 1).valueOf(),
                maxDate: new persianDate(),
                format: 'YYYY-MM-DD',
                autoClose: true,
                initialValue: true,
                initialValueType: 'persian',
                calendar: {
                    persian: {
                        locale: 'en'
                    }
                }
            });
        </script>

</body>

</html>
