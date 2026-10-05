@extends('layouts.app')

@section('title', 'Detail PO Bahan - ' . $menu->nama_menu)
@section('page_title', 'Detail Pemesanan Bahan (PO)')

@section('content')
    <div class="space-y-6 max-w-5xl mx-auto">

        <!-- Header Actions -->
        <div
            class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <div>
                <div class="flex items-center gap-2 text-xs font-semibold text-emerald-600 mb-1">
                    <i class="fa-regular fa-calendar-days"></i> {{ $menu->tanggal->format('l, d F Y') }}
                </div>
                <h3 class="font-extrabold text-slate-900 text-xl">{{ $menu->nama_menu }}</h3>
                <div class="flex flex-wrap gap-2 items-center mt-3">
                    <a href="{{ route('export.po.pdf', $menu->id) }}" target="_blank"
                        class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs rounded-xl shadow-sm transition-all flex items-center gap-1.5">
                        <i class="fa-solid fa-file-pdf"></i> Cetak PO
                    </a>
                    <a href="{{ route('export.po.qc-form', $menu->id) }}" target="_blank"
                        class="px-3.5 py-2 bg-slate-700 hover:bg-slate-800 text-white font-semibold text-xs rounded-xl shadow-sm transition-all flex items-center gap-1.5">
                        <i class="fa-solid fa-clipboard-check"></i> Form QC Manual
                    </a>
                    <a href="{{ route('export.po.invoice-pdf', $menu->id) }}" target="_blank"
                        class="px-3.5 py-2 bg-sky-600 hover:bg-sky-700 text-white font-semibold text-xs rounded-xl shadow-sm transition-all flex items-center gap-1.5">
                        <i class="fa-solid fa-file-invoice-dollar"></i> Cetak Invoice
                    </a>
                </div>
            </div>
            <div class="flex items-center gap-2.5">
                <a href="{{ route('po.edit', $menu->id) }}"
                    class="px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-white font-semibold text-xs rounded-xl shadow-md transition-all flex items-center gap-2">
                    <i class="fa-solid fa-pen-to-square"></i> Edit PO / Cover Bahan
                </a>

                @if (!$menu->inspection)
                    <a href="{{ route('inspection.check', $menu->id) }}"
                        class="px-4 py-2.5 bg-teal-600 hover:bg-teal-700 text-white font-semibold text-xs rounded-xl shadow-md shadow-teal-600/20 transition-all flex items-center gap-2">
                        <i class="fa-solid fa-clipboard-check"></i> Mulai Pengecekan (QC)
                    </a>
                @else
                    <a href="{{ route('inspection.show', $menu->inspection->id) }}"
                        class="px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs rounded-xl transition-all flex items-center gap-2">
                        <i class="fa-solid fa-square-check text-emerald-400"></i> Lihat Hasil QC
                    </a>
                @endif
                <a href="{{ route('po.index') }}"
                    class="px-4 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold text-xs rounded-xl transition-colors">
                    Kembali
                </a>
            </div>
        </div>

        <!-- PO Details Card -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex flex-wrap items-center justify-between gap-4">
                <div class="space-y-1">
                    <span class="text-xs text-slate-400 uppercase tracking-wider font-semibold">Kategori Menu</span>
                    <p class="font-bold text-slate-800 text-sm">{{ $menu->kategori }}</p>
                </div>
                <div class="space-y-1">
                    <span class="text-xs text-slate-400 uppercase tracking-wider font-semibold">Total Item Bahan</span>
                    <p class="font-bold text-slate-800 text-sm">{{ $menu->poItems->count() }} Bahan</p>
                </div>
                <div class="space-y-1">
                    <span class="text-xs text-slate-400 uppercase tracking-wider font-semibold">Total Anggaran PO</span>
                    <p class="font-extrabold text-emerald-600 text-lg">Rp {{ number_format($menu->total_po, 0, ',', '.') }}
                    </p>
                </div>
            </div>

            @if ($menu->catatan)
                <div class="p-4 bg-amber-50/60 border-b border-amber-100 text-xs text-amber-900 flex items-start gap-2.5">
                    <i class="fa-solid fa-circle-info text-amber-600 mt-0.5"></i>
                    <div>
                        <span class="font-bold">Catatan Menu:</span> {{ $menu->catatan }}
                    </div>
                </div>
            @endif

            <!-- Table of Items -->
            <div class="p-6">
                <h4 class="font-bold text-slate-800 text-base mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-list text-emerald-600"></i> Rincian Bahan Dipesan (PO)
                </h4>
                <div class="overflow-x-auto border border-slate-200 rounded-2xl">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-100 text-slate-600 text-xs uppercase font-semibold">
                            <tr>
                                <th class="p-3.5 w-12 text-center">No</th>
                                <th class="p-3.5">Nama Item Bahan</th>
                                <th class="p-3.5">Merk</th>
                                <th class="p-3.5 text-center">Qty Dipesan</th>
                                <th class="p-3.5">Satuan</th>
                                <th class="p-3.5 text-right">Harga Satuan</th>
                                <th class="p-3.5 text-right">Total Harga</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach ($menu->poItems as $index => $item)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="p-3.5 text-center text-xs font-bold text-slate-400">{{ $index + 1 }}</td>
                                    <td class="p-3.5 font-semibold text-slate-800">{{ $item->nama_bahan }}</td>
                                    <td class="p-3.5 text-slate-600">{{ $item->merk ?: '-' }}</td>
                                    <td class="p-3.5 text-center font-bold text-slate-800">
                                        {{ number_format($item->qty_pesan, 2) }}</td>
                                    <td class="p-3.5 text-slate-600 text-xs">{{ $item->satuan }}</td>
                                    <td class="p-3.5 text-right text-slate-700">Rp
                                        {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                                    <td class="p-3.5 text-right font-extrabold text-emerald-700">Rp
                                        {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-slate-50 font-bold text-slate-900 border-t border-slate-200">
                            <tr>
                                <td colspan="6" class="p-4 text-right">TOTAL KESELURUHAN PO:</td>
                                <td class="p-4 text-right text-emerald-600 text-base">Rp
                                    {{ number_format($menu->total_po, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection
