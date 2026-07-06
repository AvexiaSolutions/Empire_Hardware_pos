<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Setting;

class InvoiceController extends Controller
{
    public function print($id)
    {
        $invoice = Invoice::with(['items.item', 'items.itemBatch'])->findOrFail($id);
        $settings = Setting::getCachedAll();
        
        return view('invoice.print', compact('invoice', 'settings'));
    }
}
