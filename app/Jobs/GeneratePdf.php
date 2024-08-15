<?php
namespace App\Jobs;

use App\Models\Penjualan;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GeneratePdf implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $penjualanId;

    public function __construct($penjualanId)
    {
        $this->penjualanId = $penjualanId;
    }

    public function handle()
{
    $penjualan = Penjualan::with('rincianpenjualans.stock')->findOrFail($this->penjualanId);
    $pdf = PDF::loadView('rincianpenjualan.print', compact('penjualan'));
    $pdfPath = 'bills/penjualan_' . $this->penjualanId . '.pdf';
    $pdf->save(storage_path('app/public/' . $pdfPath));
}
}
