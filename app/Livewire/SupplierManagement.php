<?php

namespace App\Livewire;

use Livewire\Component;

class SupplierManagement extends Component
{
    public $search = '';
    
    // Modal controls
    public $showAddSupplierModal = false;
    public $showViewSupplierModal = false;
    
    // Form data
    public $supplierId = null;
    public $supplierCode = '';
    public $supplierName = '';
    public $supplierEmail = '';
    public $supplierPhone = '';
    public $supplierAddress = '';
    public $supplierRefName = '';
    public $supplierRefPhone = '';
    
    // Viewed data
    public $viewSupplier = null;

    public function openAddModal()
    {
        $this->resetFields();
        $this->showAddSupplierModal = true;
    }

    public function openEditModal($id)
    {
        $this->resetFields();
        $supplier = \App\Models\Supplier::find($id);
        if($supplier) {
            $this->supplierId = $supplier->id;
            $this->supplierCode = $supplier->code;
            $this->supplierName = $supplier->name;
            $this->supplierEmail = $supplier->email;
            $this->supplierPhone = $supplier->phone;
            $this->supplierAddress = $supplier->address;
            $this->supplierRefName = $supplier->ref_name;
            $this->supplierRefPhone = $supplier->ref_phone;
            
            $this->showAddSupplierModal = true;
        }
    }

    public function openViewModal($id)
    {
        $this->viewSupplier = \App\Models\Supplier::find($id);
        if($this->viewSupplier) {
            $this->showViewSupplierModal = true;
        }
    }

    public function closeModals()
    {
        $this->showAddSupplierModal = false;
        $this->showViewSupplierModal = false;
        $this->resetFields();
    }

    public function resetFields()
    {
        $this->supplierId = null;
        $this->supplierCode = '';
        $this->supplierName = '';
        $this->supplierEmail = '';
        $this->supplierPhone = '';
        $this->supplierAddress = '';
        $this->supplierRefName = '';
        $this->supplierRefPhone = '';
        $this->viewSupplier = null;
        $this->resetValidation();
    }

    public function saveSupplier()
    {
        $this->validate([
            'supplierName' => 'required|string|max:255',
            'supplierEmail' => 'nullable|email|max:255',
            'supplierPhone' => 'nullable|string|max:255',
            'supplierAddress' => 'nullable|string',
            'supplierRefName' => 'nullable|string|max:255',
            'supplierRefPhone' => 'nullable|string|max:255',
        ]);

        if ($this->supplierId) {
            $supplier = \App\Models\Supplier::find($this->supplierId);
            if ($supplier) {
                $supplier->update([
                    'name' => $this->supplierName,
                    'email' => $this->supplierEmail,
                    'phone' => $this->supplierPhone,
                    'address' => $this->supplierAddress,
                    'ref_name' => $this->supplierRefName,
                    'ref_phone' => $this->supplierRefPhone,
                ]);
                session()->flash('success', 'Supplier updated successfully.');
            }
        } else {
            // Generate code
            $lastSup = \App\Models\Supplier::orderBy('id', 'desc')->first();
            $nextId = $lastSup ? $lastSup->id + 1 : 1;
            $code = 'SUP' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

            \App\Models\Supplier::create([
                'code' => $code,
                'name' => $this->supplierName,
                'email' => $this->supplierEmail,
                'phone' => $this->supplierPhone,
                'address' => $this->supplierAddress,
                'ref_name' => $this->supplierRefName,
                'ref_phone' => $this->supplierRefPhone,
            ]);
            session()->flash('success', 'Supplier created successfully.');
        }

        $this->closeModals();
    }

    public function deleteSupplier($id)
    {
        $supplier = \App\Models\Supplier::find($id);
        if ($supplier) {
            $supplier->delete();
            session()->flash('success', 'Supplier deleted successfully.');
        }
    }

    public function render()
    {
        $query = \App\Models\Supplier::query();
        if(!empty($this->search)) {
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('code', 'like', '%' . $this->search . '%')
                  ->orWhere('phone', 'like', '%' . $this->search . '%');
        }
        $suppliers = $query->orderBy('id', 'desc')->get();

        return view('livewire.supplier-management', [
            'suppliers' => $suppliers
        ])->layout('components.pos-layout');
    }
}
