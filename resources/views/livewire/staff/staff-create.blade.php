<div class="admin-page">
    <div class="admin-page-head">
        <div>
            <h2 class="admin-page-title">{{ __('Create Staff') }}</h2>
            <p class="admin-page-subtitle">{{ __('Add a staff member and set login status.') }}</p>
        </div>
        <a href="{{ route('staff.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center justify-content-center gap-2">
            <i class="bi bi-arrow-left"></i>
            {{ __('Back') }}
        </a>
    </div>

    <form wire:submit.prevent="submit">
        <div class="admin-card">
            <div class="admin-card-header">
                <h3 class="admin-card-title">{{ __('Staff Details') }}</h3>
            </div>
            <div class="admin-card-body">
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label for="name" class="form-label">Name</label>
                        <input wire:model="name" type="text" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Enter name">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input wire:model="email" type="email" class="form-control @error('email') is-invalid @enderror" id="email" placeholder="Enter email">
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="password" class="form-label">Password</label>
                        <input wire:model="password" type="password" class="form-control @error('password') is-invalid @enderror" id="password" placeholder="Enter password">
                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input wire:model="password_confirmation" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" placeholder="Confirm password">
                        @error('password_confirmation') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="status" class="form-label">Status</label>
                        <select wire:model="status" id="status" class="form-select @error('status') is-invalid @enderror">
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="admin-form-actions">
            <a href="{{ route('staff.index') }}" class="btn btn-outline-secondary">{{ __('Cancel') }}</a>
            <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
        </div>
    </form>
</div>
