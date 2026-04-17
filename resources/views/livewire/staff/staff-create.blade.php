<div class="container mt-4">
    <div class="mb-4">
        <h1 class="fw-bold mb-2">{{ __('Create Staff') }}</h1>
        <div class="text-muted mb-3" style="font-size: 1.1rem;">{{ __('Add a new staff member to your organization') }}</div>
        <hr>
    </div>

    <a href="{{ route('staff.index') }}" class="btn btn-outline-danger mb-4">Back</a>

    <div class="card shadow-sm" style="max-width: 700px;">
        <div class="card-body">
            <form wire:submit.prevent="submit">
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label for="name" class="form-label">Name</label>
                        <input wire:model="name" type="text" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Enter name">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input wire:model="email" type="email" class="form-control @error('email') is-invalid @enderror" id="email" placeholder="Enter email">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label for="password" class="form-label">Password</label>
                        <input wire:model="password" type="password" class="form-control @error('password') is-invalid @enderror" id="password" placeholder="Enter password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input wire:model="password_confirmation" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" placeholder="Confirm password">
                        @error('password_confirmation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="mb-4 col-md-6">
                        <label for="status" class="form-label">Status</label>
                        <select wire:model="status" id="status" class="form-select @error('status') is-invalid @enderror">
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100">Submit</button>
            </form>
        </div>
    </div>
</div>
