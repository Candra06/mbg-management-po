<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;
use App\Models\PoItem;
use App\Models\Inspection;
use App\Models\InspectionItem;
use App\Models\Invoice;
use Carbon\Carbon;

class MbgDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Menu 1: Nasi Ayam Bakar & Tumis Buncis (Lolos QC Perfect)
        $menu1 = Menu::create([
            'tanggal' => Carbon::now()->format('Y-m-d'),
            'nama_menu' => 'Nasi Ayam Bakar Kecap + Tumis Buncis + Buah Pisang',
            'kategori' => 'Utama',
            'catatan' => 'Porsi 100 porsi makan bergizi gratis siswa.',
        ]);

        $item1_1 = $menu1->poItems()->create([
            'nama_bahan' => 'Daging Ayam Fillet',
            'merk' => 'BestChicken',
            'qty_pesan' => 15.00,
            'satuan' => 'kg',
            'harga_satuan' => 38000,
            'total_harga' => 570000,
        ]);

        $item1_2 = $menu1->poItems()->create([
            'nama_bahan' => 'Beras Medium Super',
            'merk' => 'Rojo Lele',
            'qty_pesan' => 20.00,
            'satuan' => 'kg',
            'harga_satuan' => 14500,
            'total_harga' => 290000,
        ]);

        $item1_3 = $menu1->poItems()->create([
            'nama_bahan' => 'Sayur Buncis Segar',
            'merk' => null,
            'qty_pesan' => 6.00,
            'satuan' => 'kg',
            'harga_satuan' => 12000,
            'total_harga' => 72000,
        ]);

        $item1_4 = $menu1->poItems()->create([
            'nama_bahan' => 'Buah Pisang Cavendish',
            'merk' => 'Sunpride',
            'qty_pesan' => 100.00,
            'satuan' => 'pcs',
            'harga_satuan' => 2000,
            'total_harga' => 200000,
        ]);

        // Inspection for Menu 1
        $inspection1 = Inspection::create([
            'menu_id' => $menu1->id,
            'tanggal_cek' => Carbon::now()->subHours(2),
            'nama_pemeriksa' => 'Budi Santoso (QC Lead)',
            'total_item' => 4,
            'total_sesuai' => 4,
            'total_tidak_sesuai' => 0,
            'status_keseluruhan' => 'Lolos QC Perfect',
            'catatan_umum' => 'Semua bahan datang lengkap, kualitas segar dan porsi sesuai.',
        ]);

        foreach ($menu1->poItems as $po) {
            $inspection1->inspectionItems()->create([
                'po_item_id' => $po->id,
                'qty_terima' => $po->qty_pesan,
                'status_jumlah' => 'sesuai',
                'warna' => 'Segar/Normal',
                'aroma' => 'Segar/Normal',
                'catatan' => 'Bahan memenuhi standar mutu.',
            ]);
        }

        // Invoice for Menu 1
        $totalPo1 = $menu1->poItems->sum('total_harga');
        Invoice::create([
            'nomor_invoice' => 'INV-MBG-' . date('Ymd') . '-0001',
            'menu_id' => $menu1->id,
            'inspection_id' => $inspection1->id,
            'tanggal_invoice' => Carbon::now()->format('Y-m-d'),
            'subtotal_po' => $totalPo1,
            'potongan_ketidaksesuaian' => 0,
            'total_tagihan' => $totalPo1,
            'status' => 'diterbitkan',
            'catatan' => 'Invoice resmi diterbitkan setelah pengecekan QC.',
        ]);

        // Menu 2: Sup Daging Sapi & Wortel (Pending QC)
        $menu2 = Menu::create([
            'tanggal' => Carbon::now()->addDay()->format('Y-m-d'),
            'nama_menu' => 'Nasi Sup Daging Sapi Wortel + Perkedel Kentang + Apel',
            'kategori' => 'Utama',
            'catatan' => 'Rencana masak untuk besok pagi.',
        ]);

        $menu2->poItems()->create([
            'nama_bahan' => 'Daging Sapi Segar',
            'merk' => 'Lokal Segar',
            'qty_pesan' => 12.00,
            'satuan' => 'kg',
            'harga_satuan' => 125000,
            'total_harga' => 1500000,
        ]);

        $menu2->poItems()->create([
            'nama_bahan' => 'Wortel Import',
            'merk' => null,
            'qty_pesan' => 8.00,
            'satuan' => 'kg',
            'harga_satuan' => 15000,
            'total_harga' => 120000,
        ]);

        $menu2->poItems()->create([
            'nama_bahan' => 'Kentang Dieng',
            'merk' => null,
            'qty_pesan' => 10.00,
            'satuan' => 'kg',
            'harga_satuan' => 18000,
            'total_harga' => 180000,
        ]);
    }
}
