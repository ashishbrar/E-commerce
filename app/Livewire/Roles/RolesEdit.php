<?php

namespace App\Livewire\Roles;

use Livewire\Component;
use Spatie\Permission\Models\{Permission,Role};
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class RolesEdit extends Component
{
    public $role; 
    public $name;
    public $permissions = [];
    public $allPermissions = [];
    public $status = [];

    public function mount($id)
    {
        $this->role = Role::find($id);
        $this->allPermissions = Permission::all();
        $this->name = $this->role->name;
        $this->status = $this->role->status;
        $this->permissions = $this->role->permissions()->pluck("name")->toArray();
    }

    public function submit()
    {
        $this->validate([
            'name' => 'required|unique:roles,name,' . $this->role->id,
            'permissions' => 'array',
        ]);

        $this->role->name = $this->name;
        $this->role->status = $this->status;
        $this->role->save();

        $this->role->syncPermissions($this->permissions);

        session()->flash('success', 'Role updated successfully.');
        return redirect()->route('roles.index');
    }

    public function render()
    {
        $groupedPermissions = $this->allPermissions->groupBy(function ($permission) {
            return Str::before($permission->name, ' ');
        });

        return view('livewire.roles.roles-edit', [
            'groupedPermissions' => $groupedPermissions,
        ]);
    }
}
