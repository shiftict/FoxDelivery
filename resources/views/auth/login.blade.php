<!DOCTYPE html>
<html lang="{{app()->getLocale()}}" @if(app()->getLocale() == 'ar')direction="rtl" style="direction: rtl;"@else direction="ltr" style="direction: ltr;" @endif>
<!--begin::Head-->
<head><base href="../../../../">
    <meta charset="utf-8" />
    <title>@lang('auth.button')</title>
    <meta name="description" content="Login page example" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="canonical" href="https://keenthemes.com/metronic" />
    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <!--end::Fonts-->
    <!--begin::Page Custom Styles(used by this page)-->
    <link href="{{asset('panel/assets/css/pages/login/classic/login-4.css')}}" rel="stylesheet" type="text/css" />
    <!--end::Page Custom Styles-->
    <!--begin::Global Theme Styles(used by all pages)-->
    <link href="{{asset('panel/assets/plugins/global/plugins.bundle.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('panel/assets/plugins/custom/prismjs/prismjs.bundle.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('panel/assets/css/style.bundle.css')}}" rel="stylesheet" type="text/css" />
    <!--end::Global Theme Styles-->
    <!--begin::Layout Themes(used by all pages)-->
    <link href="{{asset('panel/assets/css/themes/layout/header/base/light.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('panel/assets/css/themes/layout/header/menu/light.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('panel/assets/css/themes/layout/brand/dark.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('panel/assets/css/themes/layout/aside/dark.css')}}" rel="stylesheet" type="text/css" />
    <!--end::Layout Themes-->
    <link rel="shortcut icon" href="{{asset('panel/assets/media/logos/favicon.ico')}}" />
    @toastr_css
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Cairo', sans-serif;
        }
        label#email-error {
            color: #f00;
        }
        label#password-error {
            color: #f00;
        }
    </style>
</head>
<!--end::Head-->
<!--begin::Body-->
<body id="kt_body" class="header-fixed header-mobile-fixed subheader-enabled subheader-fixed aside-enabled aside-fixed aside-minimize-hoverable page-loading">
<!--begin::Main-->
<div class="hidden fixed top-0 right-0 px-6 py-4 sm:block" style="background: #fff;">
    @if(app()->getLocale() == 'ar')
        <a href="{{Route('change.language','en')}}" class="text-sm">English</a>
    @else
        <a href="{{Route('change.language','ar')}}" class="text-sm">عربي</a>
    @endif
</div>
<div class="d-flex flex-column flex-root">
    <!--begin::Login-->
    <div class="login login-4 login-signin-on d-flex flex-row-fluid" id="kt_login">
        <div class="d-flex flex-center flex-row-fluid bgi-size-cover bgi-position-top bgi-no-repeat" style="background-image: url('fox/public/panel/assets/media/bg/bg-3.jpg');">
            <div class="login-form text-center p-7 position-relative overflow-hidden">
                <!--begin::Login Header-->
                <div class="d-flex flex-center mb-15">
                    <a href="{{ Route('login') }}">
                        <!--<img src="{{asset('image/logo/fox_logo.jpg')}}" class="max-h-75px" alt="" />-->
                        <svg width="110" height="100" viewBox="0 0 155 148" fill="none" xmlns="http://www.w3.org/2000/svg">
