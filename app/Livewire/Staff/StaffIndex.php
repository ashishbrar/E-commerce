<?php

namespace App\Livewire\Staff;

use Livewire\Component;
use App\Models\Staff;

class StaffIndex extends Component
{
    protected $listeners = ['deleteStaff' => 'delete'];

    public function render()
    {
        $staff = Staff::orderBy('id', 'desc')->get();
        return view('livewire.staff.staff-index', compact('staff'));
    }

    public function delete($id)
    {   
        $staffMember = Staff::find($id);

        if ($staffMember) {
            $staffMember->delete();
            session()->flash("success", "Staff member deleted successfully");

            $this->dispatch('livewire:staffDeleted', ['staffName' => $staffMember->name]);

            return redirect()->route('staff.index');
        }

        session()->flash("error", "Staff member not found.");
        return redirect()->route('staff.index');
    }
}
