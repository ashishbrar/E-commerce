<?php

namespace App\Livewire\Staff;

use Livewire\Component;
use App\Models\Staff;
use Illuminate\Support\Facades\Hash;

class StaffCreate extends Component
{
    public $name, $email, $status = 'Active', $password, $password_confirmation;

    public function submit()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:staff,email',
            'password' => 'required|string|min:6|confirmed',
            'status' => 'required|in:Active,Inactive',
        ]);

        Staff::create([
            'name' => $this->name,
            'email' => $this->email,
            'status' => $this->status,
            'password' => Hash::make($this->password),
        ]);

        session()->flash('success', 'Staff member created successfully.');
        return redirect()->route('staff.index');
    }

    public function render()
    {
        return view('livewire.staff.staff-create');
    }
}
