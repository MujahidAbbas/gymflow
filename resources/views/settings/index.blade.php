@extends('layouts.master')

@section('title')
    Settings
@endsection

@section('css')
<link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
@component('components.breadcrumb')
@slot('li_1')
    Settings
@endslot
@slot('title')
    System Settings
@endslot
@endcomponent

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">System Settings</h4>
                <div class="flex-shrink-0">
                    <button type="button" class="btn btn-soft-info btn-sm" id="resetDefaults">
                        <i class="ri-refresh-line align-middle me-1"></i> Reset to Defaults
                    </button>
                </div>
            </div>

            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="ri-check-line align-middle me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="ri-error-warning-line align-middle me-2"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route('settings.update') }}" method="POST" id="settingsForm">
                    @csrf

                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs nav-tabs-custom nav-success nav-justified mb-3" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#app-settings" role="tab">
                                <i class="ri-settings-3-line align-middle me-1"></i> Application
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#email-settings" role="tab">
                                <i class="ri-mail-line align-middle me-1"></i> Email
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#payment-settings" role="tab">
                                <i class="ri-bank-card-line align-middle me-1"></i> Payment
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#security-settings" role="tab">
                                <i class="ri-shield-check-line align-middle me-1"></i> Security
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#notification-settings" role="tab">
                                <i class="ri-notification-3-line align-middle me-1"></i> Notifications
                            </a>
                        </li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content text-muted">
                        <!-- Application Settings -->
                        <div class="tab-pane active" id="app-settings" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="app_name" class="form-label">Application Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('app_name') is-invalid @enderror" 
                                               id="app_name" name="app_name" 
                                               value="{{ old('app_name', $settings->get('app')?->firstWhere('name', 'app_name')->value ?? '') }}">
                                        @error('app_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="app_timezone" class="form-label">Timezone</label>
                                        <select class="form-select @error('app_timezone') is-invalid @enderror" 
                                                id="app_timezone" name="app_timezone">
                                            <option value="UTC">UTC</option>
                                            <option value="America/New_York">America/New_York</option>
                                            <option value="Europe/London">Europe/London</option>
                                            <option value="Asia/Dubai">Asia/Dubai</option>
                                        </select>
                                        @error('app_timezone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="app_currency" class="form-label">Currency</label>
                                        <input type="text" class="form-control" id="app_currency" name="app_currency" 
                                               value="{{ old('app_currency', $settings->get('app')?->firstWhere('name', 'app_currency')->value ?? 'USD') }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="app_language" class="form-label">Language</label>
                                        <select class="form-select" id="app_language" name="app_language">
                                            <option value="en">English</option>
                                            <option value="es">Spanish</option>
                                            <option value="fr">French</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Email Settings -->
                        <div class="tab-pane" id="email-settings" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="mail_mailer" class="form-label">Mail Provider</label>
                                        <select class="form-select" id="mail_mailer" name="mail_mailer">
                                            <option value="smtp">SMTP</option>
                                            <option value="sendmail">Sendmail</option>
                                            <option value="mailgun">Mailgun</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="mail_host" class="form-label">Mail Host</label>
                                        <input type="text" class="form-control" id="mail_host" name="mail_host" 
                                               value="{{ old('mail_host', $settings->get('email')?->firstWhere('name', 'mail_host')->value ?? '') }}">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="mail_port" class="form-label">Port</label>
                                        <input type="number" class="form-control" id="mail_port" name="mail_port" 
                                               value="{{ old('mail_port', $settings->get('email')?->firstWhere('name', 'mail_port')->value ?? '587') }}">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="mail_encryption" class="form-label">Encryption</label>
                                        <select class="form-select" id="mail_encryption" name="mail_encryption">
                                            <option value="tls">TLS</option>
                                            <option value="ssl">SSL</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="mail_from_address" class="form-label">From Email</label>
                                        <input type="email" class="form-control" id="mail_from_address" name="mail_from_address" 
                                               value="{{ old('mail_from_address', $settings->get('email')?->firstWhere('name', 'mail_from_address')->value ?? '') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Settings -->
                        <div class="tab-pane" id="payment-settings" role="tabpanel">
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="mb-3">Stripe Configuration</h5>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="stripe_key" class="form-label">Stripe Publishable Key</label>
                                        <input type="text" class="form-control" id="stripe_key" name="stripe_key" 
                                               value="{{ old('stripe_key', $settings->get('payment')?->firstWhere('name', 'stripe_key')->value ?? '') }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="stripe_secret" class="form-label">Stripe Secret Key</label>
                                        <input type="password" class="form-control" id="stripe_secret" name="stripe_secret" 
                                               value="{{ old('stripe_secret', $settings->get('payment')?->firstWhere('name', 'stripe_secret')->value ?? '') }}">
                                    </div>
                                </div>

                                <div class="col-12 mt-3">
                                    <h5 class="mb-3">PayPal Configuration</h5>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="paypal_mode" class="form-label">PayPal Mode</label>
                                        <select class="form-select" id="paypal_mode" name="paypal_mode">
                                            <option value="sandbox">Sandbox</option>
                                            <option value="live">Live</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="paypal_client_id" class="form-label">Client ID</label>
                                        <input type="text" class="form-control" id="paypal_client_id" name="paypal_client_id" 
                                               value="{{ old('paypal_client_id', $settings->get('payment')?->firstWhere('name', 'paypal_client_id')->value ?? '') }}">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="paypal_secret" class="form-label">Secret</label>
                                        <input type="password" class="form-control" id="paypal_secret" name="paypal_secret" 
                                               value="{{ old('paypal_secret', $settings->get('payment')?->firstWhere('name', 'paypal_secret')->value ?? '') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Security Settings -->
                        <div class="tab-pane" id="security-settings" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <div class="form-check form-switch form-switch-lg">
                                            <input class="form-check-input" type="checkbox" role="switch" 
                                                   id="email_verification_enable" name="email_verification_enable" value="on"
                                                   {{ ($settings->get('security')?->firstWhere('name', 'email_verification_enable')->value ?? '') == 'on' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="email_verification_enable">
                                                Enable Email Verification
                                            </label>
                                        </div>
                                        <small class="text-muted">Require users to verify their email address</small>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <div class="form-check form-switch form-switch-lg">
                                            <input class="form-check-input" type="checkbox" role="switch" 
                                                   id="2fa_enable" name="2fa_enable" value="on">
                                            <label class="form-check-label" for="2fa_enable">
                                                Enable Two-Factor Authentication
                                            </label>
                                        </div>
                                        <small class="text-muted">Allow users to enable 2FA for their accounts</small>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <div class="form-check form-switch form-switch-lg">
                                            <input class="form-check-input" type="checkbox" role="switch" 
                                                   id="recaptcha_enable" name="recaptcha_enable" value="on">
                                            <label class="form-check-label" for="recaptcha_enable">
                                                Enable reCAPTCHA
                                            </label>
                                        </div>
                                        <small class="text-muted">Protect forms with Google reCAPTCHA</small>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="session_lifetime" class="form-label">Session Lifetime (minutes)</label>
                                        <input type="number" class="form-control" id="session_lifetime" name="session_lifetime" 
                                               value="{{ old('session_lifetime', $settings->get('security')?->firstWhere('name', 'session_lifetime')->value ?? '120') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Notification Settings -->
                        <div class="tab-pane" id="notification-settings" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <div class="form-check form-switch form-switch-lg">
                                            <input class="form-check-input" type="checkbox" role="switch" 
                                                   id="notification_email" name="notification_email" value="on" checked>
                                            <label class="form-check-label" for="notification_email">
                                                Email Notifications
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <div class="form-check form-switch form-switch-lg">
                                            <input class="form-check-input" type="checkbox" role="switch" 
                                                   id="notification_sms" name="notification_sms" value="on">
                                            <label class="form-check-label" for="notification_sms">
                                                SMS Notifications
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <div class="form-check form-switch form-switch-lg">
                                            <input class="form-check-input" type="checkbox" role="switch" 
                                                   id="notification_push" name="notification_push" value="on">
                                            <label class="form-check-label" for="notification_push">
                                                Push Notifications
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-success">
                            <i class="ri-save-line align-middle me-1"></i> Save Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>
<script>
// Form validation
document.getElementById('settingsForm').addEventListener('submit', function(e) {
    const appName = document.getElementById('app_name').value;
    if (!appName) {
        e.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Validation Error',
            text: 'Application name is required!'
        });
    }
});

// Reset defaults confirmation
document.getElementById('resetDefaults').addEventListener('click', function() {
    Swal.fire({
        title: 'Reset to Defaults?',
        text: "This will reset all settings to their default values!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, reset it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // Add AJAX call to reset settings
            Swal.fire(
                'Reset!',
                'Settings have been reset to defaults.',
                'success'
            );
        }
    });
});
</script>
@endsection
