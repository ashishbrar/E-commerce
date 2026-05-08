<div class="admin-page">
    <div class="admin-page-head">
        <div>
            <h2 class="admin-page-title">{{ __('Edit Role') }}</h2>
            <p class="admin-page-subtitle">{{ __('Update role details and permission access.') }}</p>
        </div>
        <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center justify-content-center gap-2">
            <i class="bi bi-arrow-left"></i>
            {{ __('Back') }}
        </a>
    </div>

    <form wire:submit.prevent="submit">
        <div class="row g-3">
            <div class="col-12 col-xl-4">
                <div class="admin-card">
                    <div class="admin-card-header">
                        <h3 class="admin-card-title">{{ __('Role Details') }}</h3>
                    </div>
                    <div class="admin-card-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input wire:model="name" type="text" id="name" class="form-control @error('name') is-invalid @enderror">
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div>
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

            <div class="col-12 col-xl-8">
                <div class="admin-card">
                    <div class="admin-card-header">
                        <div>
                            <h3 class="admin-card-title">{{ __('Permissions') }}</h3>
                            <p class="text-muted small mb-0">Select permissions by module.</p>
                        </div>
                        <div class="form-check mb-0">
                            <input type="checkbox" id="selectAll"
                                class="form-check-input"
                                onclick="
                                    var checkboxes = document.querySelectorAll('.permission-checkbox');
                                    for(var i=0;i<checkboxes.length;i++){
                                        checkboxes[i].checked = this.checked;
                                        checkboxes[i].dispatchEvent(new Event('change'));
                                    }
                                "
                            >
                            <label for="selectAll" class="form-check-label fw-semibold">Select All</label>
                        </div>
                    </div>
                    <div class="admin-card-body">
                        <div class="admin-table-wrap">
                            <table class="table table-hover align-middle admin-table">
                                <tbody>
                                    @foreach($groupedPermissions as $group => $permissionsGroup)
                                        <tr>
                                            <td colspan="3" class="bg-light">
                                                <div class="d-flex align-items-center gap-2 fw-bold">
                                                    <input type="checkbox"
                                                        class="form-check-input group-checkbox"
                                                        onclick="
                                                            var groupBoxes = document.querySelectorAll('.permission-checkbox-group-{{ \Illuminate\Support\Str::slug($group) }}');
                                                            var checked = this.checked;
                                                            for(var i=0;i<groupBoxes.length;i++){
                                                                groupBoxes[i].checked = checked;
                                                                groupBoxes[i].dispatchEvent(new Event('change'));
                                                            }
                                                        "
                                                    >
                                                    <span>{{ ucfirst($group) }}</span>
                                                </div>
                                            </td>
                                        </tr>
                                        @foreach($permissionsGroup as $permission)
                                            <tr>
                                                <td style="width: 34px;"></td>
                                                <td>{{ $permission->name }}</td>
                                                <td class="text-end" style="width: 70px;">
                                                    <input
                                                        type="checkbox"
                                                        value="{{ $permission->name }}"
                                                        wire:model="permissions"
                                                        class="form-check-input permission-checkbox permission-checkbox-group-{{ \Illuminate\Support\Str::slug($group) }}"
                                                    />
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @error('permissions') <div class="text-danger mt-2">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="admin-form-actions">
            <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary">{{ __('Cancel') }}</a>
            <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
        </div>
    </form>
</div>
