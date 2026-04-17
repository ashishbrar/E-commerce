<?php

namespace App\Livewire\Roles;

use Livewire\Component;
use Spatie\Permission\Models\Role;

class RolesIndex extends Component
{
    public function render()
    {   
        $roles = Role::with('permissions')->orderBy('id')->get();
        $rolesData = $roles->map(function($role){
            $modulesCount = [];
            foreach($role->permissions as $permission){
                $module = explode(' ',$permission->name)[0];
                $modulesCount[$module] = ($modulesCount[$module]?? 0)+1;
            }
            $formattedPermissions = collect($modulesCount)
            ->map(fn($count,$module) => "$module($count)")
            ->implode(' ');
            return[
                'id' => $role->id,
                'name' => $role->name,
                'status' => $role->status,
                'permissions_formatted'=> $formattedPermissions,
            ];
        });

        return view('livewire.roles.roles-index',['roles' => $rolesData] );
    }

    public function delete($id)
    {
        $role = Role::findOrFail($id);

        if ($role->users()->count() > 0) {
            session()->flash("error", "Role cannot be deleted because it is assigned to users.");
            return redirect()->route('roles.index');
        }
        $role->delete();
        session()->flash("success", "Role deleted successfully");
        return redirect()->route('roles.index');
    }

    
}
