@extends('layouts.master-without-nav')

@section('title')
    Two-Factor Authentication
@endsection

@section('content')
<div class="auth-page-wrapper pt-5">
    <!-- auth page bg -->
    <div class="auth-one-bg-position auth-one-bg" id="auth-particles">
        <div class="bg-overlay"></div>

        <div class="shape">
            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink"
                viewBox="0 0 1440 120">
                <path d="M 0,36 C 144,53.6 432,123.2 720,124 C 1008,124.8 1296,56.8 1440,40L1440 140L0 140z"></path>
            </svg>
        </div>
    </div>

    <!-- auth page content -->
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
                        <div class="card-body p-4">
                            <div class="mb-4 text-center">
                                <lord-icon src="https://cdn.lordicon.com/rhvddzym.json" trigger="loop"
                                    colors="primary:#0ab39c" class="avatar-xl"></lord-icon>
                            </div>

                            <div class="text-center mt-2">
                                <h5 class="text-primary">Two-Factor Authentication</h5>
                                <p class="text-muted">Enter the 6-digit code from your authenticator app</p>
                            </div>

                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    @foreach ($errors->all() as $error)
                                        {{ $error }}
                                    @endforeach
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <div class="p-2">
                                <form method="POST" action="{{ route('login.2fa.verify') }}">
                                    @csrf

                                    <div class="mb-4">
                                        <label for="code" class="form-label">Verification Code</label>
                                        <input type="text" class="form-control form-control-lg text-center @error('code') is-invalid @enderror" 
                                               id="code" name="code" placeholder="000000" maxlength="6" required autofocus
                                               pattern="[0-9]{6}">
                                        @error('code')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                        <div class="form-text">
                                            Open your authenticator app and enter the 6-digit code
                                        </div>
                                    </div>

                                    <div class="mt-4">
                                        <button class="btn btn-success w-100" type="submit">
                                            <i class="ri-shield-check-line align-middle me-1"></i> Verify Code
                                        </button>
                                    </div>
                                </form>

                                <div class="mt-4 text-center">
                                    <p class="mb-0">Lost your device? <a href="{{ route('login') }}" class="fw-semibold text-primary">
                                        Use backup method</a></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 text-center">
                        <p class="mb-0">
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-link text-muted p-0">
                                    <i class="ri-logout-box-line align-middle me-1"></i> Sign Out
                                </button>
                            </form>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- footer -->
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

@section('script')
<script>
    // Auto-submit on 6 digits
    document.getElementById('code').addEventListener('input', function(e) {
        if (this.value.length === 6) {
            this.form.submit();
        }
    });
</script>
@endsection
