<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Stock Valuation Report</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 13px; color: #333; margin: 0; padding: 20px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #ddd; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 24px; color: #2563eb; }
        .header p { margin: 5px 0; color: #666; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 12px; }
        th, td { padding: 8px; text-align: left; border-bottom: 1px solid #eee; }
        th { background-color: #f8fafc; font-weight: bold; color: #475569; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .summary-box { background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 15px; margin-bottom: 20px; display: table; width: 100%; box-sizing: border-box; }
        .summary-col { display: table-cell; width: 33.33%; text-align: center; padding: 10px; border-right: 1px solid #bbf7d0; }
        .summary-col:last-child { border-right: none; }
        .summary-col h3 { margin: 0 0 5px 0; font-size: 14px; color: #166534; }
        .summary-col p { margin: 0; font-size: 18px; font-weight: bold; color: #14532d; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Empire Hardware & POS</h1>
        <p>Comprehensive Stock Valuation Report</p>
        <p><strong>Generated on:</strong> {{ \Carbon\Carbon::now()->format('Y-m-d h:i A') }}</p>
    </div>

    <div class="summary-box">
        <div class="summary-col">
            <h3>Total Stock Cost Value</h3>
            <p>Rs. {{ number_format($totalStockCost, 2) }}</p>
        </div>
        <div class="summary-col">
            <h3>Total Stock Selling Value</h3>
            <p>Rs. {{ number_format($totalStockValue, 2) }}</p>
        </div>
        <div class="summary-col">
            <h3>Expected Profit</h3>
            <p>Rs. {{ number_format($expectedProfit, 2) }}</p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Item Code</th>
                <th>Item Name</th>
                <th>Batch No</th>
                <th class="text-right">Qty</th>
                <th class="text-right">Cost Price</th>
                <th class="text-right">Total Cost</th>
                <th class="text-right">Selling Price</th>
                <th class="text-right">Total Value</th>
                <th class="text-right">Exp. Profit</th>
            </tr>
        </thead>
        <tbody>
            @foreach($batches as $batch)
            @php
                $itemTotalCost = $batch->quantity * $batch->cost_price;
                $itemTotalValue = $batch->quantity * $batch->selling_price;
                $itemExpProfit = $itemTotalValue - $itemTotalCost;
            @endphp
            <tr>
                <td>{{ $batch->item->item_code ?? 'N/A' }}</td>
                <td>{{ $batch->item->name ?? 'Unknown Item' }}</td>
                <td>{{ $batch->batch_no }}</td>
                <td class="text-right">{{ $batch->quantity }}</td>
                <td class="text-right">{{ number_format($batch->cost_price, 2) }}</td>
                <td class="text-right">{{ number_format($itemTotalCost, 2) }}</td>
                <td class="text-right">{{ number_format($batch->selling_price, 2) }}</td>
                <td class="text-right">{{ number_format($itemTotalValue, 2) }}</td>
                <td class="text-right">{{ number_format($itemExpProfit, 2) }}</td>
            </tr>
            @endforeach
            @if($batches->count() == 0)
            <tr><td colspan="9" class="text-center" style="color: #999;">No active stock available.</td></tr>
            @endif
        </tbody>
    </table>
</body>
</html>
