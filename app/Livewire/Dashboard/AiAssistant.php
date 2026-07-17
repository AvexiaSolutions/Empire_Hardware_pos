<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\InvoiceItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AiAssistant extends Component
{
    public $messages = [];
    public $userInput = '';
    public $isLoading = false;

    public function mount()
    {
        $this->messages[] = [
            'role' => 'model',
            'content' => 'ආයුබෝවන්! මම Empire POS හි AI සහායකයා. ඔබට අලෙවිය, භාණ්ඩ හෝ වාර්තා ගැන ඕනෑම ප්‍රශ්නයක් ඇසිය හැක. (Hello! I am the Empire POS AI Assistant. Ask me anything about sales, items, or reports.)'
        ];
    }

    private function getBusinessContext()
    {
        // Gather summary data for the context
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $totalSalesMonth = Invoice::whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->sum('total');

        $totalInvoicesMonth = Invoice::whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->count();

        $topItems = InvoiceItem::join('invoices', 'invoice_items.invoice_id', '=', 'invoices.id')
            ->join('items', 'invoice_items.item_id', '=', 'items.id')
            ->whereMonth('invoices.date', $currentMonth)
            ->whereYear('invoices.date', $currentYear)
            ->select('items.name', DB::raw('SUM(invoice_items.quantity) as qty'))
            ->groupBy('items.id', 'items.name')
            ->orderBy('qty', 'desc')
            ->take(5)
            ->get()
            ->map(fn($i) => $i->name . ' (' . $i->qty . ')')
            ->implode(', ');

        $lowStockItems = Item::withSum('batches', 'quantity')
            ->having('batches_sum_quantity', '<=', 50)
            ->take(5)
            ->get()
            ->map(fn($i) => $i->name . ' (' . $i->batches_sum_quantity . ')')
            ->implode(', ');

        return "You are an AI assistant for a retail shop named 'Empire POS'. Answer concisely and accurately in Sinhala or English depending on the user's language. 
Here is the current real-time data for this month (" . Carbon::now()->format('F Y') . "):
- Total Sales: Rs. {$totalSalesMonth}
- Total Invoices: {$totalInvoicesMonth}
- Top Selling Items: {$topItems}
- Low Stock Items: {$lowStockItems}
Provide helpful insights based on this data.";
    }

    public function sendMessage()
    {
        $this->validate(['userInput' => 'required|string']);

        $prompt = $this->userInput;
        
        $this->messages[] = [
            'role' => 'user',
            'content' => $prompt
        ];
        
        $this->userInput = '';
        $this->isLoading = true;

        try {
            $apiKey = env('GEMINI_API_KEY');
            if (!$apiKey) {
                throw new \Exception("Gemini API key is not configured.");
            }

            $context = $this->getBusinessContext();
            
            // Format messages for Gemini API
            $contents = [];
            
            // Add system context as a user message since Gemini API might not support 'system' role in this endpoint
            $contents[] = [
                'role' => 'user',
                'parts' => [['text' => $context]]
            ];
            $contents[] = [
                'role' => 'model',
                'parts' => [['text' => 'Understood. I am ready to help.']]
            ];

            foreach (array_slice($this->messages, 1) as $msg) { // Skip the first greeting message
                $contents[] = [
                    'role' => $msg['role'],
                    'parts' => [['text' => $msg['content']]]
                ];
            }

            $response = Http::withoutVerifying()->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-3.5-flash:generateContent?key={$apiKey}", [
                'contents' => $contents,
                'generationConfig' => [
                    'temperature' => 0.7,
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $reply = $data['candidates'][0]['content']['parts'][0]['text'] ?? "Sorry, I couldn't generate a response.";
                
                $this->messages[] = [
                    'role' => 'model',
                    'content' => $reply
                ];
            } else {
                $this->messages[] = [
                    'role' => 'model',
                    'content' => "API Error: " . $response->body()
                ];
            }
        } catch (\Exception $e) {
            $this->messages[] = [
                'role' => 'model',
                'content' => "Error: " . $e->getMessage()
            ];
        }

        $this->isLoading = false;
    }

    public function render()
    {
        return view('livewire.dashboard.ai-assistant');
    }
}
