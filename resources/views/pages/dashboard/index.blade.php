@extends('layouts.app')

@section('title', 'Dashboard Overview')
@section('page_title', 'Dashboard Overview')

@section('content')
<div class="space-y-6">

    <!-- Hero Welcome Banner -->
    <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-emerald-950 rounded-3xl p-6 md:p-8 text-white shadow-xl relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative z-10 max-w-3xl">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 mb-3">
                <i class="fa-solid fa-sparkles text-emerald-400"></i> Sistem Pengadaan MBG
            </span>
            <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">Manajemen PO, Pengecekan Kualitas & Invoice</h1>
            <p class="mt-2 text-slate-300 text-sm leading-relaxed">
                Kelola pemesanan bahan masakan MBG per tanggal dan menu, lakukan verifikasi mutu barang datang (kesesuaian jumlah, warna & aroma), hingga penerbitan invoice dan ekspor laporan PDF/Excel.
            </p>
            <div class="mt-6 flex flex-wrap gap-3">
                <a href="{{ route('po.create') }}" class="px-5 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white font-semibold text-sm rounded-xl shadow-lg shadow-emerald-500/30 transition-all flex items-center gap-2">
                    <i class="fa-solid fa-plus"></i> Input PO Bahan Baru
                </a>
                <a href="{{ route('inspection.index') }}" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-200 font-semibold text-sm rounded-xl border border-slate-700 transition-all flex items-center gap-2">
                    <i class="fa-solid fa-clipboard-check text-emerald-400"></i> Pengecekan Barang Datang
                </a>
            </div>
        </div>
    </div>

    <!-- Metric KPI Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        
        <!-- Total PO Card -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Menu & PO</span>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                    <i class="fa-solid fa-cart-shopping"></i>
                </div>
            </div>
            <div class="mt-4">
                <div class="text-2xl font-extrabold text-slate-900">{{ number_format($totalPo) }}</div>
                <div class="mt-1 text-xs text-slate-500">Estimasi Biaya: <span class="font-semibold text-slate-700">Rp {{ number_format($totalPoBiaya, 0, ',', '.') }}</span></div>
            </div>
        </div>

        <!-- Total Inspeksi QC -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">QC Pengecekan</span>
                <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center font-bold">
                    <i class="fa-solid fa-square-check"></i>
                </div>
            </div>
            <div class="mt-4">
                <div class="text-2xl font-extrabold text-slate-900">{{ number_format($totalInspection) }}</div>
                <div class="mt-1 text-xs text-teal-600 font-semibold"><i class="fa-solid fa-check-double mr-1"></i> Telah Diverifikasi</div>
            </div>
        </div>

        <!-- Total Invoice -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Invoice Diterbitkan</span>
                <div class="w-10 h-10 rounded-xl bg-cyan-50 text-cyan-600 flex items-center justify-center font-bold">
                    <i class="fa-solid fa-file-invoice"></i>
                </div>
            </div>
            <div class="mt-4">
                <div class="text-2xl font-extrabold text-slate-900">{{ number_format($totalInvoice) }}</div>
                <div class="mt-1 text-xs text-slate-500">Nominal: <span class="font-semibold text-cyan-600">Rp {{ number_format($totalNominalInvoice, 0, ',', '.') }}</span></div>
            </div>
        </div>

        <!-- Quick Export PDF/Excel -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Export Laporan</span>
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold">
                    <i class="fa-solid fa-file-export"></i>
                </div>
            </div>
            <div class="mt-3 flex gap-2">
                <a href="{{ route('export.excel', 'po') }}" class="flex-1 text-center py-2 px-3 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-semibold rounded-lg transition-colors">
                    <i class="fa-solid fa-file-excel mr-1"></i> PO
                </a>
                <a href="{{ route('export.excel', 'inspection') }}" class="flex-1 text-center py-2 px-3 bg-teal-50 hover:bg-teal-100 text-teal-700 text-xs font-semibold rounded-lg transition-colors">
                    <i class="fa-solid fa-file-excel mr-1"></i> QC
                </a>
                <a href="{{ route('export.excel', 'invoice') }}" class="flex-1 text-center py-2 px-3 bg-cyan-50 hover:bg-cyan-100 text-cyan-700 text-xs font-semibold rounded-lg transition-colors">
                    <i class="fa-solid fa-file-excel mr-1"></i> Invoice
                </a>
            </div>
        </div>

    </div>

    <!-- Recent Menus & PO Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-slate-800 text-base">Menu & Input PO Bahan Terkini</h3>
                <p class="text-xs text-slate-500">Daftar menu masakan MBG dan rincian pemesanan bahan</p>
            </div>
            <a href="{{ route('po.index') }}" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700">Lihat Semua PO <i class="fa-solid fa-arrow-right ml-1"></i></a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500 text-xs uppercase font-semibold border-b border-slate-100">
                    <tr>
                        <th class="px-5 py-3.5">Tanggal</th>
                        <th class="px-5 py-3.5">Nama Menu</th>
                        <th class="px-5 py-3.5">Kategori</th>
                        <th class="px-5 py-3.5 text-center">Jumlah Item</th>
                        <th class="px-5 py-3.5 text-right">Total PO</th>
                        <th class="px-5 py-3.5 text-center">Status QC</th>
                        <th class="px-5 py-3.5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($latestMenus as $menu)
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
                                {{ $menu->poItems->count() }} Bahan
                            </td>
                            <td class="px-5 py-4 text-right font-bold text-emerald-700">
                                Rp {{ number_format($menu->total_po, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-4 text-center">
                                @if($menu->inspection)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <i class="fa-solid fa-circle-check text-emerald-500"></i> {{ $menu->inspection->status_keseluruhan }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                        <i class="fa-solid fa-clock text-amber-500"></i> Menunggu QC
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('po.show', $menu->id) }}" class="p-1.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg text-xs" title="Detail PO">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    @if(!$menu->inspection)
                                        <a href="{{ route('inspection.check', $menu->id) }}" class="p-1.5 bg-teal-500 hover:bg-teal-600 text-white rounded-lg text-xs" title="Pengecekan QC">
                                            <i class="fa-solid fa-clipboard-check"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center text-slate-400">
                                <i class="fa-solid fa-inbox text-3xl mb-2 block"></i>
                                Belum ada data PO bahan yang diinputkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
