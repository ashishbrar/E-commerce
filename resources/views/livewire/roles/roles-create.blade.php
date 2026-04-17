<div>
    <div class="row mb-4">
        <div class="col-12">
            <flux:heading size="xl" level="1">{{ __('Create Role') }}</flux:heading>
            <flux:subheading size="lg" class="mb-3">{{ __('Form for create roles') }}</flux:subheading>
            <flux:separator variant="subtle" />
        </div>
    </div>

    <div class="mb-3">
        <flux:button variant="primary" href="{{ route('roles.index') }}" color="red">Back</flux:button>
    </div>

    <div class="container">
        <form wire:submit.prevent="submit" class="mt-4" autocomplete="off">
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body bg-light p-3 rounded">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input wire:model.defer="name" type="text" id="name" class="form-control @error('name') is-invalid @enderror" autocomplete="off" />
                                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="status" class="form-label fw-medium">Status</label>
                                    <select wire:model="status" id="status" class="form-select" required>
                                        <option value="Active">Active</option>
                                        <option value="Inactive">Inactive</option>
                                    </select>
                                    @error('status') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                <div class="card mb-4">
                    <div class="card-body bg-light p-3 rounded">
                      <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label class="form-label fw-bold">Permissions</label>

                                        <div class="mb-2">
                                            <input
                                                type="checkbox"
                                                id="selectAll"
                                                class="form-check-input"
                                                wire:click="
                                                    $set('permissions', 
                                                        {{ collect($groupedPermissions)->flatten(1)->pluck('name')->toJson() }}
                                                    )
                                                "
                                                @if(collect($groupedPermissions)->flatten(1)->pluck('name')->diff($permissions ?? [])->isEmpty())
                                                    checked
                                                @endif
                                            >
                                            <label for="selectAll" class="form-check-label ms-2 fw-medium">Select All Permissions</label>
                                        </div>

                                        {{-- Permissions Table --}}
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover">
                                                <tbody>
                                                    @foreach($groupedPermissions as $group => $permissionsGroup)
                                                        <tr class="table-light">
                                                            <td colspan="3" class="px-3 py-2">
                                                                <div class="d-flex align-items-center gap-2">
                                                                    <input
                                                                        type="checkbox"
                                                                        class="form-check-input"
                                                                        wire:click="
                                                                            $set('permissions', 
                                                                                collect($permissions ?? [])->diff({{ Js::from($permissionsGroup->pluck('name')) }})
                                                                                ->merge(
                                                                                    collect($permissions ?? [])->intersect({{ Js::from($permissionsGroup->pluck('name')) }})
                                                                                    ->count() === {{ count($permissionsGroup) }} ? [] : {{ Js::from($permissionsGroup->pluck('name')) }}
                                                                                )
                                                                                ->unique()
                                                                                ->values()
                                                                            )
                                                                        "
                                                                        @if(collect($permissionsGroup->pluck('name'))->diff($permissions ?? [])->isEmpty())
                                                                            checked
                                                                        @endif
                                                                    >
                                                                    <strong>{{ ucfirst($group) }}</strong>
                                                                </div>
                                                            </td>
                                                        </tr>

                                                        @foreach($permissionsGroup as $permission)
                                                            <tr>
                                                                <td style="width: 30px;"></td>
                                                                <td class="px-3 py-2">{{ $permission->name }}</td>
                                                                <td class="text-center" style="width: 50px;">
                                                                    <input
                                                                        type="checkbox"
                                                                        value="{{ $permission->name }}"
                                                                        wire:model="permissions"
                                                                        class="form-check-input"
                                                                    >
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                        @error('permissions')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                        </div>
                            </div>
            </div>

                        
                </div>
            </div>

            <div class="row">
                <div class="col-12 d-flex justify-content-end">
                    <flux:button type="submit" variant="primary">Submit</flux:button>
                </div>
            </div>
        </form>
    </div>
</div>
