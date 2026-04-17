<?php

namespace App\Livewire\Users;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UserEdit extends Component
{   
    public $user;
    public $name, $email, $password, $password_confirmation,$allRoles;
    public $roles = [];
    public $status =[];
    
    
    public function mount($id)
    {
        $this->user = User::find($id);
        $this->name = $this->user->name;
        $this->email = $this->user->email;
        $this->status = $this->user->status;
        $this->allRoles = Role::all();
        $this->roles = $this->user->roles()->pluck('name');
    }
    public function render()
    {
        return view('livewire.users.user-edit');
    }
      public function submit()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
        ]);
            $this->user->name = $this->name;
            $this->user->email = $this->email;
            $this->user->status = $this->status;

        if($this->password){
            $this->user->password = Hash::make($this->password);
        }
        $this->user->syncRoles($this->roles);
        $this->user->save();
        session()->flash('success', 'User Edit successfully.');
        return redirect()->route('users.index');
    }
}
