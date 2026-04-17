<div>
    <div class="mb-4 w-100">
        <flux:heading size="xl" level="1">{{ __('Edit Role') }}</flux:heading>
        <flux:subheading size="lg" class="mb-3">{{ __('Form for editing roles') }}</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    <flux:button variant="primary" href="{{ route('roles.index') }}" color="red">Back</flux:button>

    <div class="container mt-4">
        <form wire:submit.prevent="submit" class="mt-4">
            <div class="row mb-3">
                <div class="col-md-6 mb-3">
                    <label for="status" class="form-label fw-medium">Name</label>
                    <flux:input wire:model="name"/>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="status" class="form-label fw-medium">Status</label>
                    <select wire:model="status" id="status" required class="form-select">
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                    @error('status')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-8 mb-3">
                    <flux:checkbox.group wire:model="permissions" label="Permissions">
                        <div class="mb-2">
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
                            <label for="selectAll" class="form-check-label ms-2 fw-medium"><strong>Select All Permissions</strong></label>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <tbody>
                                    @foreach($groupedPermissions as $group => $permissions)
                                        <tr class="table-light">
                                            <td colspan="3" class="px-3 py-2">
                                                <div class="d-flex justify-content-between align-items-center fw-bold">
                                                    <div class="d-flex align-items-center gap-2">
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
                                                        <b><span>{{ ucfirst($group) }}</span></b>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @foreach($permissions as $permission)
                                            <tr>
                                                <td style="width:30px;"></td>
                                                <td class="px-3 py-2">{{ $permission->name }}</td>
                                                <td class="text-center" style="width:50px;">
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
                    </flux:checkbox.group>
                </div>
            </div>


            <div class="d-flex justify-content-end">
                <flux:button type="submit" variant="primary">Submit</flux:button>
            </div>
        </form>
    </div>
</div>
