<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\RincianPenjualan;
use App\Models\Stock;
use App\Models\Supplier;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PenjualanController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $month = $request->input('month');
        $year = $request->input('year');
    
        $query = Penjualan::query()
            ->join('customers', 'penjualan.customers_id', '=', 'customers.id')
            ->select('penjualan.*'); // Ensure only columns from penjualan are selected
    
        // Apply search filter
        if ($search) {
            $query->where('customers.name', 'like', "%{$search}%");
        }
    
        // Apply month/year filter if provided
        if ($month && $year) {
            $query->whereYear('penjualan.created_at', $year)
                  ->whereMonth('penjualan.created_at', $month);
        }
    
        // Pagination
        $penjualan = $query->paginate(15); // Adjust the number of items per page as needed
    
        return view('penjualan.index', compact('penjualan', 'search', 'month', 'year'));
    }
    
    

    public function create()
    {
        $suppliers = Supplier::all();
        $customers = Customer::all();
        return view('penjualan.create', compact('suppliers', 'customers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customers_id' => 'required|exists:customers,id',
            'status' => 'required|string',
            'sales' => 'required|string',
            'tenggat_waktu' => 'required|date',
            'diskon' => 'nullable|integer|between:0,100', // Allow nullable values if not provided
        ]);
    
        $userId = Auth::id(); // Ensure you get the authenticated user ID
    
        // Debugging: Log request data
        Log::info('Store Request Data:', $validated);
    
        $penjualan = Penjualan::create([
            'customers_id' => $validated['customers_id'],
            'total' => 0, // Total will be updated later
            'status' => $validated['status'],
            'sales' => $validated['sales'],
            'tenggat_waktu' => $validated['tenggat_waktu'],
            'users_id' => $userId, // Ensure this is set
            'ppn' => 0, // PPN will be updated later
            'dpp' => 0, // dpp will be updated later
            'total_netto' => 0, // Total netto will be updated later
            'diskon' => $validated['diskon'], // Assign the validated diskon value
        ]);
        


        // Redirect to create rincianpenjualan page with the correct penjualan_id
        return redirect()->route('rincianpenjualan.create', ['penjualan_id' => $penjualan->id]);
    }
    


    public function edit(Penjualan $penjualan)
    {
        $suppliers = Supplier::all();
        $customers = Customer::all();
        return view('penjualan.edit', compact('penjualan', 'suppliers', 'customers'));
    }

    public function update(Request $request, Penjualan $penjualan)
    {
        $validated = $request->validate([
            'customers_id' => 'required|exists:customers,id',
            'status' => 'required|string',
            'sales' => 'required|string',
            'tenggat_waktu' => 'required|date',
            'diskon' => 'nullable|integer|between:0,100', // Allow nullable values if not provided
        ]);
    
        $userId = Auth::id(); // Ensure you get the authenticated user ID
    
        $penjualan->update([
            'customers_id' => $validated['customers_id'],
            'total' => 0, // Total will be updated later
            'status' => $validated['status'],
            'sales' => $validated['sales'],
            'tenggat_waktu' => $validated['tenggat_waktu'],
            'users_id' => $userId, // Ensure this is set
            'ppn' => 0, // PPN will be updated later
            'dpp' => 0, // dpp will be updated later
            'total_netto' => 0, // Total netto will be updated later
            'diskon' => $validated['diskon'], // Assign the validated diskon value
        ]);

    
        return redirect()->route('penjualan.index')
                         ->with('success', 'Penjualan updated successfully.');
    }
    

    public function destroy(Penjualan $penjualan)
    {
        // Get all rincian penjualan associated with the penjualan
        $rincianpenjualans = RincianPenjualan::where('penjualan_id', $penjualan->id)->get();
        
        // Update the stocks table based on the items being deleted
        foreach ($rincianpenjualans as $rincian) {
            // Find the stock by the ID from rincian penjualan
            $stock = Stock::find($rincian->stocks_id);
            if ($stock) {
                // Increase the stock quantity by the quantity of the item in rincian penjualan
                $stock->stock += $rincian->quantity;
                $stock->save();
            }
        }
    
        // Delete all rincian penjualan with the same penjualan id
        RincianPenjualan::where('penjualan_id', $penjualan->id)->delete();
    
        // Delete the penjualan
        $penjualan->delete();
    
        return redirect()->route('penjualan.index')
                        ->with('success', 'Penjualan and associated rincian penjualan deleted successfully.');
    }    



}
