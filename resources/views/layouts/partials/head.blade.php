<head>

    <!-- META DATA -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="ANHCO , ANHCO">
    @hasSection('csrf')
        @yield('csrf')
    @endif
	<meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="author" content="Nethub IT Solutions">
    <meta name="keywords" content="admin,admin dashboard,admin panel,admin template,bootstrap,clean,dashboard,flat,jquery,modern,responsive,premium admin templates,responsive admin,ui,ui kit.">
    <!-- FAVICON -->
    <link rel="shortcut icon" type="image/x-icon" href="{{isset($settings) ? $settings->logo: ''}} " />
    <!-- TITLE -->
    <title>@yield('title') | {{isset($settings) ? $settings->name_en : 'Retail MIS'}}</title>

    <!-- BOOTSTRAP CSS -->
    <link id="style" href="{{asset('/back/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" />
    <script src="{{ asset('js/chart.min.js') }}"></script>

    <!-- STYLE CSS -->
    <link href="{{asset('/back/css/style.min.css')}}" rel="stylesheet" />
    <link href="{{asset('/back/css/dark-style.css')}}" rel="stylesheet" />
    <link href="{{asset('/back/css/transparent-style.css')}}" rel="stylesheet">
    <link href="{{asset('/back/css/skin-modes.css')}}" rel="stylesheet" />
    <link href="{{asset('/back/css/select2.min.css')}}" rel="stylesheet" />
    <link href="{{asset('/back/iconfonts/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" />
    <!--- FONT-ICONS CSS -->
	<link href="{{asset('/css/toastr.min.css')}}" rel="stylesheet" />

    <link href="{{asset('/back/css/icons.css')}}" rel="stylesheet" />
    <link href="{{asset('/back/css/custom.css')}}" rel="stylesheet" />
	<link rel="stylesheet" href="{{asset('/js/datatable/css/buttons.dataTables.min.css')}}" />
	<link rel="stylesheet" href="{{asset('/js/datatable/css/jquery.dataTables.min.css')}}" />
	<link rel="stylesheet" href="{{asset('/js/datatable/css/responsive.dataTables.min.css')}}" />
	<link rel="stylesheet" href="{{asset('/jalali/style/persian-datepicker.css')}}">
    <!-- COLOR SKIN CSS -->
    <link rel="stylesheet" type="text/css" href="{{asset('/sweetalert2/sweetalert2.min.css')}}">
    {{-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.9/css/buttons.dataTables.min.css"> --}}

    <link id="theme" rel="stylesheet" type="text/css" media="all" href="{{asset('/back/colors/color1.css')}}" />
    @yield('style')
    @stack('style')
</head>
