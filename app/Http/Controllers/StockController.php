<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
    
        $stocks = Stock::query()
            ->join('suppliers', 'stocks.suppliers_id', '=', 'suppliers.id')
            ->select('stocks.*', 'suppliers.name as supplier_name')
            ->when($search, function ($query, $search) {
                return $query->where('stocks.name', 'like', "%{$search}%")
                             ->orWhere('stocks.kode', 'like', "%{$search}%")
                             ->orWhere('suppliers.name', 'like', "%{$search}%");
            })
            ->get();
    
        return view('stocks.index', compact('stocks', 'search'));
    }
    
    

    public function create()
    {
        return view('stocks.create');
    }

    public function edit($id)
    {
        $stocks = Stock::findOrFail($id);
        return view('stocks.edit', compact('stocks'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'kode' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
            'price' => 'nullable|numeric|min:0',
        ]);

        $stock = Stock::findOrFail($id);
        $stock->update($request->all());([
            'name' => $request->input('name'),
            'kode' => $request->input('kode'),
            'stock' => $request->input('stock'),
            'price' => $request->input('price'),
        ]);

        return redirect()->route('stocks.index')->with('success', 'Stock updated successfully.');
    }

    public function destroy($id)
    {
        $stock = Stock::findOrFail($id);
        $stock->delete();

        return redirect()->route('stocks.index')->with('success', 'Stock deleted successfully.');
    }
}
