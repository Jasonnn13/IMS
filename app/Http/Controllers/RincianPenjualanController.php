<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\Stock;
use Barryvdh\DomPDF\Facade\Pdf; // Import the PDF facade

use App\Models\RincianPenjualan;
use Illuminate\Http\Request;

class RincianPenjualanController extends Controller
{
    public function index($penjualan_id)
    {
        $penjualan = Penjualan::findOrFail($penjualan_id);
        $rincianpenjualans = RincianPenjualan::where('penjualan_id', $penjualan_id)->get();
        return view('rincianpenjualan.index', compact('penjualan', 'rincianpenjualans'));
    }

    public function create($penjualan_id)
    {
        $penjualan = Penjualan::findOrFail($penjualan_id);
        $stocks = Stock::all();
        return view('rincianpenjualan.create', compact('penjualan', 'stocks'));
    }

    public function store(Request $request)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'penjualan_id' => 'required|exists:penjualan,id',
            'items' => 'required|array',
            'items.*.name' => 'required|string|exists:stocks,name',
            'items.*.quantity' => 'required|integer|min:1',
        ]);
    
        $penjualanId = $validated['penjualan_id'];
        $items = $validated['items'];
    
        $totalAmount = 0;
        $insufficientStockItems = [];
    
        foreach ($items as $item) {
            $stock = Stock::where('name', $item['name'])->first();
    
            // Check if the stock quantity is sufficient
            if ($stock->stock < $item['quantity']) {
                $insufficientStockItems[] = [
                    'stock_name' => $stock->name,
                    'required_quantity' => $item['quantity'],
                    'available_quantity' => $stock->stock
                ];
                continue;
            }
    
            $price = $stock->jual;
    
            // Create RincianPenjualan record
            RincianPenjualan::create([
                'penjualan_id' => $penjualanId,
                'stocks_id' => $stock->id,
                'quantity' => $item['quantity'],
                'price' => $price,
                'total' => $price * $item['quantity'],
            ]);
    
            // Decrement stock quantity
            $stock->stock -= $item['quantity'];
            $stock->save();
    
            $totalAmount += $item['quantity'] * $price;
        }
    
        // Handle insufficient stock items
        if (!empty($insufficientStockItems)) {
            $errorMessages = [];
            foreach ($insufficientStockItems as $item) {
                $errorMessages[] = "{$item['stock_name']}: Required {$item['required_quantity']} but only {$item['available_quantity']} available.";
            }
            return redirect()->back()->withErrors(['items' => $errorMessages])->withInput();
        }
    
        // Update the total for the penjualan
        $penjualan = Penjualan::findOrFail($penjualanId);
        $penjualan->total = $totalAmount;
        $penjualan->total_netto = $totalAmount - (($penjualan->diskon/100) * $totalAmount);
        $dpp = $penjualan->total_netto / 1.11;
        $ppn = $penjualan->total_netto - $dpp;
        $penjualan->dpp = $dpp;
        $penjualan->ppn = $ppn;
        $penjualan->save();
    
        return redirect()->route('penjualan.index')->with('success', 'Items added and updated successfully');
    }
    
    
    
    public function edit($id)
    {
        $rincianpenjualan = RincianPenjualan::findOrFail($id);
        $penjualan = Penjualan::findOrFail($rincianpenjualan->penjualan_id);
        $stocks = Stock::all();
        return view('rincianpenjualan.edit', compact('rincianpenjualan', 'stocks', 'penjualan'));
    }

    public function update(Request $request, $id)
    {
        $rincianpenjualan = RincianPenjualan::findOrFail($id);
        $stock = Stock::findOrFail($rincianpenjualan->stocks_id);
        $penjualan = Penjualan::findOrFail($rincianpenjualan->penjualan_id);
    
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);
    
        $oldQuantity = $rincianpenjualan->quantity;
        $quantity = $validated['quantity'];
        $price = $stock->jual;
    
        // Check if the new quantity is valid
        if ($stock->stock + $oldQuantity < $quantity) {
            return redirect()->back()->withErrors([
                'message' => 'The quantity you are trying to set exceeds available stock.'
            ])->withInput();
        }
    
        $rincianpenjualan->quantity = $quantity;
        $rincianpenjualan->price = $price;
        $rincianpenjualan->total = $quantity * $price;
        $rincianpenjualan->save();
    
        // Update stock quantities
        $stock->stock += ($oldQuantity - $quantity);
        $stock->save();
    
        // Recalculate totals for Penjualan
        $penjualan->total = $penjualan->rincianpenjualans()->sum('total');
        $penjualan->total_netto = $penjualan->rincianpenjualans()->sum('total') - (($penjualan->diskon/100) * $penjualan->total);
        $dpp = $penjualan->total_netto/1.11;
        $ppn = $penjualan->total_netto - $dpp;
        $penjualan->dpp = $dpp;
        $penjualan->ppn = $ppn;
        
        $penjualan->save();

        return redirect()->route('rincianpenjualan.index', ['penjualan_id' => $rincianpenjualan->penjualan_id])->with('success', 'Rincian penjualan updated successfully.');
    }
    
    

    public function destroy($id)
    {
        $rincianpenjualan = RincianPenjualan::findOrFail($id);
        $stock = Stock::findOrFail($rincianpenjualan->stocks_id);
        $penjualan = Penjualan::findOrFail($rincianpenjualan->penjualan_id);


        $stock->stock += $rincianpenjualan->quantity;
        $stock->save();

        $rincianpenjualan->delete();

        $penjualan->total = $penjualan->rincianpenjualans()->sum('total');
        $penjualan->total_netto = $penjualan->rincianpenjualans()->sum('total') - (($penjualan->diskon/100) * $penjualan->total);
        $dpp = $penjualan->total_netto/1.11;
        $ppn = $penjualan->total_netto - $dpp;
        $penjualan->dpp = $dpp;
        $penjualan->ppn = $ppn;
        $penjualan->save();



        return redirect()->route('rincianpenjualan.index', ['penjualan_id' => $rincianpenjualan->penjualan_id])->with('success', 'Rincian penjualan deleted successfully.');
    }

    public function invoice($penjualan_id)
    {
        return view('rincianpenjualan.invoice', ['penjualan' => Penjualan::findOrFail($penjualan_id)]);
    }
    
}
