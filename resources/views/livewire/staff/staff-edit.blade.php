<div class="admin-page">
    <div class="admin-page-head">
        <div>
            <h2 class="admin-page-title">{{ __('Edit Staff') }}</h2>
            <p class="admin-page-subtitle">{{ __('Update staff profile, roles, password, and account status.') }}</p>
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
                        <input wire:model="name" type="text" id="name" class="form-control @error('name') is-invalid @enderror">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input wire:model="email" type="email" id="email" class="form-control @error('email') is-invalid @enderror">
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" wire:model="password" id="password" class="form-control @error('password') is-invalid @enderror">
                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" wire:model="password_confirmation" id="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror">
                        @error('password_confirmation') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-12 col-lg-8">
                        <label class="form-label">Roles</label>
                        <div class="d-flex flex-wrap gap-3">
                            @foreach($allRoles as $role)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="staff_role_{{ $role->id }}" value="{{ $role->name }}" wire:model="roles">
                                    <label class="form-check-label" for="staff_role_{{ $role->id }}">{{ $role->name }}</label>
                                </div>
                            @endforeach
                        </div>
                        @error('roles') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-12 col-lg-4">
                        <label for="status" class="form-label">Status</label>
                        <select wire:model="status" id="status" required class="form-select @error('status') is-invalid @enderror">
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
