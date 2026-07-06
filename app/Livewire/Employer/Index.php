<?php

namespace App\Livewire\Employer;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Employee;

#[Layout('components.pos-layout')]
class Index extends Component
{
    public $emp_id;
    public $name;
    public $phone;
    public $designation;
    public $basic_salary;
    
    #[\Livewire\Attributes\Url]
    public $add = false;

    public function mount()
    {
        $this->generateNextEmpId();
        
        if ($this->add) {
            $this->dispatch('open-modal', id: 'createEmployerModal');
            $this->add = false; // Reset to avoid re-opening on reload
        }
    }

    public function generateNextEmpId()
    {
        $lastEmployee = Employee::latest('id')->first();
        $nextId = $lastEmployee ? $lastEmployee->id + 1 : 1;
        $this->emp_id = 'EMP' . str_pad($nextId, 3, '0', STR_PAD_LEFT);
    }

    public function save()
    {
        $this->validate([
            'emp_id' => 'required|unique:employees,emp_id',
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:255',
            'designation' => 'nullable|string|max:255',
            'basic_salary' => 'required|numeric|min:0'
        ]);

        Employee::create([
            'emp_id' => $this->emp_id,
            'name' => $this->name,
            'phone' => $this->phone,
            'designation' => $this->designation,
            'basic_salary' => $this->basic_salary,
        ]);

        $this->reset(['name', 'phone', 'designation', 'basic_salary']);
        $this->generateNextEmpId();
        
        $this->dispatch('close-modal', id: 'createEmployerModal');
    }

    public function render()
    {
        $employees = Employee::all();
        return view('livewire.employer.index', compact('employees'));
    }
}
