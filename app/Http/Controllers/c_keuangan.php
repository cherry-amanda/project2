<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Keuangan;
use App\Models\Payment;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\KeuanganExport;
use PDF;

class c_keuangan extends Controller
{
    public function index()
    {
        $data = Keuangan::with('payment')->orderBy('tanggal', 'desc')->get();
        $totalMasuk = $data->where('jenis', 'pemasukan')->sum('nominal');
        $totalKeluar = $data->where('jenis', 'pengeluaran')->sum('nominal');
        $saldo = $totalMasuk - $totalKeluar;

        return view('admin.keuangan.v_list', compact('data', 'totalMasuk', 'totalKeluar', 'saldo'));
    }


    public function create()
    {
        return view('admin.keuangan.v_add');
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis' => 'required',
            'kategori' => 'required',
            'keterangan' => 'nullable',
            'nominal' => 'required|numeric',
            'tanggal' => 'required|date',
            'relasi_id' => 'nullable|exists:payment,id',
            'bukti' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048'
        ]);

        $buktiPath = null;
        if ($request->hasFile('bukti')) {
            $buktiPath = $request->file('bukti')->store('bukti_keuangan', 'public');
        }

        Keuangan::create([
            'jenis' => $request->jenis,
            'kategori' => $request->kategori,
            'keterangan' => $request->keterangan,
            'nominal' => $request->nominal,
            'tanggal' => $request->tanggal,
            'relasi_id' => $request->relasi_id,
            'bukti' => $buktiPath
        ]);

        return redirect()->route('admin.keuangan.index')->with('success', 'Data keuangan berhasil ditambahkan.');
    }

    public function show($id)
    {
        $data = Keuangan::with('payment')->findOrFail($id);
        return view('admin.keuangan.v_detail', compact('data'));
    }

    public function grafik()
    {
        $data = Keuangan::getSummaryByMonth();
        return view('admin.keuangan.v_grafik', compact('data'));
    }

    public function exportExcel()
    {
        return Excel::download(new KeuanganExport, 'keuangan.xlsx');
    }

    public function exportPdf()
    {
        $data = Keuangan::all();
        $pdf = PDF::loadView('admin.keuangan.v_pdf', compact('data'));
        return $pdf->download('keuangan.pdf');
    }
}