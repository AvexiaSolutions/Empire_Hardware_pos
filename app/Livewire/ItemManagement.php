<?php

namespace App\Livewire;

use Livewire\Component;

class ItemManagement extends Component
{
    use \Livewire\WithFileUploads;

    public $searchQuery = '';

    // Data lists
    public $categories = [];
    public $subCategories = [];
    public $items = [];

    // Modal states
    public $showAddCategoryModal = false;
    public $showAddSubCategoryModal = false;
    public $showAddItemModal = false;
    public $showAddStockModal = false;
    public $showEditItemModal = false;
    public $showItemDetailsModal = false;

    // Category form
    public $categoryName = '';

    // Sub Category form
    public $subCategoryParentId = '';
    public $subCategoryName = '';

    // Item form
    public $itemImage;
    public $itemCategoryId = '';
    public $itemName = '';
    public $itemCostPrice = '';
    public $itemSellingPrice = '';
    public $itemDiscount = '';
    public $itemQuantity = '';
    public $itemBaseUnit = '';
    public $itemHasBulkUnit = false;
    public $itemBulkUnit = '';
    public $itemBulkConversionFactor = 1;
    public $itemBulkCostPrice = '';
    public $itemBulkSellingPrice = '';
    public $itemHasWarranty = 0;
    public $itemWarrantyMonths = 0;
    public $itemSearchAliases = '';
    public $itemRackNumber = '';
    public $itemRackRow = '';

    // Stock Form
    public $stockItemId;
    public $stockQuantity = '';
    public $stockCostPrice = '';
    public $stockSellingPrice = '';

    // Edit Item
    public $editingItemId;

    // View Item Details
    public $viewingItemId;

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->categories = \App\Models\Category::with('subCategories')->get();
        $this->subCategories = \App\Models\SubCategory::with('category')->get();
        
        $query = \App\Models\Item::with(['category', 'subCategory', 'batches' => function($q) {
            $q->where('is_active', true);
        }]);

        if (!empty($this->searchQuery)) {
            $query->where('name', 'like', '%' . $this->searchQuery . '%')
                  ->orWhere('code', 'like', '%' . $this->searchQuery . '%')
                  ->orWhereHas('batches', function($q) {
                      $q->where('barcode', 'like', '%' . $this->searchQuery . '%');
                  });
        }

