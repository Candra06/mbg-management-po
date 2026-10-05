@extends('layouts.app')

@section('title', 'Invoice Resmi - ' . $invoice->nomor_invoice)
@section('page_title', 'Preview Invoice Resmi')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">

    <!-- Top Action Toolbar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm print:hidden">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-cyan-600 mb-1">
                <i class="fa-solid fa-file-invoice"></i> Tanggal Invoice: {{ $invoice->tanggal_invoice->format('d F Y') }}
            </div>
            <h3 class="font-extrabold text-slate-900 text-lg font-mono">{{ $invoice->nomor_invoice }}</h3>
        </div>

        <div class="flex items-center gap-3">
            <!-- Update Status Form -->
            <form action="{{ route('invoice.status', $invoice->id) }}" method="POST" class="flex items-center gap-2">
                @csrf
                @method('PATCH')
                <select name="status" onchange="this.form.submit()" class="text-xs p-2 rounded-xl border border-slate-300 font-bold focus:ring-2 focus:ring-cyan-500 focus:outline-none">
                    <option value="diterbitkan" {{ $invoice->status == 'diterbitkan' ? 'selected' : '' }}>Status: Diterbitkan</option>
                    <option value="lunas" {{ $invoice->status == 'lunas' ? 'selected' : '' }}>Status: Lunas</option>
                    <option value="draft" {{ $invoice->status == 'draft' ? 'selected' : '' }}>Status: Draft</option>
                </select>
            </form>

            <a href="{{ route('export.invoice.pdf', $invoice->id) }}" class="px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white font-semibold text-xs rounded-xl shadow-md shadow-rose-600/20 transition-all flex items-center gap-2">
                <i class="fa-solid fa-file-pdf"></i> Download PDF
            </a>

            <button onclick="window.print()" class="px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs rounded-xl transition-all flex items-center gap-2">
                <i class="fa-solid fa-print"></i> Cetak
            </button>
        </div>
    </div>

    <!-- Official Invoice Document Sheet -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xl p-8 md:p-12 space-y-8 text-slate-800">
        
        <!-- Document Header / Letterhead -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center border-b-2 border-slate-900 pb-6 gap-6">
            <div class="space-y-1">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-bold text-xl">
                        <i class="fa-solid fa-bowl-rice"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-extrabold text-slate-900 tracking-wider">PROGRAM MBG INDONESIA</h1>
                        <p class="text-xs font-semibold text-slate-500">Unit Penyedia Makanan Bergizi Gratis</p>
                    </div>
                </div>
            </div>

            <div class="text-left sm:text-right">
                <span class="inline-block text-xs font-extrabold tracking-widest text-slate-400 uppercase">INVOICE PENAGIHAN</span>
                <div class="text-2xl font-extrabold text-slate-900 font-mono mt-0.5">{{ $invoice->nomor_invoice }}</div>
                <div class="text-xs text-slate-500 font-medium">Tanggal: {{ $invoice->tanggal_invoice->format('d F Y') }}</div>
            </div>
        </div>

        <!-- Invoice Meta Details -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-slate-50 p-6 rounded-2xl border border-slate-200/80">
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Ditujukan Kepada / Supplier</span>
                <h4 class="font-extrabold text-slate-900 text-base">Mitra Supplier Bahan MBG</h4>
                <p class="text-xs text-slate-600 mt-1">Daftar Bahan Makanan Terverifikasi Lolos Pengecekan Quality Control</p>
            </div>
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Rincian Menu MBG</span>
                <div class="font-bold text-slate-900 text-base">{{ $invoice->menu->nama_menu }}</div>
                <div class="text-xs text-slate-600 mt-1"><i class="fa-regular fa-calendar-days mr-1"></i> Tanggal Menu: {{ $invoice->menu->tanggal->format('d F Y') }}</div>
            </div>
        </div>

        <!-- Line Items Table (Requirement 3) -->
        <div class="space-y-3">
            <h4 class="font-extrabold text-slate-900 text-sm uppercase tracking-wider">Rincian Barang Diterima, Qty, Satuan & Harga</h4>

            <div class="border border-slate-300 rounded-2xl overflow-hidden">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-900 text-white text-xs uppercase font-semibold">
                        <tr>
                            <th class="p-3.5 w-12 text-center">No</th>
                            <th class="p-3.5">Nama Barang / Bahan</th>
                            <th class="p-3.5">Merk</th>
                            <th class="p-3.5 text-center">Qty Diterima</th>
                            <th class="p-3.5 text-center">Satuan</th>
                            <th class="p-3.5 text-right">Harga Satuan</th>
                            <th class="p-3.5 text-right">Total Harga</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 text-slate-800">
                        @if($invoice->inspection && $invoice->inspection->inspectionItems->isNotEmpty())
                            @foreach($invoice->inspection->inspectionItems as $index => $insItem)
                                @php
                                    $poItem = $insItem->poItem;
                                    $subtotalLine = $insItem->qty_terima * ($poItem->harga_satuan ?? 0);
                                @endphp
                                <tr class="hover:bg-slate-50">
                                    <td class="p-3.5 text-center text-xs font-bold text-slate-400">{{ $index + 1 }}</td>
                                    <td class="p-3.5 font-bold text-slate-900">
                                        {{ $poItem->nama_bahan ?? '-' }}
                                    </td>
                                    <td class="p-3.5 text-slate-600 text-xs">{{ $poItem->merk ?: '-' }}</td>
                                    <td class="p-3.5 text-center font-extrabold text-slate-900">{{ number_format($insItem->qty_terima, 2) }}</td>
                                    <td class="p-3.5 text-center text-xs text-slate-600">{{ $poItem->satuan ?? '' }}</td>
                                    <td class="p-3.5 text-right text-slate-700">Rp {{ number_format($poItem->harga_satuan ?? 0, 0, ',', '.') }}</td>
                                    <td class="p-3.5 text-right font-extrabold text-slate-900">Rp {{ number_format($subtotalLine, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        @else
                            @foreach($invoice->menu->poItems as $index => $poItem)
                                <tr class="hover:bg-slate-50">
                                    <td class="p-3.5 text-center text-xs font-bold text-slate-400">{{ $index + 1 }}</td>
                                    <td class="p-3.5 font-bold text-slate-900">{{ $poItem->nama_bahan }}</td>
                                    <td class="p-3.5 text-slate-600 text-xs">{{ $poItem->merk ?: '-' }}</td>
                                    <td class="p-3.5 text-center font-extrabold text-slate-900">{{ number_format($poItem->qty_pesan, 2) }}</td>
                                    <td class="p-3.5 text-center text-xs text-slate-600">{{ $poItem->satuan }}</td>
                                    <td class="p-3.5 text-right text-slate-700">Rp {{ number_format($poItem->harga_satuan, 0, ',', '.') }}</td>
                                    <td class="p-3.5 text-right font-extrabold text-slate-900">Rp {{ number_format($poItem->total_harga, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Totals & Payment Summary -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-6 pt-4 border-t border-slate-200">
            <div class="space-y-2 max-w-sm">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Catatan Invoice</span>
                <p class="text-xs text-slate-600 leading-relaxed bg-slate-50 p-3 rounded-xl border border-slate-200">
                    {{ $invoice->catatan ?: 'Invoice ini sah diterbitkan berdasarkan verifikasi penerimaan barang dapur MBG.' }}
                </p>
            </div>

            <div class="w-full sm:w-80 space-y-3 bg-slate-50 p-5 rounded-2xl border border-slate-200">
                <div class="flex justify-between text-xs text-slate-600">
                    <span>Subtotal Anggaran PO:</span>
                    <span class="font-bold text-slate-800">Rp {{ number_format($invoice->subtotal_po, 0, ',', '.') }}</span>
                </div>
                @if($invoice->potongan_ketidaksesuaian > 0)
                    <div class="flex justify-between text-xs text-rose-600">
                        <span>Penyesuaian Ketidaksesuaian QC:</span>
                        <span class="font-bold">- Rp {{ number_format($invoice->potongan_ketidaksesuaian, 0, ',', '.') }}</span>
                    </div>
                @endif
                <div class="border-t border-slate-300 pt-3 flex justify-between items-center">
                    <span class="text-sm font-extrabold text-slate-900">GRAND TOTAL:</span>
                    <span class="text-xl font-black text-emerald-600">Rp {{ number_format($invoice->total_tagihan, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Signatures Footer Section -->
        <div class="grid grid-cols-2 gap-8 pt-12 text-center text-xs">
            <div class="space-y-12">
                <p class="font-semibold text-slate-500">Petugas Akuntansi / Finance</p>
                <div class="pt-4 border-b border-slate-400 w-40 mx-auto font-bold text-slate-800">( Akuntan MBG )</div>
            </div>
            <div class="space-y-12">
                <p class="font-semibold text-slate-500">Petugas Quality Control (QC)</p>
                <div class="pt-4 border-b border-slate-400 w-40 mx-auto font-bold text-slate-800">( {{ $invoice->inspection->nama_pemeriksa ?? 'QC Staff' }} )</div>
            </div>
        </div>

    </div>

</div>
@endsection
