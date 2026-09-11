@extends('backend.app')

@section('title', 'PayPal Settings')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                <h4 class="mb-sm-0">PayPal Settings</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">PayPal Settings</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h4 class="card-title mb-0 flex-grow-1">Update PayPal Settings</h4>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('admin.paypal-settings.update') }}">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="paypal_mode" class="form-label">PayPal Mode</label>
                            <select name="paypal_mode" id="paypal_mode" class="form-control @error('paypal_mode') is-invalid @enderror" required>
                                <option value="sandbox" {{ env('PAYPAL_MODE', 'sandbox') == 'sandbox' ? 'selected' : '' }}>Sandbox (Testing)</option>
                                <option value="live" {{ env('PAYPAL_MODE') == 'live' ? 'selected' : '' }}>Live (Production)</option>
                            </select>
                            @error('paypal_mode')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="paypal_client_id" class="form-label">PayPal Client ID</label>
                            <input type="text" name="paypal_client_id" id="paypal_client_id" class="form-control @error('paypal_client_id') is-invalid @enderror"
                                value="{{ env('PAYPAL_SANDBOX_CLIENT_ID') }}" placeholder="Enter PayPal Client ID" required>
                            @error('paypal_client_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="paypal_client_secret" class="form-label">PayPal Client Secret</label>
                            <input type="text" name="paypal_client_secret" id="paypal_client_secret" class="form-control @error('paypal_client_secret') is-invalid @enderror"
                                value="{{ env('PAYPAL_SANDBOX_CLIENT_SECRET') }}" placeholder="Enter PayPal Client Secret" required>
                            @error('paypal_client_secret')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="paypal_success_url" class="form-label">Frontend Success Redirect URL</label>
                            <input type="url" name="paypal_success_url" id="paypal_success_url" class="form-control @error('paypal_success_url') is-invalid @enderror"
                                value="{{ env('PAYPAL_SUCCESS_REDIRECT_URL') }}" placeholder="http://localhost:3000/payment-success">
                            @error('paypal_success_url')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="paypal_cancel_url" class="form-label">Frontend Cancel Redirect URL</label>
                            <input type="url" name="paypal_cancel_url" id="paypal_cancel_url" class="form-control @error('paypal_cancel_url') is-invalid @enderror"
                                value="{{ env('PAYPAL_CANCEL_REDIRECT_URL') }}" placeholder="http://localhost:3000/payment-cancel">
                            @error('paypal_cancel_url')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Update Settings</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
