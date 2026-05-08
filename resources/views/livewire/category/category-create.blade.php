<div class="admin-page">
    <div class="admin-page-head">
        <div>
            <h2 class="admin-page-title">{{ __('Create Category') }}</h2>
            <p class="admin-page-subtitle">{{ __('Create a product category with image and visibility status.') }}</p>
        </div>
        <a href="{{ route('category.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center justify-content-center gap-2">
            <i class="bi bi-arrow-left"></i>
            {{ __('Back') }}
        </a>
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit.prevent="submit">
        <div class="admin-card">
            <div class="admin-card-header">
                <h3 class="admin-card-title">{{ __('Category Details') }}</h3>
            </div>
            <div class="admin-card-body">
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label for="name" class="form-label">Name</label>
                        <input wire:model.defer="name" type="text" id="name" class="form-control @error('name') is-invalid @enderror">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="status" class="form-label">Status</label>
                        <select wire:model.defer="status" id="status" class="form-select @error('status') is-invalid @enderror">
                            <option value="">-- Select Status --</option>
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-12">
                        <label for="image" class="form-label">Image</label>
                        <input type="file" wire:model="image" id="image" accept="image/*" class="form-control @error('image') is-invalid @enderror">
                        @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="admin-form-actions">
            <a href="{{ route('category.index') }}" class="btn btn-outline-secondary">{{ __('Cancel') }}</a>
            <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
        </div>
    </form>
</div>
