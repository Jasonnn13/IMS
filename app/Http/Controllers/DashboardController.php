<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Penjualan;
use App\Models\Laporan;
use App\Models\User;
use App\Models\Pembelian;

class DashboardController extends Controller
{
    public function index()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $countReq = User::where('level', 0)->count();

        $penjualanCount = Penjualan::whereMonth('created_at', $currentMonth)
                                    ->whereYear('created_at', $currentYear)
                                    ->count();

        $pembelianCount = Pembelian::whereMonth('created_at', $currentMonth)
                                    ->whereYear('created_at', $currentYear)
                                    ->count();

        // Data for the last 12 months
        $months = [];   
        $penjualanData = [];
        $pembelianData = [];
        $profitData = [];
        $penjualanMonths = [];
        $pembelianMonths = [];
        $profitMonths = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M Y');
            $penjualanMonths[] = $date->format('M Y');
            $pembelianMonths[] = $date->format('M Y');
            $profitMonths[] = $date->format('M Y');

            $penjualanTotal = Penjualan::whereMonth('created_at', $date->month)
                                        ->whereYear('created_at', $date->year)
                                        ->sum('total');

            $pembelianTotal = Pembelian::whereMonth('created_at', $date->month)
                                        ->whereYear('created_at', $date->year)
                                        ->sum('total');
                
            $profit = $penjualanTotal - $pembelianTotal;

            $penjualanData[] = $penjualanTotal;
            $pembelianData[] = $pembelianTotal;
            $profitData[] = $profit;
        }

        return view('dashboard.index', compact('countReq', 'penjualanCount', 'pembelianCount', 'months', 'penjualanData', 'pembelianData', 'profitData', 'penjualanMonths', 'pembelianMonths', 'profitMonths'));
    }
}
