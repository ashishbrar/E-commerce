<?php

namespace App\Livewire\Roles;

use Livewire\Component;
use Spatie\Permission\Models\{Permission, Role};
use Illuminate\Support\Str;

class RolesCreate extends Component
{
    public $name;
    public $permissions = [];
    public $allPermissions = [];
    public $status = 'Active';

    public function mount()
    {
        $this->allPermissions = Permission::all();
    }

    public function render()
    {
        $groupedPermissions = $this->allPermissions->groupBy(function ($permission) {
            return Str::before($permission->name, ' ');
        });

        return view('livewire.roles.roles-create', [
            'groupedPermissions' => $groupedPermissions,
        ]);
    }

    public function submit()
    {
        $this->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'required|array|min:1',
        ]);

        $role = Role::create([
            'name' => $this->name,
            'status' => $this->status,
        ]);

        $role->givePermissionTo($this->permissions);

        session()->flash('success', 'Role created successfully.');

        return redirect()->route('roles.index');
    }
}
