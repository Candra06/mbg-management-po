<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with(['menu.poItems', 'inspection.inspectionItems.poItem']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where('nomor_invoice', 'like', '%' . $request->search . '%')
                ->orWhereHas('menu', function ($q) use ($request) {
                    $q->where('nama_menu', 'like', '%' . $request->search . '%');
                });
        }

        $invoices = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('pages.invoice.index', compact('invoices'));
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['menu.poItems', 'inspection.inspectionItems.poItem']);
        return view('pages.invoice.show', compact('invoice'));
    }

    public function updateStatus(Request $request, Invoice $invoice)
    {
        $request->validate([
            'status' => 'required|in:draft,diterbitkan,lunas',
        ]);

        $invoice->update([
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Status Invoice berhasil diperbarui menjadi ' . ucfirst($request->status));
    }
}