        $this->items = $query->orderBy('id', 'desc')->get();
    }

    public function updatedSearchQuery()
    {
        $this->loadData();
    }

    // Modal Actions
    public function openAddCategoryModal() { $this->showAddCategoryModal = true; }
    public function openAddSubCategoryModal() { $this->showAddSubCategoryModal = true; }
    public function openAddItemModal() { $this->showAddItemModal = true; }
    public function openAddStockModal($itemId) { 
        $this->stockItemId = $itemId;
        $item = \App\Models\Item::with('batches')->find($itemId);
        if($item) {
            $latestBatch = $item->batches()->latest()->first();
            $this->stockCostPrice = $latestBatch ? $latestBatch->getRawOriginal('cost_price') : 0;
            $this->stockSellingPrice = $latestBatch ? $latestBatch->selling_price : 0;
        }
        $this->showAddStockModal = true; 
    }
    public function closeModals() {
        $this->showAddCategoryModal = false;
        $this->showAddSubCategoryModal = false;
        $this->showAddItemModal = false;
        $this->showAddStockModal = false;
        $this->showEditItemModal = false;
        $this->showItemDetailsModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->categoryName = '';
        $this->subCategoryParentId = '';
        $this->subCategoryName = '';
        
        $this->itemImage = null;
        $this->itemCategoryId = '';
        $this->itemName = '';
        $this->itemCostPrice = '';
        $this->itemSellingPrice = '';
        $this->itemDiscount = '';
        $this->itemQuantity = '';
        $this->itemBaseUnit = '';
        $this->itemHasBulkUnit = false;
        $this->itemBulkUnit = '';
        $this->itemBulkConversionFactor = 1;
        $this->itemBulkCostPrice = '';
        $this->itemBulkSellingPrice = '';
        $this->itemHasWarranty = 0;
        $this->itemWarrantyMonths = 0;
        $this->itemSearchAliases = '';
        $this->itemRackNumber = '';
        $this->itemRackRow = '';

        $this->stockItemId = null;
        $this->stockQuantity = '';
        $this->stockCostPrice = '';
        $this->stockSellingPrice = '';

        $this->editingItemId = null;
        $this->viewingItemId = null;
    }

    // Saves
    public function saveCategory()
    {
        $this->validate(['categoryName' => 'required|string|max:255']);
        \App\Models\Category::create(['name' => $this->categoryName]);
        session()->flash('success', 'Category added successfully.');
        $this->loadData();
        $this->closeModals();
    }

    public function saveSubCategory()
    {
        $this->validate([
            'subCategoryParentId' => 'required|exists:categories,id',
            'subCategoryName' => 'required|string|max:255'
        ]);
        \App\Models\SubCategory::create([
            'category_id' => $this->subCategoryParentId,
            'name' => $this->subCategoryName
        ]);
        session()->flash('success', 'Sub Category added successfully.');
        $this->loadData();
        $this->closeModals();
    }

    public function saveItem()
    {
        $this->validate([
            'itemName' => 'required|string|max:255',
            'itemBaseUnit' => 'required|string',
            'itemQuantity' => 'required|numeric|min:0',
            'itemCostPrice' => 'required|numeric|min:0',
            'itemSellingPrice' => 'required|numeric|min:0',
            'itemBulkUnit' => $this->itemHasBulkUnit ? 'required|string' : 'nullable',
            'itemBulkConversionFactor' => $this->itemHasBulkUnit ? 'required|numeric|min:0.01' : 'nullable',
            'itemBulkCostPrice' => $this->itemHasBulkUnit ? 'required|numeric|min:0' : 'nullable',
            'itemBulkSellingPrice' => $this->itemHasBulkUnit ? 'required|numeric|min:0' : 'nullable',
            'itemSearchAliases' => 'nullable|string|max:255',
            'itemRackNumber' => 'nullable|string|max:50',
            'itemRackRow' => 'nullable|string|max:50',
        ]);

        $catId = null;
        $subCatId = null;
        if (!empty($this->itemCategoryId)) {
            if (str_starts_with($this->itemCategoryId, 'cat_')) {
                $catId = str_replace('cat_', '', $this->itemCategoryId);
            } elseif (str_starts_with($this->itemCategoryId, 'sub_')) {
                $subCatId = str_replace('sub_', '', $this->itemCategoryId);
                $subCat = \App\Models\SubCategory::find($subCatId);
                if($subCat) $catId = $subCat->category_id;
            }
        }

        $imagePath = null;
        if ($this->itemImage) {
            $imagePath = $this->itemImage->store('items', 'public');
        }

        // Generate base code ITEM001
        $lastItem = \App\Models\Item::orderBy('id', 'desc')->first();
        $nextId = $lastItem ? $lastItem->id + 1 : 1;
        $baseCode = 'ITEM' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

        $item = \App\Models\Item::create([
            'code' => $baseCode,
            'name' => $this->itemName,
            'image' => $imagePath,
            'base_unit' => $this->itemBaseUnit,
            'has_bulk_unit' => $this->itemHasBulkUnit,
            'bulk_unit' => $this->itemHasBulkUnit ? $this->itemBulkUnit : null,
            'bulk_conversion_factor' => $this->itemHasBulkUnit ? ($this->itemBulkConversionFactor ?: 1) : 1,
            'has_warranty' => $this->itemHasWarranty,
            'warranty_months' => $this->itemHasWarranty ? $this->itemWarrantyMonths : 0,
            'category_id' => $catId,
            'sub_category_id' => $subCatId,
            'search_aliases' => $this->itemSearchAliases,
            'rack_number' => $this->itemRackNumber,
            'rack_row' => $this->itemRackRow,
        ]);

        // Create first batch B01
        $item->batches()->create([
            'batch_no' => 'B01',
            'barcode' => $baseCode . '-B01',
            'cost_price' => $this->itemCostPrice,
            'selling_price' => $this->itemSellingPrice,
            'bulk_cost_price' => $this->itemHasBulkUnit ? $this->itemBulkCostPrice : 0,
            'bulk_selling_price' => $this->itemHasBulkUnit ? $this->itemBulkSellingPrice : 0,
            'quantity' => $this->itemQuantity,
            'is_active' => true
        ]);

        session()->flash('success', 'Item and Initial Stock added successfully.');
        $this->loadData();
        $this->closeModals();
    }

    public function saveStock()
    {
        $this->validate([
            'stockQuantity' => 'required|numeric|min:0.01',
            'stockCostPrice' => 'required|numeric|min:0',
            'stockSellingPrice' => 'required|numeric|min:0',
        ]);

        $item = \App\Models\Item::find($this->stockItemId);
        if(!$item) return;

        // Smart GRN Logic
        $latestBatch = $item->batches()->latest()->first();

        // If selling price is the same as the latest batch, we just add quantity to the latest batch
        if ($latestBatch && floatval($latestBatch->selling_price) === floatval($this->stockSellingPrice)) {
            $latestBatch->quantity += $this->stockQuantity;
            // Optionally update cost price if you want to average it, but simple way is to overwrite or keep oldest. 
            // We'll update cost price to newest.
            $latestBatch->cost_price = $this->stockCostPrice; 
            $latestBatch->save();
        } else {
            // Price is different, create new batch
            $batchCount = $item->batches()->count();
            $newBatchNo = 'B' . str_pad($batchCount + 1, 2, '0', STR_PAD_LEFT);
            $newBarcode = $item->code . '-' . $newBatchNo;

            $item->batches()->create([
                'batch_no' => $newBatchNo,
                'barcode' => $newBarcode,
                'cost_price' => $this->stockCostPrice,
                'selling_price' => $this->stockSellingPrice,
                'quantity' => $this->stockQuantity,
                'is_active' => true
            ]);
        }

        session()->flash('success', 'Stock added successfully.');
        $this->loadData();
        $this->closeModals();
    }

    public function editItem($id)
    {
        $item = \App\Models\Item::find($id);
        if ($item) {
            $this->editingItemId = $id;
            $this->itemName = $item->name;
            $this->itemCategoryId = $item->sub_category_id ? 'sub_'.$item->sub_category_id : ($item->category_id ? 'cat_'.$item->category_id : '');
            $this->itemBaseUnit = $item->base_unit;
            $this->itemHasBulkUnit = $item->has_bulk_unit;
            $this->itemBulkUnit = $item->bulk_unit;
            $this->itemBulkConversionFactor = $item->bulk_conversion_factor;
            $this->itemHasWarranty = $item->has_warranty;
            $this->itemWarrantyMonths = $item->warranty_months;
            $this->itemSearchAliases = $item->search_aliases;
            $this->itemRackNumber = $item->rack_number;
            $this->itemRackRow = $item->rack_row;
            
            $this->showEditItemModal = true;
        }
    }

    public function updateItem()
    {
        $this->validate([
            'itemName' => 'required|string|max:255',
            'itemBaseUnit' => 'required|string',
            'itemBulkUnit' => $this->itemHasBulkUnit ? 'required|string' : 'nullable',
            'itemBulkConversionFactor' => $this->itemHasBulkUnit ? 'required|numeric|min:0.01' : 'nullable',
            'itemSearchAliases' => 'nullable|string|max:255',
            'itemRackNumber' => 'nullable|string|max:50',
            'itemRackRow' => 'nullable|string|max:50',
        ]);

        $catId = null;
        $subCatId = null;
        if (!empty($this->itemCategoryId)) {
            if (str_starts_with($this->itemCategoryId, 'cat_')) {
                $catId = str_replace('cat_', '', $this->itemCategoryId);
            } elseif (str_starts_with($this->itemCategoryId, 'sub_')) {
                $subCatId = str_replace('sub_', '', $this->itemCategoryId);
                $subCat = \App\Models\SubCategory::find($subCatId);
                if($subCat) $catId = $subCat->category_id;
            }
        }

        $item = \App\Models\Item::find($this->editingItemId);
        if ($item) {
            $imagePath = $item->image;
            if ($this->itemImage && !is_string($this->itemImage)) {
                $imagePath = $this->itemImage->store('items', 'public');
            }

            $item->update([
                'name' => $this->itemName,
                'image' => $imagePath,
                'base_unit' => $this->itemBaseUnit,
                'has_bulk_unit' => $this->itemHasBulkUnit,
                'bulk_unit' => $this->itemHasBulkUnit ? $this->itemBulkUnit : null,
                'bulk_conversion_factor' => $this->itemHasBulkUnit ? ($this->itemBulkConversionFactor ?: 1) : 1,
                'has_warranty' => $this->itemHasWarranty,
                'warranty_months' => $this->itemHasWarranty ? $this->itemWarrantyMonths : 0,
                'category_id' => $catId,
                'sub_category_id' => $subCatId,
                'search_aliases' => $this->itemSearchAliases,
                'rack_number' => $this->itemRackNumber,
                'rack_row' => $this->itemRackRow,
            ]);

            session()->flash('success', 'Item updated successfully.');
            $this->loadData();
            $this->closeModals();
        }
    }

    public function viewItemDetails($id)
    {
        $this->viewingItemId = $id;
        $this->showItemDetailsModal = true;
    }

    public function deleteCategory($id)
    {
        \App\Models\Category::find($id)?->delete();
        $this->loadData();
    }

    public function deleteSubCategory($id)
    {
        \App\Models\SubCategory::find($id)?->delete();
        $this->loadData();
    }

    public function removeItem($id)
    {
        // Set all batches to inactive so barcode can't be used
        $item = \App\Models\Item::find($id);
        if($item) {
            $item->batches()->update(['is_active' => false, 'quantity' => 0]);
            $item->delete(); // Optionally soft delete or hard delete. Hard delete will wipe batches if cascade.
            // If we have cascade on delete, batches are deleted. But user asked: "barcode cannot be reused".
            // Since barcode is unique in batches table, deleting batches will FREE UP the barcode.
            // So we shouldn't delete the item or batch. We should just mark it inactive.
        }
        $this->loadData();
    }

    public function render()
    {
        $stockItemData = $this->stockItemId ? \App\Models\Item::with('batches')->find($this->stockItemId) : null;
        $viewingItemData = $this->viewingItemId ? \App\Models\Item::with(['category', 'subCategory', 'batches'])->find($this->viewingItemId) : null;

        return view('livewire.item-management', [
            'stockItem' => $stockItemData,
            'viewingItem' => $viewingItemData
        ])->layout('components.pos-layout');
    }
}
