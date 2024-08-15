<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\Ekonomi;
use App\Models\Stock;
use App\Models\RincianPembelian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;  // For logging

class RincianPembelianController extends Controller
{
    public function index($pembelian_id)
    {
        $pembelian = Pembelian::findOrFail($pembelian_id);
        $rincianpembelians = RincianPembelian::where('pembelian_id', $pembelian_id)->get();
        return view('rincianpembelian.index', compact('pembelian', 'rincianpembelians'));
    }


    public function create($pembelian_id)
    {
        $pembelian = Pembelian::findOrFail($pembelian_id);
        $stocks = Stock::where('suppliers_id', $pembelian->suppliers_id)->get();
        return view('rincianpembelian.create', compact('pembelian', 'stocks'));
    }


    public function store(Request $request)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'pembelian_id' => 'required|exists:pembelian,id',
            'items' => 'required|array',
            'items.new.*.name' => 'nullable|string',
            'items.new.*.quantity' => 'nullable|integer|min:0',
            'items.new.*.price' => 'nullable|numeric|min:0',
            'items.new.*.kode' => 'nullable|string',  // Add kode validation
            'items.existing.*.stock_id' => 'required|exists:stocks,id',
            'items.existing.*.quantity' => 'required|integer|min:0',
            'items.existing.*.price' => 'required|numeric|min:0',
            'items.existing.*.kode' => 'nullable|string',  // Add kode validation
        ]);
    
        $supplierId = $validated['supplier_id'];
        $pembelianId = $validated['pembelian_id'];
        $items = $validated['items'];
        
        // Handle new items
        if (isset($items['new']) && is_array($items['new'])) {
            foreach ($items['new'] as $newItem) {
                $name = $newItem['name'] ?? '';
                $quantity = $newItem['quantity'] ?? 0;
                $price = $newItem['price'] ?? 0;
                $kode = $newItem['kode'] ?? '';
    
                if (!empty($name) && $quantity > 0 && $price >= 0) {
                    // Create new stock
                    $stock = Stock::create([
                        'name' => $name,
                        'stock' => $quantity,
                        'suppliers_id' => $supplierId,
                        'kode' => $kode,  // Store kode
                        'beli' => $price,
                    ]);
                    
                    // Create new rincian pembelian
                    RincianPembelian::create([
                        'pembelian_id' => $pembelianId,
                        'stocks_id' => $stock->id,
                        'quantity' => $quantity,
                        'price' => $price,
                        'total' => $price * $quantity,
                    ]);
    
                    
                    
                }
            }
        }
    
        // Handle existing items
        if (isset($items['existing']) && is_array($items['existing'])) {
            foreach ($items['existing'] as $existingItem) {
                $kode = $existingItem['kode'] ?? '';  // Get kode
    
                // Create rincian pembelian entry for existing items
                RincianPembelian::create([
                    'pembelian_id' => $pembelianId,
                    'stocks_id' => $existingItem['stock_id'],
                    'quantity' => $existingItem['quantity'],
                    'price' => $existingItem['price'],
                    'total' => $existingItem['price'] * $existingItem['quantity'],
                ]);
    
                // Update stock quantity
                $stock = Stock::find($existingItem['stock_id']);
                $stock->kode = $kode;  // Update kode
                $stock->beli = $existingItem['price'];  // Update beli
                $stock->stock += $existingItem['quantity'];

                $stock->save();
    
                
                
            }
        }
    
        return redirect()->route('pembelian.index')->with('success', 'Items added and updated successfully');
    }
    

    public function edit($id)
    {
        $rincianpembelian = RincianPembelian::findOrFail($id);
        $pembelian = Pembelian::findOrFail($rincianpembelian->pembelian_id);
        $stock = Stock::findOrFail($rincianpembelian->stocks_id);
        return view('rincianpembelian.edit', compact('rincianpembelian', 'stock', 'pembelian'));
    }


    public function update(Request $request, $id)
    {
        // Find the RincianPembelian record and related models
        $rincianPembelian = RincianPembelian::findOrFail($id);
        $stock = Stock::findOrFail($rincianPembelian->stocks_id);
        $pembelian = Pembelian::findOrFail($rincianPembelian->pembelian_id);
    
        // Validate the incoming request data
        $validated = $request->validate([
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'kode' => 'nullable|string',  // Add kode validation
        ]);
    
        // Store the old quantity for stock adjustment
        $oldQuantity = $rincianPembelian->quantity;
    
        // Update the RincianPembelian record
        $rincianPembelian->quantity = $validated['quantity'];
        $rincianPembelian->price = $validated['price'];
        $rincianPembelian->total = $validated['quantity'] * $validated['price'];
        $stock->kode = $validated['kode'];  // Update kode
        $stock->beli = $validated['price'];  // Update beli
        $rincianPembelian->save();
    
        // Adjust the stock quantity based on whether the new quantity is greater or smaller
        if ($validated['quantity'] > $oldQuantity) {
            $stock->stock += ($validated['quantity'] - $oldQuantity);
        } elseif ($validated['quantity'] < $oldQuantity) {
            $stock->stock -= ($oldQuantity - $validated['quantity']);
        }
    
        // Save the updated stock quantity
        $stock->save();
    
    
        return redirect()->route('rincianpembelian.index', ['pembelian_id' => $rincianPembelian->pembelian_id])->with('success', 'Rincian pembelian updated successfully.');
    }
    



    public function destroy($id)
    {
        $rincianPembelian = RincianPembelian::findOrFail($id);
        $stock = Stock::findOrFail($rincianPembelian->stocks_id);  // Use findOrFail
        $pembelian = Pembelian::findOrFail($rincianPembelian->pembelian_id);  // Use findOrFail

        // Update the stock quantity
        $stock->stock -= $rincianPembelian->quantity;
        $stock->save();

        // Delete the RincianPembelian record
        $rincianPembelian->delete();
        
        return redirect()->route('rincianpembelian.index', ['pembelian_id' => $rincianPembelian->pembelian_id])->with('success', 'Rincian pembelian deleted successfully.');
    }

}
