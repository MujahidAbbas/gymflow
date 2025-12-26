@extends('layouts.master-without-nav')

@section('title')
    Verify Email Address
@endsection

@section('content')
<div class="auth-page-wrapper pt-5">
    <div class="auth-one-bg-position auth-one-bg" id="auth-particles">
        <div class="bg-overlay"></div>
        <div class="shape">
            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink"
                viewBox="0 0 1440 120">
                <path d="M 0,36 C 144,53.6 432,123.2 720,124 C 1008,124.8 1296,56.8 1440,40L1440 140L0 140z"></path>
            </svg>
        </div>
    </div>

    <div class="auth-page-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-center mt-sm-5 mb-4 text-white-50">
                        <div>
                            <a href="{{ route('root') }}" class="d-inline-block auth-logo">
                                <img src="{{ URL::asset('build/images/logo-light.png') }}" alt="" height="20">
                            </a>
                        </div>
                        <p class="mt-3 fs-15 fw-medium">{{ config('app.name') }}</p>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card mt-4">
                        <div class="card-body p-4 text-center">
                            <div class="avatar-lg mx-auto mt-2">
                                <div class="avatar-title bg-light text-success display-3 rounded-circle">
                                    <i class="ri-mail-line"></i>
                                </div>
                            </div>
                            <div class="mt-4 pt-2">
                                <h4>Verify Your Email</h4>
                                <p class="text-muted mx-4">
                                    Before proceeding, please check your email for a verification link.
                                </p>
                                
                                @if (session('resent'))
                                    <div class="alert alert-success mt-3" role="alert">
                                        A fresh verification link has been sent to your email address.
                                    </div>
                                @endif

                                @if (session('error'))
                                    <div class="alert alert-danger mt-3" role="alert">
                                        {{ session('error') }}
                                    </div>
                                @endif

                                <div class="mt-4">
                                    <form method="POST" action="{{ route('email.verification.send') }}">
                                        @csrf
                                        <button type="submit" class="btn btn-success w-100">
                                            <i class="ri-mail-send-line align-middle me-1"></i> Resend Verification Email
                                        </button>
                                    </form>
                                </div>

                                <div class="mt-3">
                                    <p class="text-muted mb-0">
                                        Didn't receive the email? Check your spam folder or
                                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-link p-0 align-baseline">
                                                sign out
                                            </button>
                                        </form>
                                        and try again.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 text-center">
                        <p class="mb-0">
                            Already verified? 
                            <a href="{{ route('dashboard') }}" class="fw-semibold text-primary">
                                Go to Dashboard
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-center">
                        <p class="mb-0 text-muted">&copy;
                            <script>document.write(new Date().getFullYear())</script> {{ config('app.name') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</div>
@endsection
