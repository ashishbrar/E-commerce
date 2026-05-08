<div class="admin-page">
    <div class="admin-page-head">
        <div>
            <h2 class="admin-page-title">{{ __('Settings') }}</h2>
            <p class="admin-page-subtitle">{{ __('Manage SMTP email delivery and payment credentials.') }}</p>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-12 col-xl-7">
            <form wire:submit.prevent="saveSmtp" class="admin-card h-100">
                <div class="admin-card-header">
                    <div>
                        <h3 class="admin-card-title">{{ __('SMTP Settings') }}</h3>
                        <p class="text-muted small mb-0">These credentials are loaded from the database for outgoing mail.</p>
                    </div>
                </div>

                <div class="admin-card-body">
                    @if (session('smtp_success'))
                        <div class="alert alert-success">{{ session('smtp_success') }}</div>
                    @endif
                    @if (session('smtp_error'))
                        <div class="alert alert-danger">{{ session('smtp_error') }}</div>
                    @endif

                    <div class="row g-3">
                        <div class="col-12 col-md-8">
                            <label for="mail_host" class="form-label">Mail Host</label>
                            <input wire:model.defer="mail_host" id="mail_host" type="text" class="form-control @error('mail_host') is-invalid @enderror" placeholder="smtp.gmail.com">
                            @error('mail_host') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12 col-md-4">
                            <label for="mail_port" class="form-label">Port</label>
                            <input wire:model.defer="mail_port" id="mail_port" type="number" class="form-control @error('mail_port') is-invalid @enderror" placeholder="587">
                            @error('mail_port') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="mail_username" class="form-label">Username</label>
                            <input wire:model.defer="mail_username" id="mail_username" type="text" class="form-control @error('mail_username') is-invalid @enderror" placeholder="your@email.com">
                            @error('mail_username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="mail_password" class="form-label">Password / App Password</label>
                            <input wire:model.defer="mail_password" id="mail_password" type="password" class="form-control @error('mail_password') is-invalid @enderror" autocomplete="new-password">
                            @error('mail_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12 col-md-4">
                            <label for="mail_encryption" class="form-label">Encryption</label>
                            <select wire:model.defer="mail_encryption" id="mail_encryption" class="form-select @error('mail_encryption') is-invalid @enderror">
                                <option value="tls">TLS</option>
                                <option value="ssl">SSL</option>
                                <option value="">None</option>
                            </select>
                            @error('mail_encryption') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12 col-md-4">
                            <label for="mail_from_address" class="form-label">From Email</label>
                            <input wire:model.defer="mail_from_address" id="mail_from_address" type="email" class="form-control @error('mail_from_address') is-invalid @enderror" placeholder="support@example.com">
                            @error('mail_from_address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12 col-md-4">
                            <label for="mail_from_name" class="form-label">From Name</label>
                            <input wire:model.defer="mail_from_name" id="mail_from_name" type="text" class="form-control @error('mail_from_name') is-invalid @enderror" placeholder="Ace Admin">
                            @error('mail_from_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input wire:model.defer="is_active" class="form-check-input" type="checkbox" id="is_active">
                                <label class="form-check-label fw-semibold" for="is_active">Use database SMTP settings</label>
                            </div>
                        </div>
                    </div>

                    <div class="admin-form-actions">
                        <button type="submit" class="btn btn-primary">{{ __('Save SMTP') }}</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="col-12 col-xl-5">
            <form wire:submit.prevent="savePaypal" class="admin-card mb-3">
                <div class="admin-card-header">
                    <h3 class="admin-card-title">{{ __('PayPal Credentials') }}</h3>
                </div>
                <div class="admin-card-body">
                    @if (session('paypal_success'))
                        <div class="alert alert-success">{{ session('paypal_success') }}</div>
                    @endif

                    <div class="row g-3">
                        <div class="col-12">
                            <label for="paypal_method" class="form-label">Mode</label>
                            <select wire:model.defer="paypal_method" id="paypal_method" class="form-select @error('paypal_method') is-invalid @enderror">
                                <option value="sandbox">Sandbox</option>
                                <option value="live">Live</option>
                            </select>
                            @error('paypal_method') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12">
                            <label for="client_id" class="form-label">Client ID</label>
                            <input wire:model.defer="client_id" id="client_id" type="text" class="form-control @error('client_id') is-invalid @enderror">
                            @error('client_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12">
                            <label for="client_secret" class="form-label">Client Secret</label>
                            <input wire:model.defer="client_secret" id="client_secret" type="password" class="form-control @error('client_secret') is-invalid @enderror" autocomplete="new-password">
                            @error('client_secret') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="admin-form-actions">
                        <button type="submit" class="btn btn-primary">{{ __('Save PayPal') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>
