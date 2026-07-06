<?php

namespace App\Livewire\Employer\Paysheet;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.pos-layout')]
class Select extends Component
{
    public function render()
    {
        return view('employer.paysheet.select');
    }
}
