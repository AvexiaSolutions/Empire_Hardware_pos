<?php

namespace App\Livewire\Employer\Paysheet;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Employee;

#[Layout('components.pos-layout')]
class Show extends Component
{
    public $employees;

    public function mount()
    {
        $this->employees = Employee::all();
    }

    public function render()
    {
        return view('employer.paysheet.show');
    }
}
