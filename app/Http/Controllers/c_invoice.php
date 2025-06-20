<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Klien;
use Barryvdh\DomPDF\Facade\Pdf;

class c_invoice extends Controller
{
    public function index()
    {
        $invoice = Invoice::with('klien.pengguna')->get();
        return view('admin.invoice.v_invoice', compact('invoice'));
    }

    public function create()
    {
        $klien = Klien::all();
        return view('admin.invoice.v_create', compact('klien'));
    }

    public function store(Request $request)
    {
        
        $request->validate([
            'id_klien' => 'nullable|exists:klien,id_klien',
            'nomor_invoice' => 'required|unique:invoice',
            'tanggal_invoice' => 'required|date',
            'status' => 'required|in:pending,lunas,batal',
            'items' => 'required|array|min:1',
            'items.*.deskripsi' => 'required|string',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.harga_satuan' => 'required|numeric|min:0',
        ]);

        // Proses items
        $descriptions = [];
        $totalHarga = 0;
        foreach ($request->items as $item) {
            $descriptions[] = $item['deskripsi'] . " (Qty: {$item['qty']} x Rp " . number_format($item['harga_satuan'], 0, ',', '.') . ")";
            $totalHarga += $item['qty'] * $item['harga_satuan'];
        }

        $invoiceData = [
            'id_klien' => $request->id_klien,
            'nomor_invoice' => $request->nomor_invoice,
            'tanggal_invoice' => $request->tanggal_invoice,
            'deskripsi' => implode("; ", $descriptions),
            'total_harga' => $totalHarga,
            'status' => $request->status,
        ];

        Invoice::create($invoiceData);

        return redirect()->route('admin.invoice.index')->with('success', 'Invoice berhasil ditambahkan.');
    }


    public function print($id)
    {
        $invoice = Invoice::with('klien')->findOrFail($id);
        $pdf = Pdf::loadView('admin.invoice.pdf', compact('invoice'));
        return $pdf->stream('Invoice-'.$invoice->nomor_invoice.'.pdf');
    }
    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->delete();

        return redirect()->route('admin.invoice.index')->with('success', 'Invoice berhasil dihapus.');
    }



}
