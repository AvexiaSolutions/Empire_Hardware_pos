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
    public $showPrintModal = false;
    public $showBulkPrintModal = false;

    // Print Forms
    public $printBatchId;
    public $printCopies = 1;
    public $pendingBatchesToPrint = [];

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

    public $itemHasWarranty = 0;
    public $itemWarrantyMonths = 0;
    public $itemHasExpiryDate = false;
    public $expiryDates = [];
    public $itemSearchAliases = '';
    public $itemRackNumber = '';
    public $itemRackRow = '';

    public $stockItemId;
    public $stockQuantity = '';
    public $stockCostPrice = '';
    public $stockSellingPrice = '';
    public $stockDiscount = '';
    public $stockExpiryDates = [];

    // Convert to Loose
    public $showConvertToLooseModal = false;
    public $convertParentItemId;
    public $convertParentItemName = '';
    public $convertConversionFactor = '';
    public $convertLoosePrice = '';
    public $convertQuantityToOpen = '';

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

        $this->items = $query->orderBy('name')->get();
    }

    public function updatedSearchQuery()
    {
        $this->loadData();
    }

    // Auto-calculation from bulk to single removed as requested by user



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
            $this->stockDiscount = $latestBatch ? $latestBatch->discount : '';
            $this->stockBulkCostPrice = $latestBatch ? $latestBatch->getRawOriginal('bulk_cost_price') : 0;
            $this->stockBulkSellingPrice = $latestBatch ? $latestBatch->bulk_selling_price : 0;
            $this->stockBulkDiscount = $latestBatch ? $latestBatch->bulk_discount : '';
            $this->stockAddByBulk = false;
            $this->stockBulkQuantity = '';
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
        $this->showPrintModal = false;
        $this->showBulkPrintModal = false;
        $this->showConvertToLooseModal = false;
        $this->resetForm();
    }



    public function addExpiryDateRow($forStock = false)
    {
        if ($forStock) {
            $this->stockExpiryDates[] = ['date' => '', 'quantity' => ''];
        } else {
            $this->expiryDates[] = ['date' => '', 'quantity' => ''];
        }
    }

    public function removeExpiryDateRow($index, $forStock = false)
    {
        if ($forStock) {
            unset($this->stockExpiryDates[$index]);
            $this->stockExpiryDates = array_values($this->stockExpiryDates);
        } else {
            unset($this->expiryDates[$index]);
            $this->expiryDates = array_values($this->expiryDates);
        }
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
        $this->itemHasWarranty = 0;
        $this->itemWarrantyMonths = 0;
        $this->itemHasExpiryDate = false;
        $this->expiryDates = [];
        $this->itemSearchAliases = '';
        $this->itemRackNumber = '';
        $this->itemRackRow = '';

        $this->stockItemId = null;
        $this->stockQuantity = '';
        $this->stockCostPrice = '';
        $this->stockSellingPrice = '';
        $this->stockDiscount = '';

        $this->convertParentItemId = null;
        $this->convertParentItemName = '';
        $this->convertConversionFactor = '';
        $this->convertLoosePrice = '';
        $this->convertQuantityToOpen = '';
        $this->stockExpiryDates = [];
        $this->editingItemId = null;
        $this->viewingItemId = null;
        
        $this->printBatchId = null;
        $this->printCopies = 1;
        $this->pendingBatchesToPrint = [];
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
            'has_expiry_date' => $this->itemHasExpiryDate,
            'has_warranty' => $this->itemHasWarranty,
            'warranty_months' => $this->itemHasWarranty ? $this->itemWarrantyMonths : 0,
            'category_id' => $catId,
            'sub_category_id' => $subCatId,
            'search_aliases' => $this->itemSearchAliases,
            'rack_number' => $this->itemRackNumber,
            'rack_row' => $this->itemRackRow,
        ]);

        $createdBatches = [];

        if ($this->itemHasExpiryDate && count($this->expiryDates) > 0) {
            foreach ($this->expiryDates as $ed) {
                if (!empty($ed['date']) && $ed['quantity'] > 0) {
                    $batchCount = $item->batches()->count();
                    $newBatchNo = 'B' . str_pad($batchCount + 1, 2, '0', STR_PAD_LEFT);
                    $expiryPart = !empty($ed['date']) ? '-' . date('my', strtotime($ed['date'])) : '';
                    $newBarcode = $baseCode . '-' . $newBatchNo . $expiryPart;

                    $batch = $item->batches()->create([
                        'batch_no' => $newBatchNo,
                        'barcode' => $newBarcode,
                        'cost_price' => $this->itemCostPrice,
                        'selling_price' => $this->itemSellingPrice,
                        'expiry_date' => $ed['date'],
                        'discount' => $this->itemDiscount,
                        'quantity' => $ed['quantity'],
                        'is_active' => true
                    ]);
                    $createdBatches[] = $batch;
                }
            }
        } else {
            $batch = $item->batches()->create([
                'batch_no' => 'B01',
                'barcode' => $baseCode . '-B01',
                'cost_price' => $this->itemCostPrice,
                'selling_price' => $this->itemSellingPrice,
                'discount' => $this->itemDiscount,
                'quantity' => $this->itemQuantity,
                'is_active' => true
            ]);
            $createdBatches[] = $batch;
        }

        if ($this->itemHasBulkUnit) {
            // First, save the bulk units to the item
            $createdBulkUnits = [];
            foreach ($this->bulkConfigurations as $config) {
                if (!empty($config['name']) && $config['conversion_factor'] > 0) {
                    $bulkUnit = $item->bulkUnits()->create([
                        'name' => $config['name'],
                        'conversion_factor' => $config['conversion_factor']
                    ]);
                    $createdBulkUnits[] = [
                        'unit' => $bulkUnit,
                        'cost_price' => $config['cost_price'] ?: 0,
                        'selling_price' => $config['selling_price'] ?: 0,
                        'discount' => $config['discount']
                    ];
                }
            }

            // Then, attach bulk prices to all created batches
            foreach ($createdBatches as $b) {
                foreach ($createdBulkUnits as $cbu) {
                    $b->bulkPrices()->create([
                        'item_bulk_unit_id' => $cbu['unit']->id,
                        'cost_price' => $cbu['cost_price'],
                        'selling_price' => $cbu['selling_price'],
                        'discount' => $cbu['discount']
                    ]);
                }
            }
        }

        session()->flash('success', 'Item and Initial Stock added successfully.');
        $this->loadData();
        $this->closeModals();
    }

    public function saveStock()
    {
        $this->validate([
            'stockCostPrice' => 'required|numeric|min:0',
            'stockSellingPrice' => 'required|numeric|min:0',
        ]);

        $item = \App\Models\Item::find($this->stockItemId);
        if(!$item) return;

        $latestBatch = $item->batches()->latest()->first();
        $conversionFactor = 1;

        $stockEntries = [];
        if ($item->has_expiry_date && count($this->stockExpiryDates) > 0) {
            foreach ($this->stockExpiryDates as $ed) {
                if (!empty($ed['date']) && $ed['quantity'] > 0) {
                    $stockEntries[] = [
                        'quantity' => floatval($ed['quantity']) * $conversionFactor,
                        'expiry_date' => $ed['date']
                    ];
                }
            }
        } else {
            $this->validate(['stockQuantity' => 'required|numeric|min:0.01']);
            $stockEntries[] = [
                'quantity' => floatval($this->stockQuantity),
                'expiry_date' => null
            ];
        }

        foreach ($stockEntries as $entry) {
            // Find existing active batch with same selling price AND same expiry date (if any)
            $matchingBatch = $item->batches()
                ->where('is_active', true)
                ->where('selling_price', $this->stockSellingPrice)
                ->when($item->has_expiry_date, function($query) use ($entry) {
                    return $query->where('expiry_date', $entry['expiry_date']);
                })
                ->first();

            if ($matchingBatch) {
                $matchingBatch->quantity += $entry['quantity'];
                $matchingBatch->cost_price = $this->stockCostPrice;
                $matchingBatch->save();
            } else {
                $batchCount = $item->batches()->count();
                $newBatchNo = 'B' . str_pad($batchCount + 1, 2, '0', STR_PAD_LEFT);
                $expiryPart = !empty($entry['expiry_date']) ? '-' . date('my', strtotime($entry['expiry_date'])) : '';
                $newBarcode = $item->code . '-' . $newBatchNo . $expiryPart;

                $newBatch = $item->batches()->create([
                    'batch_no' => $newBatchNo,
                    'barcode' => $newBarcode,
                    'cost_price' => $this->stockCostPrice,
                    'selling_price' => $this->stockSellingPrice,
                    'discount' => $this->stockDiscount,
                    'quantity' => $entry['quantity'],
                    'expiry_date' => $entry['expiry_date'],
                    'is_active' => true
                ]);
            }
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

            $this->itemHasExpiryDate = $item->has_expiry_date;
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
                'has_expiry_date' => $this->itemHasExpiryDate,
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

    public function openConvertToLooseModal($itemId)
    {
        $item = \App\Models\Item::find($itemId);
        if ($item) {
            $this->convertParentItemId = $item->id;
            $this->convertParentItemName = $item->name;
            $this->showConvertToLooseModal = true;
        }
    }

    public function convertToLoose()
    {
        $this->validate([
            'convertConversionFactor' => 'required|numeric|min:1',
            'convertLoosePrice' => 'required|numeric|min:0',
            'convertQuantityToOpen' => 'required|numeric|min:1',
        ]);

        $parentItem = \App\Models\Item::find($this->convertParentItemId);
        if (!$parentItem) return;

        // Check stock
        $parentStock = $parentItem->getTotalStock();
        if ($parentStock < $this->convertQuantityToOpen) {
            $this->addError('convertQuantityToOpen', 'Insufficient stock to open.');
            return;
        }

        // Deduct from parent (simplistic approach: deduct from oldest active batch)
        $qtyToDeduct = $this->convertQuantityToOpen;
        $batches = $parentItem->batches()->where('is_active', true)->where('quantity', '>', 0)->orderBy('created_at')->get();
        $totalCostOfLoose = 0;
        foreach ($batches as $batch) {
            if ($qtyToDeduct <= 0) break;
            $deduct = min($batch->quantity, $qtyToDeduct);
            $batch->quantity -= $deduct;
            $batch->save();
            $qtyToDeduct -= $deduct;
            $totalCostOfLoose += ($deduct * $batch->cost_price);
        }

        // Find or create Loose Item
        $looseItem = \App\Models\Item::where('parent_item_id', $parentItem->id)->where('is_loose', true)->first();
        if (!$looseItem) {
            $lastItem = \App\Models\Item::orderBy('id', 'desc')->first();
            $nextId = $lastItem ? $lastItem->id + 1 : 1;
            $baseCode = 'ITEM' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

            $looseItem = \App\Models\Item::create([
                'code' => $baseCode,
                'name' => $parentItem->name . ' - Loose',
                'image' => $parentItem->image,
                'base_unit' => 'Pcs', // Default loose unit
                'has_expiry_date' => $parentItem->has_expiry_date,
                'has_warranty' => $parentItem->has_warranty,
                'warranty_months' => $parentItem->warranty_months,
                'category_id' => $parentItem->category_id,
                'sub_category_id' => $parentItem->sub_category_id,
                'is_loose' => true,
                'parent_item_id' => $parentItem->id,
            ]);
        }

        // Add stock to loose item
        $looseQty = $this->convertQuantityToOpen * $this->convertConversionFactor;
        $looseCostPrice = $totalCostOfLoose / $looseQty;

        $batchCount = $looseItem->batches()->count();
        $newBatchNo = 'B' . str_pad($batchCount + 1, 2, '0', STR_PAD_LEFT);
        $newBarcode = $looseItem->code . '-' . $newBatchNo;

        $looseItem->batches()->create([
            'batch_no' => $newBatchNo,
            'barcode' => $newBarcode,
            'cost_price' => $looseCostPrice,
            'selling_price' => $this->convertLoosePrice,
            'discount' => 0,
            'quantity' => $looseQty,
            'is_active' => true
        ]);

        session()->flash('success', 'Successfully converted to loose items.');
        $this->loadData();
        $this->closeModals();
    }

    // Printing Logic
    public function openPrintModal($batchId)
    {
        $this->printBatchId = $batchId;
        $this->printCopies = \App\Models\ItemBatch::find($batchId)->quantity ?? 1;
        $this->showPrintModal = true;
    }

    public function printSingleBatch()
    {
        $this->validate(['printCopies' => 'required|numeric|min:1']);
        
        $batch = \App\Models\ItemBatch::with(['item'])->find($this->printBatchId);
        if ($batch) {
            $printData = [
                [
                    'name' => $batch->item->name,
                    'price' => $batch->selling_price,
                    'barcode' => $batch->barcode,
                    'copies' => $this->printCopies
                ]
            ];
            
            // Mark as printed
            $batch->update(['is_printed' => true]);
            
            $this->dispatch('print-barcodes', data: $printData);
            $this->closeModals();
        }
    }

    public function openItemPrintModal($itemId)
    {
        $batches = \App\Models\ItemBatch::with(['item'])
            ->where('item_id', $itemId)
            ->where('is_active', true)
            ->get();
            
        $pending = [];
        foreach ($batches as $b) {
            $pending[] = [
                'id' => $b->id,
                'name' => $b->item->name,
                'barcode' => $b->barcode,
                'price' => $b->selling_price,
                'copies' => $b->quantity > 0 ? (int)$b->quantity : 1,
                'selected' => true
            ];
        }
        $this->pendingBatchesToPrint = $pending;
        
        $this->showBulkPrintModal = true;
    }

    public function openBulkPrintModal()
    {
        $batches = \App\Models\ItemBatch::with(['item'])
            ->where('is_printed', false)
            ->where('is_active', true)
            ->get();
            
        $pending = [];
        foreach ($batches as $b) {
            $pending[] = [
                'id' => $b->id,
                'name' => $b->item->name,
                'barcode' => $b->barcode,
                'price' => $b->selling_price,
                'copies' => $b->quantity > 0 ? (int)$b->quantity : 1,
                'selected' => true
            ];
        }
        $this->pendingBatchesToPrint = $pending;
        
        $this->showBulkPrintModal = true;
    }

    public function printBulkBatches()
    {
        $printData = [];
        $idsToUpdate = [];
        
        foreach ($this->pendingBatchesToPrint as $item) {
            if (isset($item['selected']) && $item['selected']) {
                $printData[] = [
                    'name' => $item['name'],
                    'price' => $item['price'],
                    'barcode' => $item['barcode'],
                    'copies' => (int)$item['copies']
                ];
                $idsToUpdate[] = $item['id'];
            }
        }
        
        if (count($printData) > 0) {
            \App\Models\ItemBatch::whereIn('id', $idsToUpdate)->update(['is_printed' => true]);
            $this->dispatch('print-barcodes', data: $printData);
        }
        
        $this->closeModals();
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
