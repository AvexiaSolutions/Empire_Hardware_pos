<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        if ($user->role === 'admin') {
            return redirect()->route('dashboard');
        }
        
        $permissions = [
            'pos.access' => 'pos.index',
            'invoice.access' => 'invoice.index',
            'item.access' => 'item.index',
            'returns.access' => 'warranty-returns.index',
            'supplier.access' => 'supplier.index',
            'account.access' => 'account',
            'expenses.access' => 'expenses.index',
            'employer.access' => 'employer.index',
            'credit-cheque.access' => 'credit-cheque.index',
            'settings.access' => 'settings.index',
        ];

        foreach ($permissions as $perm => $route) {
            if ($user->hasPermission($perm)) {
                return redirect()->route($route);
            }
        }
        
        return redirect()->route('no-access');
    }
    return redirect('/login');
});

Route::view('/no-access', 'errors.no-access')->name('no-access')->middleware(['auth']);

Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    // POS Route (Shared)
    Route::get('pos', \App\Livewire\Pos\Index::class)->name('pos.index');
    Route::get('customer-display', \App\Livewire\Pos\CustomerDisplay::class)->name('customer-display');

    // Invoice Routes (Shared)
    Route::get('invoice', \App\Livewire\InvoiceManagement::class)->name('invoice.index');
    Route::get('invoice/create', \App\Livewire\InvoiceCreate::class)->name('invoice.create');
    Route::get('invoice/{id}/print', [\App\Http\Controllers\InvoiceController::class, 'print'])->name('invoice.print');
    
    // Warranty & Returns
    Route::get('warranty-returns', \App\Livewire\WarrantyReturn\Index::class)->name('warranty-returns.index');

    // Item Routes (Shared)
    Route::get('item', \App\Livewire\ItemManagement::class)->name('item.index');
    Route::view('item/create', 'item.create')->name('item.create');
    Route::view('item/stock/create', 'item.stock.create')->name('item.stock.create');
    Route::view('item/stock/show', 'item.stock.show')->name('item.stock.show');
    Route::view('category/create', 'category.create')->name('category.create');
    Route::view('subcategory/create', 'subcategory.create')->name('subcategory.create');

    // Language Switcher (Shared)
    Route::get('lang/{lang}', [App\Http\Controllers\LanguageController::class, 'switchLang'])->name('lang.switch');
});

// Admin Only Routes
Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::get('/dashboard', \App\Livewire\Dashboard\Index::class)->middleware(['auth', 'verified'])->name('dashboard');
    Route::get('account', \App\Livewire\Account\Index::class)->name('account');
    Route::get('expenses', \App\Livewire\ExpenseManagement::class)->name('expenses.index');

    // Reports
    Route::get('reports/pnl', [\App\Http\Controllers\ReportController::class, 'pnlReport'])->name('report.pnl');
    Route::get('reports/stock', [\App\Http\Controllers\ReportController::class, 'stockReport'])->name('report.stock');

    // Supplier Routes
    Route::get('supplier', \App\Livewire\SupplierManagement::class)->name('supplier.index');

    // Employer Routes
    Route::prefix('employer')->name('employer.')->group(function () {
        Route::get('/', \App\Livewire\Employer\Index::class)->name('index');
        Route::get('/paysheet/select', \App\Livewire\Employer\Paysheet\Select::class)->name('paysheet.select');
        Route::get('/paysheet/show', \App\Livewire\Employer\Paysheet\Show::class)->name('paysheet.show');
    });

    // Credit & Cheque Routes
    Route::get('credit-cheque', \App\Livewire\CreditChequeManagement::class)->name('credit-cheque.index');

    // Activity Logs
    Route::get('/activity-logs', [App\Http\Controllers\ActivityLogController::class, 'index'])->name('activity-logs.index');

    // AI Assistant
    Route::get('/ai-assistant', \App\Livewire\Dashboard\AiAssistant::class)->name('ai.assistant');

    // Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [App\Http\Controllers\SettingController::class, 'index'])->name('index');
        Route::post('/update', [App\Http\Controllers\SettingController::class, 'update'])->name('update');
        Route::post('/system-update', [App\Http\Controllers\SettingController::class, 'systemUpdate'])->name('system-update');
        Route::post('/users', [App\Http\Controllers\SettingController::class, 'storeUser'])->name('users.store');
        Route::put('/users/{id}', [App\Http\Controllers\SettingController::class, 'updateUser'])->name('users.update');
        Route::put('/users/{id}/permissions', [App\Http\Controllers\SettingController::class, 'updateUserPermissions'])->name('users.permissions.update');
        Route::get('/manual-backup', [App\Http\Controllers\SettingController::class, 'manualBackup'])->name('manual-backup');
    });
});

require __DIR__.'/auth.php';