<g clip-path="url(#clip0_75_3111)">
<path d="M72.9258 22.7303V0L55.0861 8.36753L38.1448 0L38.0903 25.1162L54.9908 42.8777C58.6731 35.251 66.2351 26.4349 72.9326 22.7303" fill="#E8D416"/>
<path d="M42.2559 109.743C42.2559 109.743 66.2895 83.3354 103.092 85.3339V55.9081C62.403 58.2192 42.2559 109.743 42.2559 109.743" fill="#E8D416"/>
<path d="M56.3044 48.8729C43.2836 76.6332 40.2139 109.743 40.2139 109.743C40.2139 109.743 56.2839 55.9082 103.092 50.8849C105.134 50.6674 116.746 50.511 116.746 50.511V15.6543C116.746 15.6543 75.0493 8.9113 56.3044 48.8729Z" fill="#E8D416"/>
<path d="M0.246582 129.435H11.6814V130.883H1.89375V137.102H10.2317V138.523H1.89375V146.952H0.246582V129.435Z" fill="#1C1D1C"/>
<path d="M12.6957 138.183C12.6957 132.963 15.8811 129.068 20.8634 129.068C25.8458 129.068 29.0652 132.963 29.0652 138.183C29.0652 143.404 25.8185 147.271 20.8362 147.271C15.8539 147.271 12.6685 143.37 12.6685 138.156L12.6957 138.183ZM27.2887 138.156C27.2887 133.914 25.0358 130.509 20.809 130.509C16.5822 130.509 14.3837 133.908 14.3837 138.156C14.3837 142.404 16.6162 145.796 20.809 145.796C25.0018 145.796 27.2887 142.398 27.2887 138.156Z" fill="#1C1D1C"/>
<path d="M34.8777 138.013L29.1875 129.435H31.0797L35.8646 136.837H35.9123L40.6496 129.435H42.4601L36.7971 138.034L42.7324 146.952H40.7925L35.817 139.21H35.7625L30.7121 146.952H28.8472L34.8777 138.013Z" fill="#1C1D1C"/>
<path d="M48.2592 129.435H54.4871C59.6668 129.435 61.9742 133.058 61.9742 138.272C61.9742 143.485 59.4694 146.972 54.2965 146.972H48.2388L48.2592 129.435ZM54.2693 145.511C58.4621 145.511 60.2794 142.69 60.2794 138.251C60.2794 133.813 58.6323 130.876 54.4395 130.876H49.8995V145.511H54.2693Z" fill="#1C1D1C"/>
<path d="M64.4316 129.435H76.3497V130.883H66.072V137.177H75.4989V138.598H66.072V145.436H76.622V146.952H64.4316V129.435Z" fill="#1C1D1C"/>
<path d="M79.0859 129.435H80.7331V145.436H90.0784V146.952H79.0859V129.435Z" fill="#1C1D1C"/>
<path d="M93.9445 129.435H92.2974V146.952H93.9445V129.435Z" fill="#1C1D1C"/>
<path d="M95.8027 129.435H97.518L102.058 142.35C102.453 143.424 102.99 145.239 102.99 145.239H103.038C103.038 145.239 103.603 143.356 103.991 142.275L108.565 129.435H110.232L103.882 146.952H102.092L95.8027 129.435Z" fill="#1C1D1C"/>
<path d="M111.975 129.435H123.9V130.883H113.622V137.177H123.042V138.598H113.622V145.436H124.165V146.952H111.975V129.435Z" fill="#1C1D1C"/>
<path d="M126.629 129.435H134.429C137.594 129.435 139.411 131.25 139.411 134.159C139.411 136.321 138.479 137.741 136.464 138.38V138.448C138.282 139.06 138.874 140.386 138.989 143.105C139.105 145.823 139.432 146.632 139.752 146.857V146.952H137.989C137.594 146.68 137.499 146.021 137.349 143.03C137.199 140.039 136.151 139.135 133.592 139.135H128.303V146.952H126.656L126.629 129.435ZM133.891 137.755C136.444 137.755 137.744 136.504 137.744 134.322C137.744 132.14 136.764 130.89 134.109 130.89H128.303V137.755H133.891Z" fill="#1C1D1C"/>
<path d="M146.538 139.312L140.208 129.435H142.025L147.375 137.911H147.423L152.82 129.435H154.583L148.206 139.359V146.952H146.538V139.312Z" fill="#1C1D1C"/>
</g>
<defs>
<clipPath id="clip0_75_3111">
<rect width="154.507" height="147.502" fill="white" transform="translate(0.246582)"/>
</clipPath>
</defs>
</svg>

                    </a>
                </div>
                <!--end::Login Header-->
                <!--begin::Login Sign in form-->
                <div class="login-signin">
                    <div class="mb-20">
                        <h3>@lang('auth.title')</h3>
                        <div class="text-muted font-weight-bold">@lang('auth.description')</div>
                    </div>
                    @if(Session::has('message'))
                        <p class="alert alert-danger">{{ Session::get('message') }}</p>
                    @endif
                    <form id="kt_form_1" method="post" action="{{Route('login')}}">
                        @csrf
                        <div class="form-group mb-5">
                            <input value="{{ old('email') }}" class="form-control h-auto form-control-solid py-4 px-8 @error('email') is-invalid @enderror" type="text" placeholder="@lang('auth.email')" id="email" name="email" autocomplete="off" />
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group mb-5">
                            <input class="form-control h-auto form-control-solid py-4 px-8" type="password" placeholder="@lang('auth.password')" name="password" />
                            @error('password')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group d-flex flex-wrap justify-content-between align-items-center">
                            <div class="checkbox-inline">
                                <label class="checkbox m-0 text-muted">
                                    <input type="checkbox" name="remember" />
                                    <span></span>@lang('auth.rememberMe')</label>
                            </div>
                            <a href="{{ route('password.request') }}" id="kt_login_forgot" class="text-muted text-hover-primary">@lang('auth.forgetPassword')</a>
                        </div>
                        <button type="submit" class="btn btn-primary font-weight-bold px-9 py-4 my-3 mx-4">@lang('auth.button')</button>
                    </form>
                </div>
                <!--end::Login Sign in form-->

                <!--begin::Login forgot password form-->
                <div class="login-forgot">
                    <div class="mb-20">
                        <h3>Forgotten Password ?</h3>
                        <div class="text-muted font-weight-bold">Enter your email to reset your password</div>
                    </div>
                    <form class="form" id="kt_login_forgot_form">
                        <div class="form-group mb-10">
                            <input class="form-control form-control-solid h-auto py-4 px-8" type="text" placeholder="Email" name="email" autocomplete="off" />
                        </div>
                        <div class="form-group d-flex flex-wrap flex-center mt-10">
                            <button id="kt_login_forgot_submit" class="btn btn-primary font-weight-bold px-9 py-4 my-3 mx-2">@lang('auth.reset')</button>
                            <button id="kt_login_forgot_cancel" class="btn btn-light-primary font-weight-bold px-9 py-4 my-3 mx-2">@lang('auth.login')</button>
                        </div>
                    </form>
                </div>
                <!--end::Login forgot password form-->
            </div>
        </div>
    </div>
    <!--end::Login-->
