@extends('layouts.app')

@section('title', 'Daftar PO Bahan MBG')
@section('page_title', 'Daftar Pemesanan Bahan (PO)')

@section('content')
<div class="space-y-6">

    <!-- Header Actions & Search -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
        <div>
            <h3 class="font-bold text-slate-800 text-lg">Input & Daftar PO Bahan MBG</h3>
            <p class="text-xs text-slate-500">Akuntan menginputkan bahan masakan berdasarkan tanggal & menu</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('export.excel', 'po') }}" class="px-4 py-2.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-semibold text-xs rounded-xl border border-emerald-200 transition-colors flex items-center gap-2">
                <i class="fa-solid fa-file-excel"></i> Export Excel PO
            </a>
            <a href="{{ route('po.create') }}" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs rounded-xl shadow-md shadow-emerald-600/20 transition-all flex items-center gap-2">
                <i class="fa-solid fa-plus"></i> Input PO Bahan Baru
            </a>
        </div>
    </div>

    <!-- Filter Form -->
    <form method="GET" action="{{ route('po.index') }}" class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex flex-wrap items-center gap-4">
        <div class="flex-1 min-w-[200px]">
            <label class="block text-xs font-semibold text-slate-500 mb-1">Tanggal Masak/Menu</label>
            <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="w-full text-xs p-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
        </div>
        <div class="flex-2 min-w-[240px]">
            <label class="block text-xs font-semibold text-slate-500 mb-1">Cari Nama Menu</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Misal: Nasi Ayam Bakar..." class="w-full text-xs p-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
        </div>
        <div class="flex items-end gap-2 pt-4">
            <button type="submit" class="px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs rounded-xl transition-colors">
                <i class="fa-solid fa-filter mr-1"></i> Filter
            </button>
            @if(request('tanggal') || request('search'))
                <a href="{{ route('po.index') }}" class="px-3 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold text-xs rounded-xl transition-colors">
                    Reset
                </a>
            @endif
        </div>
    </form>

    <!-- PO Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500 text-xs uppercase font-semibold border-b border-slate-100">
                    <tr>
                        <th class="px-5 py-3.5">Tanggal</th>
                        <th class="px-5 py-3.5">Nama Menu</th>
                        <th class="px-5 py-3.5">Kategori</th>
                        <th class="px-5 py-3.5 text-center">Bahan Dipesan</th>
                        <th class="px-5 py-3.5 text-right">Total Anggaran PO</th>
                        <th class="px-5 py-3.5 text-center">Status Pengecekan</th>
                        <th class="px-5 py-3.5 text-center">Invoice</th>
                        <th class="px-5 py-3.5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($menus as $menu)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-5 py-4 font-medium text-slate-900 whitespace-nowrap">
                                <i class="fa-regular fa-calendar-days text-slate-400 mr-2"></i> {{ $menu->tanggal->format('d M Y') }}
                            </td>
                            <td class="px-5 py-4 font-semibold text-slate-800">
                                {{ $menu->nama_menu }}
                            </td>
                            <td class="px-5 py-4 text-xs">
                                <span class="px-2.5 py-1 bg-slate-100 text-slate-600 font-medium rounded-md border border-slate-200">
                                    {{ $menu->kategori }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-center font-medium text-slate-700">
                                <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 rounded-md font-bold text-xs">
                                    {{ $menu->poItems->count() }} Item
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right font-extrabold text-emerald-700">
                                Rp {{ number_format($menu->total_po, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-4 text-center">
                                @if($menu->inspection)
                                    <a href="{{ route('inspection.show', $menu->inspection->id) }}" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <i class="fa-solid fa-circle-check text-emerald-500"></i> {{ $menu->inspection->status_keseluruhan }}
                                    </a>
                                @else
                                    <a href="{{ route('inspection.check', $menu->id) }}" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200 hover:bg-amber-100 transition-colors">
                                        <i class="fa-solid fa-clock text-amber-500"></i> Cek Bahan Datang
                                    </a>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-center">
                                @if($menu->invoice)
                                    <a href="{{ route('invoice.show', $menu->invoice->id) }}" class="text-xs font-bold text-cyan-600 hover:underline">
                                        {{ $menu->invoice->nomor_invoice }}
                                    </a>
                                @else
                                    <span class="text-xs text-slate-400 italic">Belum terbit</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('po.show', $menu->id) }}" class="p-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs" title="Lihat Detail">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <a href="{{ route('po.edit', $menu->id) }}" class="p-2 bg-amber-50 hover:bg-amber-100 text-amber-600 rounded-lg text-xs" title="Edit PO / Cover Tambahan Bahan">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <a href="{{ route('export.po.pdf', $menu->id) }}" target="_blank" class="p-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-600 rounded-lg text-xs" title="Cetak PO PDF">
                                        <i class="fa-solid fa-file-pdf"></i>
                                    </a>
                                    <a href="{{ route('export.po.qc-form', $menu->id) }}" target="_blank" class="p-2 bg-sky-50 hover:bg-sky-100 text-sky-600 rounded-lg text-xs" title="Cetak Form QC Manual Checklist">
                                        <i class="fa-solid fa-clipboard-check"></i>
                                    </a>
                                    <form action="{{ route('po.destroy', $menu->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus PO ini?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg text-xs" title="Hapus">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-12 text-center text-slate-400">
                                <i class="fa-solid fa-folder-open text-3xl mb-2 block"></i>
                                Belum ada data PO bahan yang sesuai dengan kriteria filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $menus->withQueryString()->links() }}
        </div>
    </div>

</div>
@endsection
