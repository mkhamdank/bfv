@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/login-style.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;800&display=swap" rel="stylesheet">
    <style>
        .login100-form-title {
            font-family: 'Poppins', sans-serif !important;
            font-weight: 600 !important;
        }

        .input100 {
            font-family: 'Poppins', sans-serif !important;
            font-weight: 400 !important;
        }

        .login100-form-btn {
            font-family: 'Poppins', sans-serif !important;
            font-weight: 600 !important;
        }
    </style>
@endsection

@section('content')
    <div class="container-login100">
        <div class="wrap-login100">
            <div class="col-md-12 pl-md-5" style="margin-top: 50px;">
                <form method="post" action="{{ route('login') }}">
                    @csrf
                    {{-- {{ csrf_field() }} --}}
                    <span class="login100-form-title" style="color: white;background-color: #605ca8;padding-bottom: 0;text-align: center;font-size: 20px;font-weight: bold;padding: 10px;border-radius: 16px;margin-top: 20px;">
                        <img src="{{ asset('img/bridgesmall.png') }}" alt="smallogo" width="50px">&nbsp;&nbsp;Bridge For Vendor
                    </span>

                    @if ($errors->has('email'))
                        <div class="alert alert-danger alert-dismissible" style="margin-top: 10px;">
                            <h4 style="font-size: 15px;font-weight: bold;"> Error!</h4>
                            <span style="font-size: 12px">These credentials do not match our records.</span>
                        </div>
                    @endif
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible">
                            <h4 style="font-size: 15px;font-weight: bold;"> Success!</h4>
                            <span style="font-size: 12px">{{ session('success') }}</span>
                        </div>
                    @endif

                    <div class="wrap-input100 validate-input" style="margin-top:20px">
                        <!-- <input autocomplete="off" type="text" class="input100" placeholder="Email" id="email" name="email" value="{{ old('email') }}" required autofocus a> -->
                        <input id="username" type="text" class="input100 @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autofocus>
                        <span class="focus-input100"></span>
                        @error('username')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <span class="symbol-input100">
                            <i class="fa fa-envelope" aria-hidden="true"></i>
                        </span>
                    </div>
                                        

                    <!-- <div class="wrap-input100 validate-input" data-validate="Valid email is required: ex@abc.xyz" style="margin-top:20px">
                        <input autocomplete="off" type="text" class="input100" placeholder="Email" id="email" name="email" value="{{ old('email') }}" required autofocus a>
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-envelope" aria-hidden="true"></i>
                        </span>
                    </div> -->

                    <div class="wrap-input100 validate-input" data-validate="Password is required">
                        <input class="input100" type="password" placeholder="Password" id="password" name="password" required>
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-lock" aria-hidden="true"></i>
                        </span>
                    </div>



                    <div class="container-login100-form-btn">
                        <button class="login100-form-btn" type="submit">
                            Login
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(function() {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%'
            });
        });

        jQuery(document).ready(function() {
            $('#username').val('');
            $('#password').val('');
        });
    </script>
@endsection
