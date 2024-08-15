<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\RincianPembelian;
use App\Models\Stock;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;  // Import Auth facade
use Illuminate\Support\Facades\Log;  // Import the Log facade

class PembelianController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $month = $request->input('month');
        $year = $request->input('year');
    
        $query = Pembelian::query()
            ->join('suppliers', 'pembelian.suppliers_id', '=', 'suppliers.id')
            ->select('pembelian.*'); // Ensure only columns from pembelian are selected
    
        // Apply search filter
        if ($search) {
            $query->where('suppliers.name', 'like', "%{$search}%");
        }
    
        // Apply month/year filter if provided
        if ($month && $year) {
            $query->whereYear('pembelian.created_at', $year)
                  ->whereMonth('pembelian.created_at', $month);
        }
    
        // Pagination
        $pembelian = $query->paginate(15); // Adjust the number of items per page as needed
    
        return view('pembelian.index', compact('pembelian', 'search', 'month', 'year'));
    }
    


    public function create()
    {
        $suppliers = Supplier::all();
        return view('pembelian.create', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'suppliers_id' => 'required|exists:suppliers,id',
            'total' => 'required|numeric|min:0',
        ]);

        $userId = Auth::id();  // Get the current user ID

        // Debugging: Log request data
        Log::info('Store Request Data:', $validated);

        $pembelian = Pembelian::create([
            'suppliers_id' => $validated['suppliers_id'],
            'total' => $validated['total'], // Total will be updated later
            'users_id' => $userId,  // Set user_id
        ]);


        // Redirect to create rincianpembelian page with the correct pembelian_id
        return redirect()->route('rincianpembelian.create', ['pembelian_id' => $pembelian->id]);
    }


    public function edit(Pembelian $pembelian)
    {
        $suppliers = Supplier::all();
        return view('pembelian.edit', compact('pembelian', 'suppliers'));
    }

    public function update(Request $request, Pembelian $pembelian)
    {
        $validated = $request->validate([
            'suppliers_id' => 'required|exists:suppliers,id',
            'total' => 'required|numeric|min:0',
            // Add validation rules for other fields if necessary
        ]);

        $userId = Auth::id(); // Get the current authenticated user's ID
    
        // Update the pembelian record
        $pembelian->update([
            'suppliers_id' => $validated['suppliers_id'],
            'users_id' => $userId, // Update to the current authenticated user's ID
            'total' => $validated['total'],
        ]);
    
        $rincianPembelian = RincianPembelian::where('pembelian_id', $pembelian->id)->get();

        foreach ($rincianPembelian as $rincian) {
            $stock = Stock::findOrFail($rincian->stocks_id);  // Use findOrFail
            $stock->suppliers_id = $validated['suppliers_id'];
            $stock->save();
        }
    
        return redirect()->route('pembelian.index')
                         ->with('success', 'Pembelian updated successfully.');
    }
    

    public function destroy(Pembelian $pembelian)
    {
        // Get all rincian pembelian associated with the pembelian
        $rincianPembelians = RincianPembelian::where('pembelian_id', $pembelian->id)->get();

        // Update the stocks table based on the items being deleted
        foreach ($rincianPembelians as $rincian) {
            $rincianPembelian = RincianPembelian::findOrFail($rincian->id);
            $stock = Stock::findOrFail($rincianPembelian->stocks_id);  // Use findOrFail
            $pembelian = Pembelian::findOrFail($rincianPembelian->pembelian_id);  // Use findOrFail

            // Update the stock quantity
            $stock->stock -= $rincianPembelian->quantity;
            $stock->save();

            // Delete the RincianPembelian record
            $rincianPembelian->delete();

            // Update the total pembelian
            $pembelian->total = $pembelian->rincianPembelians()->sum('total');  // Access relationship correctly
            $pembelian->save();
        }

        // Delete all rincian pembelian with the same pembelian id
        RincianPembelian::where('pembelian_id', $pembelian->id)->delete();

        // Delete the pembelian
        $pembelian->delete();

        return redirect()->route('pembelian.index')
                        ->with('success', 'Pembelian and associated rincian pembelian deleted successfully.');
    }
}
