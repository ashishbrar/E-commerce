<?php

namespace App\Livewire\Users;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class UserCreate extends Component
{
    public $name, $email, $password, $password_confirmation, $allRoles;
    public $roles = [];
    public $status = 'Active'; 

    public function mount()
    {
        $this->allRoles = Role::all();
    }

    public function render()
    {
        return view('livewire.users.user-create', [
            'allRoles' => $this->allRoles,
        ]);
    }

    public function submit()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'roles' => 'required|array|min:1',
            'roles.*' => 'exists:roles,name',
        ]);

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'status' => $this->status,
            'password' => Hash::make($this->password),
        ]);

        $user->syncRoles($this->roles);

        session()->flash('success', 'User created successfully.');
        return redirect()->route('users.index');
    }
}
