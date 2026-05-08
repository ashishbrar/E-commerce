<div class="admin-page">
    <div class="admin-page-head">
        <div>
            <h2 class="admin-page-title">{{ __('Edit Email Template') }}</h2>
            <p class="admin-page-subtitle">{{ $name }} <span class="text-muted small">— key: <code>{{ $template->key }}</code></span></p>
        </div>
        <a href="{{ route('settings.email-templates') }}" class="btn btn-outline-secondary d-inline-flex align-items-center justify-content-center gap-2">
            <i class="bi bi-arrow-left"></i>
            {{ __('Back') }}
        </a>
    </div>

    @if (session()->has('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form wire:submit.prevent="save" autocomplete="off">
        <div class="admin-card">
            <div class="admin-card-header">
                <h3 class="admin-card-title">{{ __('Template Content') }}</h3>
                <p class="text-muted small mb-0">
                    {{ __('Use placeholders like') }}
                    <code>@{{name}}</code>, <code>@{{email}}</code>, <code>@{{order_id}}</code>, <code>@{{order_total}}</code>, <code>@{{login_url}}</code>, <code>@{{reset_link}}</code>.
                </p>
            </div>
            <div class="admin-card-body">
                <div class="row g-3">
                    <div class="col-12 col-md-8">
                        <label for="subject" class="form-label">{{ __('Subject') }}</label>
                        <input wire:model.defer="subject" type="text" id="subject" class="form-control @error('subject') is-invalid @enderror">
                        @error('subject') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label d-block">{{ __('Status') }}</label>
                        <div class="form-check form-switch">
                            <input wire:model.defer="isActive" class="form-check-input" type="checkbox" id="is_active">
                            <label class="form-check-label" for="is_active">{{ __('Active') }}</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <label for="body" class="form-label">{{ __('Body (HTML allowed)') }}</label>
                        <textarea wire:model.defer="body" id="body" rows="14" class="form-control @error('body') is-invalid @enderror"></textarea>
                        @error('body') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="admin-form-actions">
            <a href="{{ route('settings.email-templates') }}" class="btn btn-outline-secondary">{{ __('Cancel') }}</a>
            <button type="submit" class="btn btn-primary">{{ __('Save Changes') }}</button>
        </div>
    </form>
</div>
