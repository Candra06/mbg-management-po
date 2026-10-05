<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\PoItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PoController extends Controller
{
    public function index(Request $request)
    {
        $query = Menu::with(['poItems', 'inspection', 'invoice']);

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        if ($request->filled('search')) {
            $query->where('nama_menu', 'like', '%' . $request->search . '%');
        }

        $menus = $query->orderBy('tanggal', 'desc')->paginate(10);

        return view('pages.po.index', compact('menus'));
    }

    public function create()
    {
        return view('pages.po.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'nama_menu' => 'required|string|max:255',
            'kategori' => 'nullable|string',
            'catatan' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.nama_bahan' => 'required|string|max:255',
            'items.*.merk' => 'nullable|string|max:255',
            'items.*.qty_pesan' => 'required|numeric|min:0.01',
            'items.*.satuan' => 'required|string|max:50',
            'items.*.harga_satuan' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $menu = Menu::create([
                'tanggal' => $request->tanggal,
                'nama_menu' => $request->nama_menu,
                'kategori' => $request->kategori ?? 'Utama',
                'catatan' => $request->catatan,
            ]);

            foreach ($request->items as $itemData) {
                $total = floatval($itemData['qty_pesan']) * floatval($itemData['harga_satuan']);
                $menu->poItems()->create([
                    'nama_bahan' => $itemData['nama_bahan'],
                    'merk' => $itemData['merk'] ?? null,
                    'qty_pesan' => $itemData['qty_pesan'],
                    'satuan' => $itemData['satuan'],
                    'harga_satuan' => $itemData['harga_satuan'],
                    'total_harga' => $total,
                ]);
            }
        });

        return redirect()->route('po.index')->with('success', 'Pemesanan Bahan (PO) berhasil disimpan oleh Akuntan!');
    }

    public function show(Menu $menu)
    {
        $menu->load(['poItems', 'inspection.inspectionItems.poItem', 'invoice']);
        return view('pages.po.show', compact('menu'));
    }

    public function edit(Menu $menu)
    {
        $menu->load('poItems');
        return view('pages.po.edit', compact('menu'));
    }

    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'nama_menu' => 'required|string|max:255',
            'kategori' => 'nullable|string',
            'catatan' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.id' => 'nullable|exists:po_items,id',
            'items.*.nama_bahan' => 'required|string|max:255',
            'items.*.merk' => 'nullable|string|max:255',
            'items.*.qty_pesan' => 'required|numeric|min:0.01',
            'items.*.satuan' => 'required|string|max:50',
            'items.*.harga_satuan' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request, $menu) {
            $menu->update([
                'tanggal' => $request->tanggal,
                'nama_menu' => $request->nama_menu,
                'kategori' => $request->kategori ?? 'Utama',
                'catatan' => $request->catatan,
            ]);

            // Keep track of item IDs present in request
            $submittedIds = [];

            foreach ($request->items as $itemData) {
                $total = floatval($itemData['qty_pesan']) * floatval($itemData['harga_satuan']);
                
                if (!empty($itemData['id'])) {
                    $poItem = $menu->poItems()->where('id', $itemData['id'])->first();
                    if ($poItem) {
                        $poItem->update([
                            'nama_bahan' => $itemData['nama_bahan'],
                            'merk' => $itemData['merk'] ?? null,
                            'qty_pesan' => $itemData['qty_pesan'],
                            'satuan' => $itemData['satuan'],
                            'harga_satuan' => $itemData['harga_satuan'],
                            'total_harga' => $total,
                        ]);
                        $submittedIds[] = $poItem->id;
                    }
                } else {
                    $newItem = $menu->poItems()->create([
                        'nama_bahan' => $itemData['nama_bahan'],
                        'merk' => $itemData['merk'] ?? null,
                        'qty_pesan' => $itemData['qty_pesan'],
                        'satuan' => $itemData['satuan'],
                        'harga_satuan' => $itemData['harga_satuan'],
                        'total_harga' => $total,
                    ]);
                    $submittedIds[] = $newItem->id;
                }
            }

            // Delete removed items
            $menu->poItems()->whereNotIn('id', $submittedIds)->delete();

            // Recalculate invoice totals if invoice exists
            if ($menu->invoice) {
                $newSubtotal = $menu->poItems()->sum('total_harga');
                $potongan = $menu->invoice->potongan_ketidaksesuaian ?? 0;
                $menu->invoice->update([
                    'subtotal_po' => $newSubtotal,
                    'total_tagihan' => max(0, $newSubtotal - $potongan),
                ]);
            }
        });

        return redirect()->route('po.show', $menu->id)->with('success', 'Pemesanan Bahan (PO) berhasil diperbarui!');
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();
        return redirect()->route('po.index')->with('success', 'Data PO Bahan berhasil dihapus!');
    }
}
