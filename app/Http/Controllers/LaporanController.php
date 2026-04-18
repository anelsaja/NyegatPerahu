<?php

namespace App\Http\Controllers;

use App\Nelayan;
use App\Penjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // Ambil daftar nelayan untuk dropdown filter
        $nelayans = Nelayan::where('pengguna_id', Auth::id())->get();
        
        $laporan = null;
        $total_kotor = 0;
        $total_admin = 0;
        $laba_bersih = 0;

        // Jika user sudah menekan tombol cari (filter)
        if ($request->filled('nelayan_id') && $request->filled('bulan')) {
            $bulan = date('m', strtotime($request->bulan));
            $tahun = date('Y', strtotime($request->bulan));

            $laporan = Penjualan::with('detail')
                ->where('pengguna_id', Auth::id())
                ->where('nelayan_id', $request->nelayan_id)
                ->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun)
                ->orderBy('tanggal', 'asc')
                ->get();

            // LOGIKA PERHITUNGAN BARU (Sama dengan PDF)
            foreach ($laporan as $trx) {
                $total_kotor += $trx->total_harga;
                $total_admin += $trx->biaya_admin;
            }
            
            $laba_bersih = $total_kotor - $total_admin;
        }

        return view('laporan.index', compact(
            'nelayans', 
            'laporan', 
            'total_kotor', 
            'total_admin', 
            'laba_bersih'
        ));
    }

    // Fungsi Baru untuk Download PDF
    public function downloadPDF(Request $request)
    {
        $nelayan = Nelayan::findOrFail($request->nelayan_id);
        $bulan = date('m', strtotime($request->bulan));
        $tahun = date('Y', strtotime($request->bulan));

        $laporan = Penjualan::with('detail')
            ->where('pengguna_id', Auth::id())
            ->where('nelayan_id', $request->nelayan_id)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->orderBy('tanggal', 'asc')
            ->get();

        // LOGIKA BARU: Hitung Total Kotor, Admin, dan Laba Bersih
        $total_kotor = 0;
        $total_admin = 0;

        foreach ($laporan as $trx) {
            $total_kotor += $trx->total_harga; // Asumsi ini adalah harga ikan keseluruhan
            $total_admin += $trx->biaya_admin; // Potongan admin per transaksi
        }

        $laba_bersih = $total_kotor - $total_admin;
        $nama_bulan = date('F Y', strtotime($request->bulan));

        // Masukkan ke dalam array data
        $data = [
            'nelayan' => $nelayan,
            'laporan' => $laporan,
            'total_kotor' => $total_kotor,
            'total_admin' => $total_admin,
            'laba_bersih' => $laba_bersih,
            'nama_bulan' => $nama_bulan
        ];

        $pdf = app('dompdf.wrapper')->loadView('laporan.pdf', $data);
        return $pdf->download('Laporan_'.$nelayan->nama.'_'.$nama_bulan.'.pdf');
    }
}
