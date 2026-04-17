<div class="container mt-5">
    <div class="mb-4">
        <h1 class="display-4">{{ __('Create User') }}</h1>
        <p class="lead mb-4">{{ __('Form to create a user') }}</p>
        <hr>
    </div>
    @if (session()->has('success'))
        <div class="alert alert-success mb-4">
            {{ session('success') }}
        </div>
    @endif
    <a href="{{ route('users.index') }}" class="btn btn-danger mb-3">Back</a>
    <div class="w-100">
        <form wire:submit.prevent="submit" autocomplete="off" class="mt-2">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="name" class="form-label">Name</label>
                    <input wire:model.defer="name" type="text" id="name" class="form-control @error('name') is-invalid @enderror" autocomplete="off" />
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label for="email" class="form-label">Email</label>
                    <input wire:model.defer="email" type="email" id="email" class="form-control @error('email') is-invalid @enderror" autocomplete="off" />
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="password" class="form-label">Password</label>
                    <input wire:model.defer="password" type="password" id="password" class="form-control @error('password') is-invalid @enderror" autocomplete="off" />
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input wire:model.defer="password_confirmation" type="password" id="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" autocomplete="off" />
                    @error('password_confirmation') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="row mb-3"> 
                <div class="col-md-6">
                    <label class="form-label">Roles</label>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($allRoles as $role)
                            <div class="form-check me-3">
                                <input
                                    class="form-check-input @error('roles') is-invalid @enderror"
                                    type="checkbox"
                                    id="role_{{ $role->id }}"
                                    value="{{ $role->name }}"
                                    wire:model="roles"
                                >
                                <label class="form-check-label" for="role_{{ $role->id }}">
                                    {{ $role->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                    @error('roles') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label for="status" class="form-label">Status</label>
                    <select wire:model.defer="status" id="status" class="form-select @error('status') is-invalid @enderror">
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
</div>
