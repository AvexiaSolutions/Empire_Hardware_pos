<?php

namespace App\Livewire\Pos;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;

#[Layout('components.layouts.customer')]
class CustomerDisplay extends Component
{
    public $cart = [];
    public $grandTotal = 0;

    #[On('echo:pos,CartUpdated')]
    public function updateCartData($payload)
    {
        $this->cart = $payload['cart'];
        $this->grandTotal = $payload['grandTotal'];
    }

    public function render()
    {
        return view('livewire.pos.customer-display');
    }
}
