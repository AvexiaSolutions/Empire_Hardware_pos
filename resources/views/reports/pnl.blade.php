<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profit and Loss Report</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; color: #333; margin: 0; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #ddd; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 24px; color: #2563eb; }
        .header p { margin: 5px 0; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #eee; }
        th { background-color: #f8fafc; font-weight: bold; color: #475569; }
        .text-right { text-align: right; }
        .summary-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 15px; margin-bottom: 20px; }
        .summary-row { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 16px; }
        .summary-row.total { font-weight: bold; font-size: 18px; border-top: 2px solid #cbd5e1; padding-top: 10px; }
        .profit { color: #16a34a; }
        .loss { color: #dc2626; }
        .section-title { font-size: 18px; font-weight: bold; margin-bottom: 10px; color: #1e293b; border-left: 4px solid #2563eb; padding-left: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Empire Hardware & POS</h1>
        <p>Profit and Loss Report</p>
        <p><strong>Period:</strong> {{ $startStr }} to {{ $endStr }}</p>
    </div>

    <div class="summary-box">
        <table style="border: none; margin-bottom: 0;">
            <tr style="border: none;">
                <td style="border: none; padding: 5px 0;">Total Revenue (Income):</td>
                <td style="border: none; padding: 5px 0;" class="text-right">Rs. {{ number_format($monthlyIncome, 2) }}</td>
            </tr>
            <tr style="border: none;">
                <td style="border: none; padding: 5px 0;">Total Expenses:</td>
                <td style="border: none; padding: 5px 0;" class="text-right">Rs. {{ number_format($monthlyExpenses, 2) }}</td>
            </tr>
            <tr style="border: none;">
                <td style="border: none; padding: 15px 0 5px 0; font-weight: bold; font-size: 18px; border-top: 2px solid #cbd5e1;">Net {{ $companyLoss > 0 ? 'Loss' : 'Profit' }}:</td>
                <td style="border: none; padding: 15px 0 5px 0; font-weight: bold; font-size: 18px; border-top: 2px solid #cbd5e1;" class="text-right {{ $companyLoss > 0 ? 'loss' : 'profit' }}">
                    Rs. {{ number_format(max($companyProfit, $companyLoss), 2) }}
                </td>
            </tr>
        </table>
    </div>

    <div class="section-title">Revenue Details (Invoices)</div>
    <table>
        <thead>
            <tr>
                <th>Invoice No</th>
                <th>Date</th>
                <th>Customer</th>
                <th class="text-right">Total Amount (Rs.)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoices as $inv)
            <tr>
                <td>{{ $inv->invoice_number }}</td>
                <td>{{ $inv->created_at->format('Y-m-d') }}</td>
                <td>{{ $inv->customer_name ?? 'Walk-in' }}</td>
                <td class="text-right">{{ number_format($inv->total, 2) }}</td>
            </tr>
            @endforeach
            @if($invoices->count() == 0)
            <tr><td colspan="4" style="text-align: center; color: #999;">No invoices in this period.</td></tr>
            @endif
        </tbody>
    </table>

    <div style="page-break-before: always;"></div>

    <div class="section-title">Expense Details</div>
    <table>
        <thead>
            <tr>
                <th>Type</th>
                <th>Description / Payee</th>
                <th>Date / Month</th>
                <th class="text-right">Amount (Rs.)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($expensesList as $exp)
            <tr>
                <td>General Expense</td>
                <td>{{ $exp->description }}</td>
                <td>{{ \Carbon\Carbon::parse($exp->date)->format('Y-m-d') }}</td>
                <td class="text-right">{{ number_format($exp->amount, 2) }}</td>
            </tr>
            @endforeach
            @foreach($paysheetsList as $pay)
            <tr>
                <td>Salary (Paysheet)</td>
                <td>{{ $pay->employee->name ?? 'Unknown' }}</td>
                <td>{{ $pay->month_year }}</td>
                <td class="text-right">{{ number_format($pay->net_salary, 2) }}</td>
            </tr>
            @endforeach
            @if($expensesList->count() == 0 && $paysheetsList->count() == 0)
            <tr><td colspan="4" style="text-align: center; color: #999;">No expenses in this period.</td></tr>
            @endif
        </tbody>
    </table>
</body>
</html>
