<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\Pembelian;
use App\Models\Penjualan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function profit()
    {
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;

        // Data for the current month

        $penjualanTotalCurrentMonth = Penjualan::whereMonth('created_at', $currentMonth)
                                    ->whereYear('created_at', $currentYear)
                                    ->sum('total');

        $pembelianTotalCurrentMonth = Pembelian::whereMonth('created_at', $currentMonth)
                                    ->whereYear('created_at', $currentYear)
                                    ->sum('total');

        $profitCurrentMonth = $penjualanTotalCurrentMonth - $pembelianTotalCurrentMonth;

        // Check if a row for the current month exists in the 'laporan' table
        $laporan = Laporan::whereMonth('created_at', $currentMonth)
                            ->whereYear('created_at', $currentYear)
                            ->first();

        if ($laporan) {
            // Update the existing row
            $laporan->update([
                'pemasukan' => $penjualanTotalCurrentMonth,
                'pengeluaran' => $pembelianTotalCurrentMonth,
                'profit' => $profitCurrentMonth,
            ]);
        } else {
            // Create a new row
            Laporan::create([
                'pemasukan' => $penjualanTotalCurrentMonth,
                'pengeluaran' => $pembelianTotalCurrentMonth,
                'profit' => $profitCurrentMonth,
                'bulan' => $currentMonth,
                'tahun' => $currentYear,
            ]);
        }

        $laporans = Laporan::all();
        $laporans = Laporan::orderBy('tahun', 'desc')->orderBy('bulan', 'desc')->get();

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
            $penjualanMonths[] = $date->format('M Y'); // To be used for Penjualan chart
            $pembelianMonths[] = $date->format('M Y'); // To be used for Pembelian chart
            $profitMonths[] = $date->format('M Y'); // To be used for Profit chart

            $penjualanTotal = Penjualan::whereMonth('created_at', $date->month)
                                        ->whereYear('created_at', $date->year)
                                        ->sum('total'); // Assuming 'total' field in ekonomi

            $pembelianTotal = Pembelian::whereMonth('created_at', $date->month)
                                        ->whereYear('created_at', $date->year)
                                        ->sum('total'); // Assuming 'total' field in ekonomi
                
            $profit = $penjualanTotal - $pembelianTotal;

            $penjualanData[] = $penjualanTotal;
            $pembelianData[] = $pembelianTotal;
            $profitData[] = $profit;
        }


        return view('laporan.profit', compact('laporans', 'months', 'penjualanData', 'pembelianData', 'profitData', 'penjualanMonths', 'pembelianMonths', 'profitMonths'));
    }

    
}
