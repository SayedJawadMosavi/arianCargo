<!doctype html>
<html lang="en" dir="ltr">

    @include('layouts.partials.head')

<body class="app sidebar-mini ltr login-img" style="background-image: url(front/images/bg.jpg);">

    <!-- BACKGROUND-IMAGE -->
    <div class="">

        <!-- GLOABAL LOADER -->
        <div id="global-loader">
            <img src="{{asset('/back/images/loader.svg')}}" class="loader-img" alt="Loader">
        </div>
        <!-- /GLOABAL LOADER -->

        <!-- PAGE -->
        <div class="page">
            <div class="">

                <!-- CONTAINER OPEN -->
                <div class="col col-login mx-auto mt-7">
                    <div class="text-center">
                        <a href="#"><img src="{{isset($settings) ? $settings->logo: ''}}" class="header-brand-img" alt=""></a>
                    </div>
                </div>

                <div class="container-login100">
                    <div class="wrap-login100 p-6">
                        @if (session()->has('success') || session()->has('error') )
                            @include('layouts.partials.components.alert')
                        @endif
                        <form class="login100-form validate-form" action="{{route('login')}}" method="POST">
                            <span class="login100-form-title pb-5">
                                Login
                            </span>
                            @csrf
                            <div class="panel panel-primary">

                                <div class="tab-pane active" id="tab5">
                                    <div class="wrap-input100 validate-input input-group" data-bs-validate="Valid email is required: ex@abc.xyz">
                                        <a href="javascript:void(0)" class="input-group-text bg-white text-muted">
                                            <i class="zmdi zmdi-email text-muted" aria-hidden="true"></i>
                                        </a>
                                        <input class="input100 border-start-0 form-control ms-0" type="email" placeholder="Email" name="email">
                                    </div>
                                    <div class="wrap-input100 validate-input input-group" id="Password-toggle">
                                        <a href="javascript:void(0)" class="input-group-text bg-white text-muted">
                                            <i class="zmdi zmdi-eye text-muted" aria-hidden="true"></i>
                                        </a>
                                        <input class="input100 border-start-0 form-control ms-0" type="password" placeholder="Password" name="password">
                                    </div>
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="invalidCheck" name="remember">
                                            <label class="form-check-label" for="invalidCheck">Remember Me!</label>
                                        </div>
                                    </div>
                                    <div class="text-end pt-4 d-none">
                                        <p class="mb-0"><a href="forgot-password.html" class="text-primary ms-1">Forgot Password?</a></p>
                                    </div>
                                    <div class="container-login100-form-btn">
                                        <input type="submit" class="login100-form-btn btn-primary" value="Login">
                                    </div>

                                </div>


                            </div>

                        </form>
                    </div>
                </div>
                <!-- CONTAINER CLOSED -->
            </div>
        </div>
        <!-- End PAGE -->

    </div>
    <!-- BACKGROUND-IMAGE CLOSED -->

    <!-- JQUERY JS -->
    <script src="{{asset('/back/js/jquery.min.js')}}"></script>

    <!-- BOOTSTRAP JS -->
    <script src="{{asset('/back/plugins/bootstrap/js/popper.min.js')}}"></script>
    <script src="{{asset('/back/plugins/bootstrap/js/bootstrap.min.js')}}"></script>

    <!-- SHOW PASSWORD JS -->
    <script src="{{asset('/back/js/show-password.min.js')}}"></script>

    <!-- GENERATE OTP JS -->
    <script src="{{asset('/back/js/generate-otp.js')}}"></script>

    <!-- Perfect SCROLLBAR JS-->
    <script src="{{asset('/back/plugins/p-scroll/perfect-scrollbar.js')}}"></script>

    <!-- Color Theme js -->
    <script src="{{asset('/back/js/themeColors.js')}}"></script>

    <!-- CUSTOM JS -->
    <script src="{{asset('/back/js/custom.js')}}"></script>


</body>

</html>
