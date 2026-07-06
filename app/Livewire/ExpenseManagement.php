<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use App\Models\Expense;
use Carbon\Carbon;

#[Layout('components.pos-layout')]
class ExpenseManagement extends Component
{
    use WithPagination;

    public $search = '';
    
    // Form fields
    public $expense_id;
    public $description;
    public $amount;
    public $date;

    public $isEditMode = false;

    public function mount()
    {
        $this->date = Carbon::today()->format('Y-m-d');
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function resetForm()
    {
        $this->expense_id = null;
        $this->description = '';
        $this->amount = '';
        $this->date = Carbon::today()->format('Y-m-d');
        $this->isEditMode = false;
        $this->resetValidation();
    }

    public function edit($id)
    {
        $expense = Expense::findOrFail($id);
        $this->expense_id = $expense->id;
        $this->description = $expense->description;
        $this->amount = $expense->amount;
        $this->date = Carbon::parse($expense->date)->format('Y-m-d');
        $this->isEditMode = true;
        $this->resetValidation();
    }

    public function save()
    {
        $this->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
        ]);

        if ($this->isEditMode) {
            $expense = Expense::findOrFail($this->expense_id);
            $expense->update([
                'description' => $this->description,
                'amount' => $this->amount,
                'date' => $this->date,
            ]);
            session()->flash('success', 'Expense updated successfully.');
        } else {
            Expense::create([
                'description' => $this->description,
                'amount' => $this->amount,
                'date' => $this->date,
            ]);
            session()->flash('success', 'Expense added successfully.');
        }

        $this->resetForm();
        $this->dispatch('close-modal');
    }

    public function delete($id)
    {
        Expense::findOrFail($id)->delete();
        session()->flash('success', 'Expense deleted successfully.');
    }

    public function render()
    {
        $expenses = Expense::where('description', 'like', '%' . $this->search . '%')
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.expense-management', [
            'expenses' => $expenses
        ]);
    }
}
