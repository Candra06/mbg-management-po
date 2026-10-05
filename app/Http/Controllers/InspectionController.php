<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Inspection;
use App\Models\InspectionItem;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class InspectionController extends Controller
{
    public function index(Request $request)
    {
        $selectedTanggal = $request->get('tanggal', now()->toDateString());
        $selectedMenuId = $request->get('menu_id');

        // Fetch menus for date selector
        $menusOnDate = Menu::whereDate('tanggal', $selectedTanggal)->with('poItems')->get();

        $selectedMenu = null;
        if ($selectedMenuId) {
            $selectedMenu = Menu::with(['poItems', 'inspection.inspectionItems.poItem', 'invoice'])->find($selectedMenuId);
        } elseif ($menusOnDate->isNotEmpty()) {
            $selectedMenu = $menusOnDate->first();
            $selectedMenu->load(['poItems', 'inspection.inspectionItems.poItem', 'invoice']);
        }

        $allInspections = Inspection::with(['menu', 'invoice'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pages.inspection.index', compact(
            'selectedTanggal',
            'selectedMenuId',
            'menusOnDate',
            'selectedMenu',
            'allInspections'
        ));
    }

    public function check(Menu $menu)
    {
        $menu->load(['poItems', 'inspection.inspectionItems']);
        return view('pages.inspection.check', compact('menu'));
    }

    public function store(Request $request, Menu $menu)
    {
        $request->validate([
            'nama_pemeriksa' => 'required|string|max:255',
            'catatan_umum' => 'nullable|string',
            'items' => 'required|array',
            'items.*.po_item_id' => 'required|exists:po_items,id',
            'items.*.qty_terima' => 'required|numeric|min:0',
            'items.*.warna' => 'required|string',
            'items.*.aroma' => 'required|string',
            'items.*.catatan' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $menu) {
            // Delete old inspection if exists
            if ($menu->inspection) {
                $menu->inspection->delete();
            }

            $inspection = Inspection::create([
                'menu_id' => $menu->id,
                'tanggal_cek' => now(),
                'nama_pemeriksa' => $request->nama_pemeriksa,
                'total_item' => count($request->items),
                'catatan_umum' => $request->catatan_umum,
            ]);

            $totalSesuai = 0;
            $totalTidakSesuai = 0;
            $subtotalActual = 0;

            foreach ($request->items as $itemData) {
                $poItem = $menu->poItems()->find($itemData['po_item_id']);
                
                $qtyPesan = floatval($poItem->qty_pesan);
                $qtyTerima = floatval($itemData['qty_terima']);
                
                $isQtyMatch = abs($qtyPesan - $qtyTerima) < 0.01;
                $isWarnaNormal = in_array(strtolower($itemData['warna']), ['segar/normal', 'segar', 'normal']);
                $isAromaNormal = in_array(strtolower($itemData['aroma']), ['segar/normal', 'segar', 'normal']);

                $statusJumlah = ($isQtyMatch && $isWarnaNormal && $isAromaNormal) ? 'sesuai' : 'tidak_sesuai';

                if ($statusJumlah === 'sesuai') {
                    $totalSesuai++;
                } else {
                    $totalTidakSesuai++;
                }

                $inspection->inspectionItems()->create([
                    'po_item_id' => $poItem->id,
                    'qty_terima' => $qtyTerima,
                    'status_jumlah' => $statusJumlah,
                    'warna' => $itemData['warna'],
                    'aroma' => $itemData['aroma'],
                    'catatan' => $itemData['catatan'] ?? null,
                ]);

                $subtotalActual += ($qtyTerima * $poItem->harga_satuan);
            }

            $inspection->total_sesuai = $totalSesuai;
            $inspection->total_tidak_sesuai = $totalTidakSesuai;
            $inspection->status_keseluruhan = ($totalTidakSesuai === 0) ? 'Passed' : ($totalSesuai > 0 ? 'Passed With Note' : 'Ditolak QC');
            $inspection->save();

            // Auto Generate Invoice upon completion of inspection
            if (!$menu->invoice) {
                $subtotalPo = $menu->poItems->sum('total_harga');
                $potongan = max(0, $subtotalPo - $subtotalActual);

                Invoice::create([
                    'nomor_invoice' => 'INV-MBG-' . date('Ymd') . '-' . str_pad($menu->id, 4, '0', STR_PAD_LEFT),
                    'menu_id' => $menu->id,
                    'inspection_id' => $inspection->id,
                    'tanggal_invoice' => now()->toDateString(),
                    'subtotal_po' => $subtotalPo,
                    'potongan_ketidaksesuaian' => $potongan,
                    'total_tagihan' => $subtotalActual,
                    'status' => 'diterbitkan',
                    'catatan' => 'Diterbitkan otomatis setelah pengecekan kualitas barang.',
                ]);
            }
        });

        return redirect()->route('inspection.index', ['menu_id' => $menu->id])
            ->with('success', 'Pengecekan bahan berhasil disimpan & Invoice telah diterbitkan!');
    }

    public function show(Inspection $inspection)
    {
        $inspection->load(['menu.poItems', 'inspectionItems.poItem', 'invoice']);
        return view('pages.inspection.show', compact('inspection'));
    }
}
