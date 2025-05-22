<!doctype html>
<html lang="en" dir="ltr">

    @include('layouts.partials.head')

<body class="app sidebar-mini ltr login-img" style="background: none; overflow: hidden;">

    <!-- Water Animation Background -->
    <div class="water-bg"></div>
    <div class="bubbles-container">
        <div class="bubble"></div>
        <div class="bubble"></div>
        <div class="bubble"></div>
        <div class="bubble"></div>
        <div class="bubble"></div>
    </div>

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
                    <a href="#"><img src="{{isset($settings) ? $settings->logo: ''}}" class="header-brand-img logo-animate" alt=""></a>
                </div>
            </div>

            <div class="container-login100 animated-form">
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
                                    <input class="input100 border-start-0 form-control ms-0 input-animate" type="email" placeholder="Email" name="email">
                                </div>
                                <div class="wrap-input100 validate-input input-group" id="Password-toggle">
                                    <a href="javascript:void(0)" class="input-group-text bg-white text-muted">
                                        <i class="zmdi zmdi-eye text-muted" aria-hidden="true"></i>
                                    </a>
                                    <input class="input100 border-start-0 form-control ms-0 input-animate" type="password" placeholder="Password" name="password">
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
                                    <input type="submit" class="login100-form-btn btn-primary btn-animate" value="Login">
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

    <style>
        :root {
            --orange: #FF6B35;
            --light-blue: #6DD3CE;
            --dark-blue: #1A2930;
            --white: #FFFFFF;
        }

        /* Water Animation */
        .water-bg {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, var(--light-blue), var(--orange));
            z-index: -2;
        }

        .water-bg::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            width: 200%;
            height: 150px;
            background: url('data:image/svg+xml;utf8,<svg viewBox="0 0 1200 120" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none"><path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z" opacity=".25" fill="%23FFFFFF"/><path d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z" opacity=".5" fill="%23FFFFFF"/><path d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z" fill="%23FFFFFF"/></svg>');
            background-repeat: repeat-x;
            animation: wave 15s linear infinite;
            opacity: 0.8;
        }

        @keyframes wave {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }

        /* Bubbles Animation */
        .bubbles-container {
            position: fixed;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
            top: 0;
            left: 0;
        }

        .bubble {
            position: absolute;
            bottom: -100px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            animation: rise 10s infinite ease-in;
        }

        .bubble:nth-child(1) {
            width: 40px;
            height: 40px;
            left: 10%;
            animation-duration: 8s;
        }

        .bubble:nth-child(2) {
            width: 20px;
            height: 20px;
            left: 20%;
            animation-duration: 5s;
            animation-delay: 1s;
        }

        .bubble:nth-child(3) {
            width: 50px;
            height: 50px;
            left: 35%;
            animation-duration: 7s;
            animation-delay: 2s;
        }

        .bubble:nth-child(4) {
            width: 80px;
            height: 80px;
            left: 50%;
            animation-duration: 11s;
            animation-delay: 0s;
        }

        .bubble:nth-child(5) {
            width: 35px;
            height: 35px;
            left: 65%;
            animation-duration: 6s;
            animation-delay: 1s;
        }

        @keyframes rise {
            0% {
                bottom: -100px;
                transform: translateX(0);
            }
            50% {
                transform: translateX(100px);
            }
            100% {
                bottom: 1080px;
                transform: translateX(-200px);
            }
        }

        /* Form Styling */
        .animated-form {
            animation: fadeInUp 0.8s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .wrap-login100 {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            position: relative;
        }

        .login100-form-title {
            color: var(--dark-blue);
            font-weight: 600;
            position: relative;
        }

        .login100-form-title::after {
            content: '';
            position: absolute;
            bottom: 10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: linear-gradient(to right, var(--orange), var(--light-blue));
            border-radius: 3px;
        }

        .logo-animate {
            transition: all 0.3s ease;
            filter: drop-shadow(0 5px 15px rgba(0, 0, 0, 0.1));
        }

        .logo-animate:hover {
            transform: scale(1.05) rotate(-5deg);
            filter: drop-shadow(0 5px 20px rgba(255, 107, 53, 0.4));
        }

        .input-animate {
            transition: all 0.3s ease;
            border: 1px solid #e0e0e0 !important;
        }

        .input-animate:focus {
            border-color: var(--light-blue) !important;
            box-shadow: 0 0 0 3px rgba(109, 211, 206, 0.3) !important;
        }

        .btn-animate {
            transition: all 0.3s ease;
            background: linear-gradient(45deg, var(--orange), #FFA630) !important;
            border: none !important;
            box-shadow: 0 5px 15px rgba(255, 107, 53, 0.4) !important;
        }

        .btn-animate:hover {
            transform: translateY(-3px) !important;
            box-shadow: 0 8px 20px rgba(255, 107, 53, 0.6) !important;
        }

        .form-check-input:checked {
            background-color: var(--orange) !important;
            border-color: var(--orange) !important;
        }

        .text-primary {
            color: var(--orange) !important;
        }
    </style>

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
