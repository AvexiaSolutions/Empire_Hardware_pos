<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Invoice;
use App\Models\Expense;
use App\Models\Paysheet;
use App\Models\Cheque;
use App\Models\Credit;
use App\Models\ItemBatch;
use App\Models\ReturnLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function pnlReport(Request $request)
    {
        $startStr = $request->query('start', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endStr = $request->query('end', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $start = Carbon::parse($startStr)->startOfDay();
        $end = Carbon::parse($endStr)->endOfDay();

        $invoices = Invoice::whereBetween('created_at', [$start, $end])->orderBy('created_at', 'desc')->get();
        $monthlyIncome = $invoices->sum('total');

        $expensesList = Expense::whereBetween('date', [$start->format('Y-m-d'), $end->format('Y-m-d')])
                               ->orderBy('date', 'desc')->get();

        $startMonthStr = $start->format('Y-m'); 
        $endMonthStr = $end->format('Y-m');

        $paysheetsList = Paysheet::with('employee')
                                 ->whereBetween('month_year', [$startMonthStr, $endMonthStr])
                                 ->get();
                                       
        $monthlyExpenses = $expensesList->sum('amount') + $paysheetsList->sum('net_salary');
        
        $returnLogs = ReturnLog::with(['invoiceItem', 'itemBatch'])
            ->whereBetween('date', [$start->format('Y-m-d'), $end->format('Y-m-d')])
            ->get();

        $returnDeductions = 0;
        $damageExpenses = 0;

        foreach ($returnLogs as $log) {
            if ($log->type === 'change' || $log->type === 'damage') {
                $refundAmount = $log->quantity * ($log->invoiceItem->unit_price ?? 0);
                $returnDeductions += $refundAmount;
            }
            if ($log->type === 'damage') {
                $costPrice = $log->itemBatch ? $log->itemBatch->getRawOriginal('cost_price') : 0;
                $damageExpenses += ($log->quantity * $costPrice);
            }
        }

        $monthlyIncome -= $returnDeductions;
        $monthlyExpenses += $damageExpenses;

        $net = $monthlyIncome - $monthlyExpenses;
        $companyProfit = $net >= 0 ? $net : 0;
        $companyLoss = $net < 0 ? abs($net) : 0;

        $pdf = Pdf::loadView('reports.pnl', compact(
            'startStr', 'endStr', 'invoices', 'monthlyIncome', 
            'expensesList', 'paysheetsList', 'monthlyExpenses', 
            'companyProfit', 'companyLoss', 'returnDeductions', 'damageExpenses'
        ));

        return $pdf->stream('pnl-report-'.$startStr.'-to-'.$endStr.'.pdf');
    }

    public function stockReport()
    {
        $stockData = ItemBatch::where('is_active', true)
            ->where('quantity', '>', 0)
            ->select(
                DB::raw('SUM(quantity * cost_price) as total_cost'),
                DB::raw('SUM(quantity * selling_price) as total_value')
            )->first();

        $totalStockCost = $stockData->total_cost ?? 0;
        $totalStockValue = $stockData->total_value ?? 0;
        $expectedProfit = $totalStockValue - $totalStockCost;

        $batches = ItemBatch::with('item')->where('is_active', true)->where('quantity', '>', 0)->get();

        $pdf = Pdf::loadView('reports.stock', compact(
            'totalStockCost', 'totalStockValue', 'expectedProfit', 'batches'
        ));

        // Let's use landscape for stock report to fit all columns
        $pdf->setPaper('a4', 'landscape');

        return $pdf->stream('stock-valuation-report.pdf');
    }
}