</div>
<!--end::Main-->
<script>var HOST_URL = "https://preview.keenthemes.com/metronic/theme/html/tools/preview";</script>
<!--begin::Global Config(global config for global JS scripts)-->
<script>var KTAppSettings = { "breakpoints": { "sm": 576, "md": 768, "lg": 992, "xl": 1200, "xxl": 1400 }, "colors": { "theme": { "base": { "white": "#ffffff", "primary": "#3699FF", "secondary": "#E5EAEE", "success": "#1BC5BD", "info": "#8950FC", "warning": "#FFA800", "danger": "#F64E60", "light": "#E4E6EF", "dark": "#181C32" }, "light": { "white": "#ffffff", "primary": "#E1F0FF", "secondary": "#EBEDF3", "success": "#C9F7F5", "info": "#EEE5FF", "warning": "#FFF4DE", "danger": "#FFE2E5", "light": "#F3F6F9", "dark": "#D6D6E0" }, "inverse": { "white": "#ffffff", "primary": "#ffffff", "secondary": "#3F4254", "success": "#ffffff", "info": "#ffffff", "warning": "#ffffff", "danger": "#ffffff", "light": "#464E5F", "dark": "#ffffff" } }, "gray": { "gray-100": "#F3F6F9", "gray-200": "#EBEDF3", "gray-300": "#E4E6EF", "gray-400": "#D1D3E0", "gray-500": "#B5B5C3", "gray-600": "#7E8299", "gray-700": "#5E6278", "gray-800": "#3F4254", "gray-900": "#181C32" } }, "font-family": "Poppins" };</script>
<!--end::Global Config-->
<!--begin::Global Theme Bundle(used by all pages)-->
<script src="{{asset('panel/assets/plugins/global/plugins.bundle.js')}}"></script>
<script src="{{asset('panel/assets/plugins/custom/prismjs/prismjs.bundle.js')}}"></script>
<script src="{{asset('panel/assets/js/scripts.bundle.js')}}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>
{{-- <script src="{{asset('panel/assets/js/pages/crud/forms/validation/form-controls.js')}}"></script> --}}
<!--end::Global Theme Bundle-->
<script>
    $(function() {
        $("#kt_form_1").validate({
            rules: {
                 email: {
                    required: true,
                    email: true
                },
                password: {
                    required: true,
                    minlength: 6
                }
            },
            messages: {
                password: {
                    required: '{{ __('auth.passwordRequired') }}',
                    minlength: '{{ __('auth.passwordMin') }}'
                },
                email: {
                    required: '{!!__('auth.emailRequires')!!}',
                    email: '{!!__('auth.emailChecked')!!}'
                },
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
    });
</script>
</body>
<!--end::Body-->
</html>
































{{--<div class="container">--}}
{{--    <div class="row justify-content-center">--}}
{{--        <div class="col-md-8">--}}
{{--            <div class="card">--}}
{{--                <div class="card-header">{{ __('Login') }}</div>--}}

{{--                <div class="card-body">--}}
{{--                    <form method="POST" action="{{ route('login') }}">--}}
{{--                        @csrf--}}

{{--                        <div class="form-group row">--}}
{{--                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>--}}

{{--                            <div class="col-md-6">--}}
{{--                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" c>--}}

{{--                                @error('email')--}}
{{--                                    <span class="invalid-feedback" role="alert">--}}
{{--                                        <strong>{{ $message }}</strong>--}}
{{--                                    </span>--}}
{{--                                @enderror--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                        <div class="form-group row">--}}
{{--                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>--}}

{{--                            <div class="col-md-6">--}}
{{--                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">--}}

{{--                                @error('password')--}}
{{--                                    <span class="invalid-feedback" role="alert">--}}
{{--                                        <strong>{{ $message }}</strong>--}}
{{--                                    </span>--}}
{{--                                @enderror--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                        <div class="form-group row">--}}
{{--                            <div class="col-md-6 offset-md-4">--}}
{{--                                <div class="form-check">--}}
{{--                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>--}}

{{--                                    <label class="form-check-label" for="remember">--}}
{{--                                        {{ __('Remember Me') }}--}}
{{--                                    </label>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                        <div class="form-group row mb-0">--}}
{{--                            <div class="col-md-8 offset-md-4">--}}
{{--                                <button type="submit" class="btn btn-primary">--}}
{{--                                    {{ __('Login') }}--}}
{{--                                </button>--}}

{{--                                @if (Route::has('password.request'))--}}
{{--                                    <a class="btn btn-link" href="{{ route('password.request') }}">--}}
{{--                                        {{ __('Forgot Your Password?') }}--}}
{{--                                    </a>--}}
{{--                                @endif--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </form>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}

