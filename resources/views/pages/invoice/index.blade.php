@extends('layouts.app')

@section('title', 'Daftar Invoice MBG')
@section('page_title', '3. Penerbitan & Kelola Invoice')

@section('content')
<div class="space-y-6">

    <!-- Header Actions & Filter -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
        <div>
            <h3 class="font-bold text-slate-800 text-lg">Invoice Terbit MBG</h3>
            <p class="text-xs text-slate-500">Daftar invoice resmi berdasarkan rincian barang yang diterima dan lolos pengecekan QC</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('export.excel', 'invoice') }}" class="px-4 py-2.5 bg-cyan-50 hover:bg-cyan-100 text-cyan-700 font-semibold text-xs rounded-xl border border-cyan-200 transition-colors flex items-center gap-2">
                <i class="fa-solid fa-file-excel"></i> Export Excel Invoice
            </a>
        </div>
    </div>

    <!-- Filter Bar -->
    <form method="GET" action="{{ route('invoice.index') }}" class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex flex-wrap items-center gap-4">
        <div class="flex-1 min-w-[200px]">
            <label class="block text-xs font-semibold text-slate-500 mb-1">Status Invoice</label>
            <select name="status" class="w-full text-xs p-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-cyan-500 focus:outline-none">
                <option value="">-- Semua Status --</option>
                <option value="diterbitkan" {{ request('status') == 'diterbitkan' ? 'selected' : '' }}>Diterbitkan</option>
                <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
            </select>
        </div>

        <div class="flex-2 min-w-[240px]">
            <label class="block text-xs font-semibold text-slate-500 mb-1">Cari Nomor Invoice / Menu</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="INV-MBG-..." class="w-full text-xs p-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-cyan-500 focus:outline-none">
        </div>

        <div class="flex items-end pt-4">
            <button type="submit" class="px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs rounded-xl transition-colors">
                <i class="fa-solid fa-filter mr-1"></i> Filter
            </button>
        </div>
    </form>

    <!-- Table Invoices -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500 text-xs uppercase font-semibold border-b border-slate-100">
                    <tr>
                        <th class="px-5 py-3.5">Nomor Invoice</th>
                        <th class="px-5 py-3.5">Tanggal Invoice</th>
                        <th class="px-5 py-3.5">Menu Terkait</th>
                        <th class="px-5 py-3.5 text-right">Subtotal PO</th>
                        <th class="px-5 py-3.5 text-right">Potongan QC</th>
                        <th class="px-5 py-3.5 text-right">Total Tagihan</th>
                        <th class="px-5 py-3.5 text-center">Status</th>
                        <th class="px-5 py-3.5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($invoices as $inv)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-5 py-4 font-extrabold text-slate-900 font-mono">
                                <a href="{{ route('invoice.show', $inv->id) }}" class="text-cyan-700 hover:underline">
                                    {{ $inv->nomor_invoice }}
                                </a>
                            </td>
                            <td class="px-5 py-4 text-slate-600 whitespace-nowrap">
                                <i class="fa-regular fa-calendar-days text-slate-400 mr-1.5"></i> {{ $inv->tanggal_invoice->format('d M Y') }}
                            </td>
                            <td class="px-5 py-4 font-semibold text-slate-800">
                                {{ $inv->menu->nama_menu ?? '-' }}
                            </td>
                            <td class="px-5 py-4 text-right text-slate-600">
                                Rp {{ number_format($inv->subtotal_po, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-4 text-right text-rose-600 font-medium">
                                - Rp {{ number_format($inv->potongan_ketidaksesuaian, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-4 text-right font-extrabold text-cyan-700 text-base">
                                Rp {{ number_format($inv->total_tagihan, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-4 text-center">
                                @if($inv->status == 'lunas')
                                    <span class="px-3 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 font-bold rounded-full text-xs">
                                        <i class="fa-solid fa-circle-check"></i> Lunas
                                    </span>
                                @elseif($inv->status == 'diterbitkan')
                                    <span class="px-3 py-1 bg-cyan-50 text-cyan-700 border border-cyan-200 font-bold rounded-full text-xs">
                                        <i class="fa-solid fa-paper-plane"></i> Diterbitkan
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-slate-100 text-slate-600 border border-slate-200 font-bold rounded-full text-xs">
                                        Draft
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('invoice.show', $inv->id) }}" class="p-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-semibold flex items-center gap-1" title="Lihat Invoice">
                                        <i class="fa-solid fa-eye"></i> Detail
                                    </a>
                                    <a href="{{ route('export.invoice.pdf', $inv->id) }}" class="p-2 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg text-xs font-semibold flex items-center gap-1" title="Download PDF">
                                        <i class="fa-solid fa-file-pdf"></i> PDF
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-12 text-center text-slate-400">
                                Belum ada data invoice yang diterbitkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $invoices->links() }}
        </div>
    </div>

</div>
@endsection
