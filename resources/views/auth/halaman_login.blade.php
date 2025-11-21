<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8" />
    <title>Login &mdash; {{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="theme-color" content="#ffffff">
    <meta name="color-scheme" content="light">

    <!-- App css -->
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" id="app-style" />
    <!-- Icons -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Plugins css -->
    <link href="{{ asset('assets/libs/jquery-confirm/jquery-confirm.min.css') }}" rel="stylesheet" />

    <script src="{{ asset('assets/js/head.js') }}"></script>
</head>

<body>
    <!-- Begin page -->
    <div class="account-page">
        <div class="container-fluid p-0">
            <div class="row align-items-center g-0 px-3 py-3 vh-100">
                <div class="col-xl-12">
                    <div class="row">
                        <div class="col-md-12 col-xl-4 mx-auto">
                            <div class="card bg-primary-subtle rounded-4">
                                <div class="card-body">
                                    <div class="mb-0 p-0 p-lg-3">
                                        <div class="mb-0 border-0 p-md-4 p-lg-0">
                                            {{-- <div class="mb-4 p-0 text-lg-start text-center">
                                                <div class="auth-brand">
                                                    <a href="{{ route('home.index') }}" class="logo logo-light">
                                                        <span class="logo-lg">
                                                            <img src="{{ asset('assets/images/logo-light-3.png') }}"
                                                                alt="" height="24">
                                                        </span>
                                                    </a>
                                                    <a href="{{ route('home.index') }}" class="logo logo-dark">
                                                        <span class="logo-lg">
                                                            <img src="{{ asset('assets/images/logo-dark-3.png') }}"
                                                                alt="" height="24">
                                                        </span>
                                                    </a>
                                                </div>
                                            </div> --}}

                                            <div class="auth-title-section mb-4 text-lg-start text-center">
                                                <h3 class="text-dark fw-semibold mb-3">
                                                    Silakan masuk untuk melanjutkan
                                                </h3>
                                            </div>

                                            <div class="pt-0">
                                                <form method="POST" action="/login" class="my-4">
                                                    @csrf
                                                    <div class="form-group mb-3">
                                                        <label for="login" class="form-label">
                                                            username
                                                        </label>
                                                        <input type="text" id="login" name="name"
                                                            class="form-control @error('login')
                                                                is-invalid
                                                            @enderror"
                                                            placeholder="Masukkan username" required>

                                                        @error('login')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>

                                                    <div class="form-group mb-3">
                                                        <label for="password" class="form-label">
                                                            Kata Sandi
                                                        </label>
                                                        <div class="input-group">
                                                            <input type="password" id="password" name="password"
                                                                class="form-control @error('password') is-invalid @enderror"
                                                                placeholder="Masukkan kata sandi" autocomplete="off"
                                                                required>
                                                            <span class="input-group-text bg-transparent"
                                                                id="togglePassword" style="cursor: pointer;">
                                                                <i class="ti ti-eye" id="passwordIcon"></i>
                                                            </span>
                                                        </div>

                                                        @error('password')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>

                                                    <div class="form-group mb-0 row">
                                                        <div class="col-12">
                                                            <div class="d-grid">
                                                                <button class="btn btn-primary fw-semibold g-recaptcha"
                                                                    type="submit">
                                                                    Masuk
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- END wrapper -->

    <!-- Vendor -->
    <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ asset('assets/libs/waypoints/lib/jquery.waypoints.min.js') }}"></script>
    <script src="{{ asset('assets/libs/jquery.counterup/jquery.counterup.min.js') }}"></script>
    <script src="{{ asset('assets/libs/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('assets/libs/jquery-confirm/jquery-confirm.min.js') }}"></script>

    <script>
        function onSubmit(token) {
            document.querySelector("form").submit();
        }

        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            const passwordIcon = document.getElementById('passwordIcon');

            togglePassword.addEventListener('click', () => {
                const isHidden = passwordInput.type === 'password';
                passwordInput.type = isHidden ? 'text' : 'password';
                passwordIcon.className = isHidden ? 'ti ti-eye-off' : 'ti ti-eye';
            });
        });
    </script>

    <!-- Google reCAPTCHA -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <!-- App js-->
    <script src="{{ asset('assets/js/app.js') }}"></script>

    <script>
        $(document).ready(function() {
            @if (\Illuminate\Support\Facades\Session::get('failed_message'))
                $.alert({
                    title: 'Peringatan',
                    content: '{{ \Illuminate\Support\Facades\Session::get('failed_message') }}',
                    type: 'red',
                    theme: 'material',
                    backgroundDismissAnimation: 'shake',
                    onOpenBefore: function() {
                        this.$title.css("color", "black");
                        this.$content.css("color", "black");
                    }
                });
            @endif

            @if (\Illuminate\Support\Facades\Session::get('success_message'))
                $.alert({
                    title: 'Informasi',
                    content: '{{ \Illuminate\Support\Facades\Session::get('success_message') }}',
                    type: 'green',
                    theme: 'material',
                    backgroundDismissAnimation: 'shake',
                    onOpenBefore: function() {
                        this.$title.css("color", "black");
                        this.$content.css("color", "black");
                    }
                });
            @endif
        });
    </script>
</body>

</html>
