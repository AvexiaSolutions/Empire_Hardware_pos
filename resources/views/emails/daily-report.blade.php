<!DOCTYPE html>
<html>
<head>
    <title>Daily End-of-Day Report</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; background-color: #f4f7f6; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        h1 { color: #2c3e50; text-align: center; border-bottom: 2px solid #3498db; padding-bottom: 15px; }
        h2 { color: #34495e; font-size: 18px; margin-top: 25px; border-bottom: 1px solid #eee; padding-bottom: 5px; }
        .stat-grid { display: flex; flex-wrap: wrap; gap: 15px; margin-top: 20px; }
        .stat-box { flex: 1; min-width: 120px; background: #f8f9fa; padding: 15px; border-radius: 6px; text-align: center; border: 1px solid #e9ecef; }
        .stat-title { font-size: 12px; color: #7f8c8d; text-transform: uppercase; font-weight: bold; }
        .stat-value { font-size: 20px; color: #2c3e50; font-weight: bold; margin-top: 5px; }
        .text-success { color: #27ae60 !important; }
        .text-danger { color: #e74c3c !important; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; font-size: 14px; }
        th, td { padding: 10px; border-bottom: 1px solid #eee; text-align: left; }
        th { background-color: #f8f9fa; color: #7f8c8d; font-weight: 600; }
        .footer { margin-top: 30px; text-align: center; font-size: 12px; color: #95a5a6; }
    </style>
</head>
<body>
    <div class="container">
        <h1>End-of-Day Report</h1>
        <p style="text-align: center; color: #7f8c8d; margin-top: -10px;">{{ date('l, F j, Y') }}</p>

        <!-- Tier 1: Sales & Invoices -->
        <h2>1. Sales Overview</h2>
        <div style="display: table; width: 100%; margin-top: 15px; table-layout: fixed;">
            <div style="display: table-cell; padding: 15px; background: #f8f9fa; border-radius: 6px; text-align: center; border: 1px solid #e9ecef;">
                <div class="stat-title">Total Sales</div>
                <div class="stat-value">Rs. {{ number_format($reportData['total_sales'], 2) }}</div>
            </div>
            <div style="display: table-cell; padding: 15px; background: #f8f9fa; border-radius: 6px; text-align: center; border: 1px solid #e9ecef; margin-left: 10px;">
                <div class="stat-title">Discounts</div>
                <div class="stat-value">Rs. {{ number_format($reportData['total_discounts'], 2) }}</div>
            </div>
            <div style="display: table-cell; padding: 15px; background: #f8f9fa; border-radius: 6px; text-align: center; border: 1px solid #e9ecef; margin-left: 10px;">
                <div class="stat-title">Invoices</div>
                <div class="stat-value">{{ $reportData['invoice_count'] }}</div>
            </div>
        </div>

        <!-- Tier 2: Net Profit -->
        <h2>2. Financial Summary</h2>
        <div style="background: #e8f5e9; padding: 20px; border-radius: 6px; text-align: center; border: 1px solid #c8e6c9;">
            <div style="font-size: 14px; color: #2e7d32; text-transform: uppercase; font-weight: bold;">Estimated Net Profit</div>
            <div style="font-size: 28px; color: #1b5e20; font-weight: bold; margin-top: 5px;">Rs. {{ number_format($reportData['net_profit'], 2) }}</div>
            <div style="font-size: 12px; color: #4caf50; margin-top: 5px;">(Total Sales - Expenses)</div>
        </div>

        <!-- Tier 3: Items Data -->
        <h2>3. Item Analytics</h2>
        
        <h3 style="font-size: 15px; color: #34495e; margin-bottom: 10px;">Best-Selling Items (Today)</h3>
        @if(count($reportData['best_sellers']) > 0)
        <table>
            <thead>
                <tr>
                    <th>Item Name</th>
                    <th style="text-align: right;">Qty Sold</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reportData['best_sellers'] as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td style="text-align: right; font-weight: bold;">{{ $item->total_sold }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p style="color: #7f8c8d; font-size: 14px;">No items sold today.</p>
        @endif

        <h3 style="font-size: 15px; color: #34495e; margin-top: 25px; margin-bottom: 10px;">Low Stock Alerts (Total Qty <= 50)</h3>
        @if(count($reportData['low_stock_items']) > 0)
        <table>
            <thead>
                <tr>
                    <th>Item Code</th>
                    <th>Item Name</th>
                    <th style="text-align: right;">Current Stock</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reportData['low_stock_items'] as $item)
                <tr>
                    <td>{{ $item->code }}</td>
                    <td>{{ $item->name }}</td>
                    <td style="text-align: right; color: #e74c3c; font-weight: bold;">{{ $item->batches_sum_quantity ?? 0 }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p style="color: #27ae60; font-size: 14px; font-weight: bold;">All items have sufficient stock!</p>
        @endif

        <div class="footer">
            <p>This is an automated message generated by the Avexia POS System.</p>
        </div>
    </div>
</body>
</html>
