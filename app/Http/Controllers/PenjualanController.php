<?php
namespace App\Http\Controllers;

use App\Penjualan;
use App\DetailPenjualan;
use App\Nelayan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class PenjualanController extends Controller
{
    public function create()
    {
        // Ambil data nelayan yang dimiliki ibu ini untuk ditampilkan di Dropdown
        $nelayans = Nelayan::where('pengguna_id', Auth::id())->get();
        return view('penjualan.create', compact('nelayans'));
    }

    public function store(Request $request)
    {
        // 1. Simpan tabel induk
        $penjualan = Penjualan::create([
            'pengguna_id' => Auth::id(),
            'nelayan_id' => $request->nelayan_id,
            'tanggal' => $request->tanggal,
            'total_harga' => 0, 
            'biaya_admin' => $request->biaya_admin ?? 0,
            'catatan' => $request->catatan,
        ]);

        $total_keseluruhan = 0;

        // 2. Simpan detail ikan BESERTA nama pengepulnya masing-masing
        if ($request->has('hasil_laut')) {
            foreach ($request->hasil_laut as $item) {
                if (!empty($item['jenis']) && !empty($item['harga']) && !empty($item['pengepul'])) {
                    DetailPenjualan::create([
                        'penjualan_id' => $penjualan->penjualan_id,
                        'nama_pengepul' => $item['pengepul'],
                        'jenis_hasil_laut' => $item['jenis'],
                        'harga' => $item['harga'],
                        'status_pembayaran' => $item['status_pembayaran']
                    ]);
                    $total_keseluruhan += $item['harga']; 
                }
            }
        }

        $penjualan->update(['total_harga' => $total_keseluruhan]);

        // ALUR REDIRECT
        return redirect()->route('home')->with([
            'success' => 'Data tersimpan! Karcis sedang diunduh.',
            'url_karcis_pdf' => route('penjualan.cetak', $penjualan->penjualan_id),
            'link_wa_nelayan' => $penjualan->link_wa
        ]);
    }

    // Menampilkan detail transaksi
    public function show($id)
    {
        // 1. Ambil data penjualan berdasarkan ID, pastikan milik ibu yang sedang login
        $penjualan = Penjualan::with(['nelayan', 'detail'])
            ->where('penjualan_id', $id)
            ->where('pengguna_id', Auth::id())
            ->firstOrFail();

        // 2. Kelompokkan rincian ikan berdasarkan nama_pengepul
        $detail_dikelompokkan = $penjualan->detail->groupBy(function ($item) {
            return $item->nama_pengepul . '|' . $item->status_pembayaran;
        });

        return view('penjualan.show', compact('penjualan', 'detail_dikelompokkan'));
    }

    // Menghapus data transaksi
    public function destroy($id)
    {
        // Cari data penjualan, pastikan itu milik ibu yang sedang login
        $penjualan = Penjualan::where('penjualan_id', $id)
                              ->where('pengguna_id', Auth::id())
                              ->firstOrFail();
        
        // Hapus data (Detail ikan akan otomatis terhapus oleh database)
        $penjualan->delete();

        return redirect()->route('home')->with('success', 'Data transaksi berhasil dihapus!');
    }

    // 1. Menampilkan Halaman Edit
    public function edit($id)
    {
        // Ambil data transaksi beserta detail ikannya
        $penjualan = Penjualan::with('detail')
            ->where('penjualan_id', $id)
            ->where('pengguna_id', Auth::id())
            ->firstOrFail();

        $nelayans = Nelayan::where('pengguna_id', Auth::id())->get();

        return view('penjualan.edit', compact('penjualan', 'nelayans'));
    }

    // 2. Menyimpan Perubahan (Update)
    public function update(Request $request, $id)
    {
        $penjualan = Penjualan::where('penjualan_id', $id)
            ->where('pengguna_id', Auth::id())
            ->firstOrFail();

        // A. Update data induk
        $penjualan->update([
            'nelayan_id' => $request->nelayan_id,
            'tanggal' => $request->tanggal,
            'biaya_admin' => $request->biaya_admin ?? 0,
            'catatan' => $request->catatan
        ]);

        // B. HAPUS SEMUA rincian ikan yang lama
        DetailPenjualan::where('penjualan_id', $penjualan->penjualan_id)->delete();

        $total_keseluruhan = 0;

        // C. SIMPAN ULANG semua ikan yang ada di form (baik yang lama diedit maupun yang baru ditambah)
        if ($request->has('hasil_laut')) {
            foreach ($request->hasil_laut as $item) {
                if (!empty($item['jenis']) && !empty($item['harga']) && !empty($item['pengepul'])) {
                    DetailPenjualan::create([
                        'penjualan_id' => $penjualan->penjualan_id,
                        'nama_pengepul' => $item['pengepul'],
                        'jenis_hasil_laut' => $item['jenis'],
                        'harga' => $item['harga'],
                        'status_pembayaran' => $item['status_pembayaran']
                    ]);
                    $total_keseluruhan += $item['harga'];
                }
            }
        }

        // D. Update total harga terbaru
        $penjualan->update(['total_harga' => $total_keseluruhan]);

        return redirect()->route('home')->with('success', 'Data penjualan berhasil diperbarui!');
    }

    public function cetak($id)
    {
        $penjualan = Penjualan::with('detail', 'nelayan')
            ->where('penjualan_id', $id)
            ->where('pengguna_id', Auth::id())
            ->firstOrFail();

        $pdf = Pdf::loadView('penjualan.pdf', compact('penjualan'));

        $namaNelayan = preg_replace('/[^A-Za-z0-9_\-]/', '_', $penjualan->nelayan->nama);

        $tanggal = \Carbon\Carbon::parse($penjualan->tanggal)
            ->locale('id')
            ->translatedFormat('d F Y');

        return $pdf->download('Karcis Penjualan_' . $namaNelayan . '_' . $tanggal . '.pdf');
    }
}