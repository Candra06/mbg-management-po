@extends('layouts.app')

@section('title', 'Pengecekan Bahan Datang (QC)')
@section('page_title', '2. Pengecekan Kualitas & Penerimaan Bahan')

@section('content')
    <div class="space-y-6">

        <!-- Header Banner & Selector -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm space-y-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h3 class="font-extrabold text-slate-900 text-lg">Pengecekan Kualitas (QC) Bahan Datang</h3>
                    <p class="text-xs text-slate-500">Pilih Tanggal dan Menu untuk memunculkan daftar bahan yang dipesan,
                        lalu lakukan verifikasi kesesuaian jumlah, warna & aroma.</p>
                </div>
                <a href="{{ route('export.excel', 'inspection') }}"
                    class="px-4 py-2.5 bg-teal-50 hover:bg-teal-100 text-teal-700 font-semibold text-xs rounded-xl border border-teal-200 transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-file-excel"></i> Export Excel Rekap QC
                </a>
            </div>

            <!-- Filter Selector Form: Pilih Tanggal & Menu -->
            <form method="GET" action="{{ route('inspection.index') }}"
                class="bg-slate-50 p-4 rounded-2xl border border-slate-200 flex flex-wrap items-center gap-4">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-xs font-bold text-slate-700 mb-1">1. Pilih Tanggal Datang</label>
                    <input type="date" name="tanggal" value="{{ $selectedTanggal }}" onchange="this.form.submit()"
                        class="w-full text-xs p-2.5 rounded-xl border border-slate-300 font-semibold focus:ring-2 focus:ring-teal-500 focus:outline-none">
                </div>

                <div class="flex-2 min-w-[260px]">
                    <label class="block text-xs font-bold text-slate-700 mb-1">2. Pilih Menu Masakan</label>
                    <select name="menu_id" onchange="this.form.submit()"
                        class="w-full text-xs p-2.5 rounded-xl border border-slate-300 font-semibold focus:ring-2 focus:ring-teal-500 focus:outline-none">
                        @forelse($menusOnDate as $menuItem)
                            <option value="{{ $menuItem->id }}"
                                {{ $selectedMenu && $selectedMenu->id == $menuItem->id ? 'selected' : '' }}>
                                {{ $menuItem->nama_menu }} ({{ $menuItem->poItems->count() }} Item PO)
                                {{ $menuItem->inspection ? '✓ Submited' : '⌛ Pending' }}
                            </option>
                        @empty
                            <option value="">-- Tidak ada PO pada tanggal ini --</option>
                        @endforelse
                    </select>
                </div>

                <div class="flex items-end pt-4">
                    <button type="submit"
                        class="px-4 py-2.5 bg-teal-600 hover:bg-teal-700 text-white font-semibold text-xs rounded-xl transition-colors">
                        <i class="fa-solid fa-magnifying-glass mr-1"></i> Tampilkan List Bahan
                    </button>
                </div>
            </form>
        </div>

        <!-- Selected Menu Preview Card -->
        @if ($selectedMenu)
            <div class="bg-gradient-to-r from-slate-900 to-slate-800 rounded-3xl p-6 text-white shadow-lg space-y-4">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2 text-xs font-medium text-teal-400">
                            <i class="fa-regular fa-calendar-check"></i> Tanggal Menu:
                            {{ $selectedMenu->tanggal->format('d F Y') }}
                        </div>
                        <h3 class="text-xl font-extrabold mt-1 text-white">{{ $selectedMenu->nama_menu }}</h3>
                    </div>

                    <div>
                        @if ($selectedMenu->inspection)
                            <div class="flex items-center gap-3">
                                <span
                                    class="px-3 py-1.5 rounded-full text-xs font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                                    <i class="fa-solid fa-circle-check"></i> QC Selesai:
                                    {{ $selectedMenu->inspection->status_keseluruhan }}
                                </span>
                                <a href="{{ route('inspection.show', $selectedMenu->inspection->id) }}"
                                    class="px-4 py-2 bg-white text-slate-900 hover:bg-slate-100 font-bold text-xs rounded-xl transition-colors">
                                    Lihat Laporan QC
                                </a>
                            </div>
                        @else
                            <a href="{{ route('inspection.check', $selectedMenu->id) }}"
                                class="px-5 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-xs rounded-xl shadow-lg shadow-emerald-500/30 transition-all flex items-center gap-2">
                                <i class="fa-solid fa-clipboard-check"></i> Mulai Form Pengecekan Bahan
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <!-- All Inspection History Table -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100">
                <h3 class="font-bold text-slate-800 text-base">Riwayat Pengecekan (QC) Terakhir</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-slate-500 text-xs uppercase font-semibold border-b border-slate-100">
                        <tr>
                            <th class="px-5 py-3.5">Waktu Pengecekan</th>
                            <th class="px-5 py-3.5">Menu</th>
                            <th class="px-5 py-3.5">Pemeriksa</th>
                            <th class="px-5 py-3.5 text-center">Hasil Sesuai</th>
                            <th class="px-5 py-3.5 text-center">Hasil Tidak Sesuai</th>
                            <th class="px-5 py-3.5 text-center">Status Keseluruhan</th>
                            <th class="px-5 py-3.5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($allInspections as $ins)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-5 py-4 font-medium text-slate-800 whitespace-nowrap">
                                    {{ $ins->tanggal_cek ? $ins->tanggal_cek->format('d M Y H:i') : '-' }}
                                </td>
                                <td class="px-5 py-4 font-semibold text-slate-900">
                                    {{ $ins->menu->nama_menu ?? '-' }}
                                </td>
                                <td class="px-5 py-4 text-slate-600">
                                    <i class="fa-solid fa-user-shield text-slate-400 mr-1.5"></i>
                                    {{ $ins->nama_pemeriksa }}
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <span
                                        class="px-2.5 py-1 {{ $ins->total_sesuai > 0 ? 'bg-emerald-50 text-emerald-700 border-emerald-200 font-extrabold' : 'bg-slate-50 text-slate-400' }} rounded-md text-xs border">
                                        {{ $ins->total_sesuai }} <i class="fa-solid fa-square-check"></i>
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <span
                                        class="px-2.5 py-1 {{ $ins->total_tidak_sesuai > 0 ? 'bg-rose-50 text-rose-700 border-rose-200 font-extrabold' : 'bg-slate-50 text-slate-400' }} rounded-md text-xs border">
                                        {{ $ins->total_tidak_sesuai }} <i class="fa-solid fa-xmark"></i>
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <span
                                        class="px-3 py-1 bg-teal-50 text-teal-800 font-bold rounded-full text-xs border border-teal-200">
                                        {{ $ins->status_keseluruhan }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <a href="{{ route('inspection.show', $ins->id) }}"
                                        class="p-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-semibold inline-flex items-center gap-1">
                                        <i class="fa-solid fa-file-lines"></i> Rekap Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-12 text-center text-slate-400">
                                    Belum ada riwayat pengecekan bahan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $allInspections->links() }}
            </div>
        </div>

    </div>
@endsection
