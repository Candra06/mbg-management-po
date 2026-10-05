@extends('layouts.app')

@section('title', 'Hasil Rekapitulasi QC - ' . $inspection->menu->nama_menu)
@section('page_title', 'Hasil Rekapitulasi Pengecekan Kualitas')

@section('content')
<div class="space-y-6 max-w-5xl mx-auto">

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-teal-600 mb-1">
                <i class="fa-regular fa-calendar-check"></i> Waktu Cek: {{ $inspection->tanggal_cek ? $inspection->tanggal_cek->format('d M Y H:i WIB') : '-' }}
            </div>
            <h3 class="font-extrabold text-slate-900 text-xl">{{ $inspection->menu->nama_menu }}</h3>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('export.inspection.pdf', $inspection->id) }}" class="px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white font-semibold text-xs rounded-xl shadow-md shadow-rose-600/20 transition-all flex items-center gap-2">
                <i class="fa-solid fa-file-pdf"></i> Export PDF Laporan QC
            </a>
            @if($inspection->invoice)
                <a href="{{ route('invoice.show', $inspection->invoice->id) }}" class="px-4 py-2.5 bg-cyan-600 hover:bg-cyan-700 text-white font-semibold text-xs rounded-xl transition-all flex items-center gap-2">
                    <i class="fa-solid fa-file-invoice-dollar"></i> Lihat Invoice
                </a>
            @endif
            <a href="{{ route('inspection.index') }}" class="px-4 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold text-xs rounded-xl transition-colors">
                Kembali
            </a>
        </div>
    </div>

    <!-- Summary Rekap Banner -->
    <div class="bg-gradient-to-r from-slate-900 to-slate-800 rounded-3xl p-6 text-white shadow-lg grid grid-cols-1 sm:grid-cols-4 gap-4">
        <div class="bg-slate-800/80 p-4 rounded-2xl border border-slate-700">
            <span class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Petugas Pemeriksa</span>
            <div class="text-base font-bold text-white mt-1">{{ $inspection->nama_pemeriksa }}</div>
        </div>

        <div class="bg-emerald-950/80 p-4 rounded-2xl border border-emerald-800/60">
            <span class="text-xs text-emerald-400 font-semibold uppercase tracking-wider">Jumlah Items Sesuai</span>
            <div class="text-2xl font-extrabold text-emerald-300 mt-1">{{ $inspection->total_sesuai }} Sesuai</div>
        </div>

        <div class="bg-rose-950/80 p-4 rounded-2xl border border-rose-800/60">
            <span class="text-xs text-rose-400 font-semibold uppercase tracking-wider">Jumlah Items Tidak Sesuai</span>
            <div class="text-2xl font-extrabold text-rose-300 mt-1">{{ $inspection->total_tidak_sesuai }} Tidak Sesuai</div>
        </div>

        <div class="bg-teal-950/80 p-4 rounded-2xl border border-teal-800/60">
            <span class="text-xs text-teal-400 font-semibold uppercase tracking-wider">Status Keseluruhan</span>
            <div class="text-base font-extrabold text-teal-200 mt-1">{{ $inspection->status_keseluruhan }}</div>
        </div>
    </div>

    <!-- Inspection Items Detail Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 space-y-4">
        <h4 class="font-bold text-slate-800 text-base flex items-center gap-2 border-b border-slate-100 pb-3">
            <i class="fa-solid fa-square-poll-vertical text-teal-600"></i> Rekapitulasi Pengecekan Per Item Bahan
        </h4>

        <div class="overflow-x-auto border border-slate-200 rounded-2xl">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-100 text-slate-600 text-xs uppercase font-semibold">
                    <tr>
                        <th class="p-3.5">Nama Bahan</th>
                        <th class="p-3.5 text-center">Qty Pesan</th>
                        <th class="p-3.5 text-center">Qty Terima</th>
                        <th class="p-3.5 text-center">Warna</th>
                        <th class="p-3.5 text-center">Aroma</th>
                        <th class="p-3.5 text-center">Status Pengecekan</th>
                        <th class="p-3.5">Catatan QC</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @foreach($inspection->inspectionItems as $item)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="p-3.5 font-bold text-slate-800">
                                {{ $item->poItem->nama_bahan ?? '-' }}
                                <div class="text-xs text-slate-400 font-normal">Merk: {{ $item->poItem->merk ?: '-' }}</div>
                            </td>
                            <td class="p-3.5 text-center text-slate-600 font-medium">
                                {{ number_format($item->poItem->qty_pesan ?? 0, 2) }} {{ $item->poItem->satuan ?? '' }}
                            </td>
                            <td class="p-3.5 text-center font-extrabold text-slate-900">
                                {{ number_format($item->qty_terima, 2) }} {{ $item->poItem->satuan ?? '' }}
                            </td>
                            <td class="p-3.5 text-center">
                                <span class="px-2.5 py-1 rounded-md text-xs font-semibold {{ strtolower($item->warna) == 'segar/normal' ? 'bg-slate-100 text-slate-700' : 'bg-amber-100 text-amber-800' }}">
                                    {{ $item->warna }}
                                </span>
                            </td>
                            <td class="p-3.5 text-center">
                                <span class="px-2.5 py-1 rounded-md text-xs font-semibold {{ strtolower($item->aroma) == 'segar/normal' ? 'bg-slate-100 text-slate-700' : 'bg-amber-100 text-amber-800' }}">
                                    {{ $item->aroma }}
                                </span>
                            </td>
                            <td class="p-3.5 text-center">
                                @if($item->status_jumlah == 'sesuai')
                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-full font-extrabold text-xs">
                                        <i class="fa-solid fa-check"></i> Sesuai
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-rose-50 text-rose-700 border border-rose-200 rounded-full font-extrabold text-xs">
                                        <i class="fa-solid fa-xmark"></i> Tidak Sesuai
                                    </span>
                                @endif
                            </td>
                            <td class="p-3.5 text-xs text-slate-600">
                                {{ $item->catatan ?: '-' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
