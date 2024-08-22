<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Support\Facades\Log;
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
            ->orderBy('stocks.created_at', 'desc') // Order by newest created_at
            ->get();
    
        return view('stocks.index', compact('stocks', 'search'));
    }

    

    public function create()
    {
        $suppliers = Supplier::all();
        return view('stocks.create', compact('suppliers'));
    }

    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'kode' => 'required|string|max:255|unique:stocks,kode',
        'stock' => 'required|integer|min:0',
        'jual' => 'nullable|numeric|min:0',
        'beli' => 'nullable|numeric|min:0',
        'suppliers_id' => 'required|exists:suppliers,id',
    ]);

    Stock::create([
        'name' => $request->input('name'),
        'kode' => $request->input('kode'),
        'stock' => $request->input('stock'),
        'jual' => $request->input('jual'),
        'beli' => $request->input('beli'),
        'suppliers_id' => $request->input('suppliers_id'),
    ]);

    return redirect()->route('stocks.index')->with('success', 'Stock created successfully.');
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
            'jual' => 'nullable|numeric|min:0',
            'beli' => 'nullable|numeric|min:0',
        ]);

        $stock = Stock::findOrFail($id);
        $stock->update($request->all());([
            'name' => $request->input('name'),
            'kode' => $request->input('kode'),
            'stock' => $request->input('stock'),
            'jual' => $request->input('jual'),
            'beli' => $request->input('beli'),
        ]);

        return redirect()->route('stocks.index')->with('success', 'Stock updated successfully.');
    }

    public function destroy($id)
    {
        $stock = Stock::findOrFail($id);
        $stock->delete();

        return redirect()->route('stocks.index')->with('success', 'Stock deleted successfully.');
    }

    public function autocomplete(Request $request)
    {
        $search = $request->input('term');
        
        $stocks = Stock::query()
            ->where('name', 'like', "%{$search}%")
            ->orWhere('kode', 'like', "%{$search}%")
            ->limit(10)
            ->get(['id', 'name', 'kode']);
        
            $results = $stocks->map(function ($stock) {
                return [
                    'label' => $stock->name, // Display name
                    // 'value' => $stock->id,   // ID to be used in the hidden field
                ];
            });
            
        
        return response()->json($results);
    }
    



    
}
