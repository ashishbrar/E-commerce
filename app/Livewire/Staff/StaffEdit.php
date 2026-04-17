<?php
namespace App\Livewire\Staff;

use Livewire\Component;
use App\Models\Staff;
use Illuminate\Support\Facades\Hash;

class StaffEdit extends Component
{
    public $staff;
    public $name, $email, $status, $password, $password_confirmation, $roles = [];
    public $allRoles;

    public function mount($id)
    {
        $this->staff = Staff::findOrFail($id);

        $this->name = $this->staff->name;
        $this->email = $this->staff->email;
        $this->status = $this->staff->status;

        // If using roles, fetch all roles (stubbed here as empty array)
        $this->allRoles = []; // Replace with actual roles if using a Role model
        // $this->roles = $this->staff->roles->pluck('name')->toArray(); // If using roles relationship
    }

    public function submit()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'nullable|string|min:6|confirmed',
            'status' => 'required|in:Active,Inactive',
        ]);

        $this->staff->name = $this->name;
        $this->staff->email = $this->email;
        $this->staff->status = $this->status;

        if ($this->password) {
            $this->staff->password = Hash::make($this->password);
        }

        // If using roles, sync them here
        // $this->staff->roles()->sync($this->roles);

        $this->staff->save();

        session()->flash('success', 'Staff edited successfully.');

        return redirect()->route('staff.index');
    }

    public function render()
    {
        return view('livewire.staff.staff-edit', [
            'allRoles' => $this->allRoles,
        ]);
    }
}
