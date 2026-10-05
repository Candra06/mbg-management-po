<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Inspection;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ExportController extends Controller
{
    /**
     * Export PO (Purchase Order) to PDF
     */
    public function exportPoPdf(Menu $menu)
    {
        $menu->load('poItems');

        $pdf = Pdf::loadView('pdf.po', compact('menu'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('PO_' . Str::slug($menu->nama_menu) . '_' . $menu->tanggal->format('Y-m-d') . '.pdf');
    }

    /**
     * Export Form QC (Checklist Manual Pengecekan Kualitas) to PDF
     */
    public function exportQcFormPdf(Menu $menu)
    {
        $menu->load('poItems');

        $pdf = Pdf::loadView('pdf.qc_form', compact('menu'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('Form_QC_Manual_' . Str::slug($menu->nama_menu) . '_' . $menu->tanggal->format('Y-m-d') . '.pdf');
    }

    /**
     * Export PO Invoice (Draft / Terbit) to PDF
     */
    public function exportPoInvoicePdf(Menu $menu)
    {
        $menu->load(['poItems', 'invoice', 'inspection.inspectionItems.poItem']);

        if ($menu->invoice) {
            return $this->exportInvoicePdf($menu->invoice);
        }

        $pdf = Pdf::loadView('pdf.po_invoice', compact('menu'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('Invoice_Draft_' . Str::slug($menu->nama_menu) . '_' . $menu->tanggal->format('Y-m-d') . '.pdf');
    }

    /**
     * Export Invoice to PDF
     */
    public function exportInvoicePdf(Invoice $invoice)
    {
        $invoice->load(['menu.poItems', 'inspection.inspectionItems.poItem']);

        $pdf = Pdf::loadView('pdf.invoice', compact('invoice'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('Invoice_' . $invoice->nomor_invoice . '.pdf');
    }

    /**
     * Export Inspection QC Report to PDF
     */
    public function exportInspectionPdf(Inspection $inspection)
    {
        $inspection->load(['menu.poItems', 'inspectionItems.poItem', 'invoice']);

        $pdf = Pdf::loadView('pdf.inspection', compact('inspection'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('Laporan_QC_' . Str::slug($inspection->menu->nama_menu) . '_' . $inspection->menu->tanggal->format('Y-m-d') . '.pdf');
    }

    /**
     * Export Invoice / PO / Inspection to Excel (XLS / CSV formatted)
     */
    public function exportExcel(Request $request, $type)
    {
        $filename = 'Export_' . ucfirst($type) . '_' . date('Ymd_His') . '.xls';

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        if ($type === 'po') {
            $menus = Menu::with('poItems')->orderBy('tanggal', 'desc')->get();
            return view('excel.po', compact('menus'));
        } elseif ($type === 'inspection') {
            $inspections = Inspection::with(['menu', 'inspectionItems.poItem'])->orderBy('created_at', 'desc')->get();
            return view('excel.inspection', compact('inspections'));
        } elseif ($type === 'invoice') {
            $invoices = Invoice::with(['menu', 'inspection'])->orderBy('created_at', 'desc')->get();
            return view('excel.invoice', compact('invoices'));
        }

        abort(404, 'Tipe export tidak ditemukan.');
    }
}
