<!DOCTYPE html>
<html>
<head>
    <title>Low Stock Alert</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <h2>Low Stock Alert</h2>
    <p>The following items are running low on stock (Quantity <= 50):</p>
    
    <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
        <thead>
            <tr style="background-color: #f8f9fa;">
                <th style="padding: 10px; border: 1px solid #ddd; text-align: left;">Item Code</th>
                <th style="padding: 10px; border: 1px solid #ddd; text-align: left;">Item Name</th>
                <th style="padding: 10px; border: 1px solid #ddd; text-align: right;">Current Stock</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
            <tr>
                <td style="padding: 10px; border: 1px solid #ddd;">{{ $item->code }}</td>
                <td style="padding: 10px; border: 1px solid #ddd;">{{ $item->name }}</td>
                <td style="padding: 10px; border: 1px solid #ddd; text-align: right; color: red; font-weight: bold;">
                    {{ $item->batches_sum_quantity ?? 0 }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <p style="margin-top: 30px;">
        Please log in to the POS System Dashboard to reorder stock.
    </p>
</body>
</html>
