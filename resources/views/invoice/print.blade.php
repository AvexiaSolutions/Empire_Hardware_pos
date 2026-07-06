<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $invoice->invoice_no }}</title>
    <style>
        @page {
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Courier New', Courier, monospace; /* Classic thermal font */
            font-size: 12px;
            color: #000;
            margin: 0;
            padding: 10px;
            background: #f8f9fa;
        }
        /* Thermal Printer Wrapper */
        .thermal-wrapper {
            width: {{ ($settings['printer_type'] ?? '80mm') === '58mm' ? '58mm' : (($settings['printer_type'] ?? '80mm') === '80mm' ? '80mm' : '100%') }};
            max-width: {{ ($settings['printer_type'] ?? '80mm') === 'A4' ? '800px' : '100%' }};
            margin: 0 auto;
            background: #fff;
            padding: 10px;
            box-sizing: border-box;
        }
        @media print {
            body { background: transparent; padding: 0; }
            .thermal-wrapper { padding: 0; box-shadow: none; border: none; margin: 0; width: 100%; max-width: 100%; }
            .no-print { display: none !important; }
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .fw-bold { font-weight: bold; }
        .mb-1 { margin-bottom: 5px; }
        .mb-2 { margin-bottom: 10px; }
        .mb-3 { margin-bottom: 15px; }
        .mt-1 { margin-top: 5px; }
        .mt-2 { margin-top: 10px; }
        .p-0 { padding: 0; }
        
        .divider { border-bottom: 1px dashed #000; margin: 10px 0; }
        .solid-divider { border-bottom: 1px solid #000; margin: 10px 0; }
        
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 4px 0; vertical-align: top; }
        th { border-bottom: 1px dashed #000; border-top: 1px dashed #000; text-align: left; }
        
        /* A4 specific overrides if needed */
        .a4-mode { font-family: Arial, sans-serif; font-size: 14px; padding: 30px; }
        .a4-mode table th, .a4-mode table td { padding: 8px; border-bottom: 1px solid #eee; }
        .a4-mode table th { border-top: 2px solid #000; border-bottom: 2px solid #000; }
        .a4-mode .divider { display: none; }
    </style>
</head>
<body>

    <div class="no-print text-center mb-3">
        <button onclick="window.print()" style="padding: 10px 20px; font-size: 16px; cursor: pointer; background: #0D8ABC; color: #fff; border: none; border-radius: 5px;">Print Receipt</button>
        <a href="{{ route('pos.index') }}" style="display: inline-block; padding: 10px 20px; font-size: 16px; margin-left: 10px; text-decoration: none; color: #333; border: 1px solid #ccc; border-radius: 5px;">Back to POS</a>
    </div>

    <div class="thermal-wrapper {{ ($settings['printer_type'] ?? '') === 'A4' ? 'a4-mode shadow' : '' }}">
        
        <!-- Header -->
        <div class="text-center mb-2">
            @if(!empty($settings['shop_logo']))
                <img src="{{ $settings['shop_logo'] }}" style="max-height: 60px; max-width: 100%; margin-bottom: 5px;">
            @endif
            <div class="fw-bold" style="font-size: 1.2em;">{{ $settings['shop_name'] ?? 'Avexia POS' }}</div>
            <div>{{ $settings['shop_address'] ?? '' }}</div>
            <div>Tel: {{ $settings['shop_phone'] ?? '' }}</div>
            @if(!empty($settings['tax_no']))
                <div>VAT/BR: {{ $settings['tax_no'] }}</div>
            @endif
        </div>

        <div class="divider"></div>

        <!-- Meta info -->
        <table style="width: 100%; margin-bottom: 5px;">
            <tr>
                <td class="text-left">No: <span class="fw-bold">{{ $invoice->invoice_no }}</span></td>
                <td class="text-right">Date: {{ \Carbon\Carbon::parse($invoice->date)->format('Y-m-d H:i') }}</td>
            </tr>
            <tr>
                <td class="text-left">Cashier: {{ $invoice->user ? $invoice->user->name : 'Admin' }}</td>
                <td class="text-right">Type: {{ ucfirst($invoice->type) }}</td>
            </tr>
            @if(!empty($invoice->customer_name))
            <tr>
                <td colspan="2" class="text-left">Customer: {{ $invoice->customer_name }}</td>
            </tr>
            @endif
        </table>

        <!-- Items Table -->
        <table>
            <thead>
                <tr>
                    <th width="50%">Item</th>
                    <th width="15%" class="text-center">Qty</th>
                    <th width="15%" class="text-right">Price</th>
                    <th width="20%" class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $item)
                <tr>
                    <td>{{ $item->item->name }}</td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right">{{ number_format($item->unit_price, 2) }}</td>
                    <td class="text-right">{{ number_format($item->total, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="divider"></div>

        <!-- Totals -->
        <table style="width: 100%;">
            <tr>
                <td width="50%"></td>
                <td width="30%" class="text-right">Sub Total:</td>
                <td width="20%" class="text-right">{{ number_format($invoice->sub_total, 2) }}</td>
            </tr>
            @if($invoice->bill_discount > 0)
            <tr>
                <td width="50%"></td>
                <td width="30%" class="text-right">Discount:</td>
                <td width="20%" class="text-right">-{{ number_format($invoice->bill_discount, 2) }}</td>
            </tr>
            @endif
            <tr>
                <td width="50%"></td>
                <td width="30%" class="text-right fw-bold" style="font-size: 1.2em;">NET TOTAL:</td>
                <td width="20%" class="text-right fw-bold" style="font-size: 1.2em;">{{ number_format($invoice->total, 2) }}</td>
            </tr>
            <tr><td colspan="3"><div class="divider"></div></td></tr>
            <tr>
                <td width="50%"></td>
                <td width="30%" class="text-right">Tendered:</td>
                <td width="20%" class="text-right">{{ number_format($invoice->tendered_amount, 2) }}</td>
            </tr>
            <tr>
                <td width="50%"></td>
                <td width="30%" class="text-right fw-bold">Balance:</td>
                <td width="20%" class="text-right fw-bold">{{ number_format(abs($invoice->balance_amount), 2) }}</td>
            </tr>
        </table>

        <div class="divider"></div>

        <!-- Footer -->
        <div class="text-center mt-2 mb-2">
            {!! nl2br(e($settings['bill_footer'] ?? 'Thank you for shopping with us!')) !!}
            <br><br>
            <small style="font-size: 0.8em;">Software by Avexia</small>
        </div>

    </div>
</body>
</html>
