@extends('layouts.app')

@section('title', 'Form Pengecekan Bahan - ' . $menu->nama_menu)
@section('page_title', 'Form Verification & Quality Control (QC)')

@section('content')
<div x-data="checkForm()" class="space-y-6 max-w-5xl mx-auto">

    <!-- Header & Info Card -->
    <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs font-bold text-teal-600 mb-1">
                <i class="fa-regular fa-calendar-days"></i> Tanggal Menu: {{ $menu->tanggal->format('l, d F Y') }}
            </div>
            <h3 class="font-extrabold text-slate-900 text-xl">{{ $menu->nama_menu }}</h3>
            <p class="text-xs text-slate-500 mt-1">Lakukan pengecekan fisik barang datang (Qty terima, warna & aroma)</p>
        </div>
        <a href="{{ route('inspection.index') }}" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold text-xs rounded-xl transition-colors">
            Kembali
        </a>
    </div>

    <!-- Dynamic QC Form -->
    <form action="{{ route('inspection.store', $menu->id) }}" method="POST" class="space-y-6">
        @csrf

        <!-- Examiner Info Card -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm space-y-4">
            <h4 class="font-bold text-slate-800 text-sm flex items-center gap-2 border-b border-slate-100 pb-3">
                <i class="fa-solid fa-user-shield text-teal-600"></i> Informasi Petugas Pemeriksa & Catatan Umum
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Petugas Quality Control / Receiver <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama_pemeriksa" value="{{ old('nama_pemeriksa', 'Budi Santoso (QC Staff)') }}" required class="w-full text-sm p-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-teal-500 focus:outline-none shadow-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Catatan Ringkas Pemeriksaan</label>
                    <input type="text" name="catatan_umum" placeholder="Misal: Kemasan rapi, pengiriman jam 07:30 WIB" class="w-full text-sm p-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-teal-500 focus:outline-none shadow-sm">
                </div>
            </div>
        </div>

        <!-- Real-time Rekap Summary Badge Card -->
        <div class="bg-gradient-to-r from-slate-900 to-slate-800 rounded-3xl p-6 text-white shadow-lg grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-slate-800/80 p-4 rounded-2xl border border-slate-700">
                <span class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Total Item Bahan</span>
                <div class="text-2xl font-extrabold text-white mt-1" x-text="items.length"></div>
            </div>

            <div class="bg-emerald-950/80 p-4 rounded-2xl border border-emerald-800/60">
                <span class="text-xs text-emerald-400 font-semibold uppercase tracking-wider"><i class="fa-solid fa-circle-check mr-1"></i> Rekap Sesuai</span>
                <div class="text-2xl font-extrabold text-emerald-300 mt-1" x-text="rekapSesuai + ' Item'"></div>
            </div>

            <div class="bg-rose-950/80 p-4 rounded-2xl border border-rose-800/60">
                <span class="text-xs text-rose-400 font-semibold uppercase tracking-wider"><i class="fa-solid fa-circle-xmark mr-1"></i> Rekap Tidak Sesuai</span>
                <div class="text-2xl font-extrabold text-rose-300 mt-1" x-text="rekapTidakSesuai + ' Item'"></div>
            </div>
        </div>

        <!-- Table Inspection List -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden p-6 space-y-4">
            <h4 class="font-bold text-slate-800 text-sm flex items-center gap-2 border-b border-slate-100 pb-3">
                <i class="fa-solid fa-list-check text-teal-600"></i> Verifikasi Fisik Bahan Dipesan vs Bahan Datang
            </h4>

            <div class="overflow-x-auto border border-slate-200 rounded-2xl">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-100 text-slate-600 text-xs uppercase font-semibold">
                        <tr>
                            <th class="p-3.5">Nama Bahan / Merk</th>
                            <th class="p-3.5 text-center w-28">Qty Pesan</th>
                            <th class="p-3.5 text-center w-32">Qty Terima <span class="text-rose-500">*</span></th>
                            <th class="p-3.5 w-36">Identifikasi Warna <span class="text-rose-500">*</span></th>
                            <th class="p-3.5 w-36">Identifikasi Aroma <span class="text-rose-500">*</span></th>
                            <th class="p-3.5 text-center w-32">Status Jumlah</th>
                            <th class="p-3.5">Catatan QC</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @foreach($menu->poItems as $index => $poItem)
                            <tr class="hover:bg-slate-50 transition-colors" x-data="{
                                qtyPesan: {{ $poItem->qty_pesan }},
                                qtyTerima: {{ $poItem->qty_pesan }},
                                warna: 'Segar/Normal',
                                aroma: 'Segar/Normal',
                                get isSesuai() {
                                    return (Math.abs(this.qtyPesan - this.qtyTerima) < 0.01) &&
                                           (this.warna === 'Segar/Normal') &&
                                           (this.aroma === 'Segar/Normal');
                                }
                            }" x-init="
                                $watch('isSesuai', () => updateSummary())
                            ">
                                <input type="hidden" name="items[{{ $index }}][po_item_id]" value="{{ $poItem->id }}">
                                
                                <td class="p-3.5">
                                    <div class="font-bold text-slate-800">{{ $poItem->nama_bahan }}</div>
                                    <div class="text-xs text-slate-500">Merk: {{ $poItem->merk ?: '-' }}</div>
                                </td>

                                <td class="p-3.5 text-center font-bold text-slate-700">
                                    {{ number_format($poItem->qty_pesan, 2) }} {{ $poItem->satuan }}
                                </td>

                                <td class="p-3.5">
                                    <input type="number" step="0.01" min="0" name="items[{{ $index }}][qty_terima]" x-model.number="qtyTerima" @input="updateSummary()" required class="w-full text-xs p-2.5 rounded-lg border border-slate-300 text-center font-bold focus:ring-2 focus:ring-teal-500 focus:outline-none">
                                </td>

                                <td class="p-3.5">
                                    <select name="items[{{ $index }}][warna]" x-model="warna" @change="updateSummary()" required class="w-full text-xs p-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-teal-500 focus:outline-none">
                                        <option value="Segar/Normal">Segar / Normal</option>
                                        <option value="Pucat">Pucat</option>
                                        <option value="Perubahan Warna">Perubahan Warna</option>
                                        <option value="Memar/Bintik">Memar / Bintik</option>
                                    </select>
                                </td>

                                <td class="p-3.5">
                                    <select name="items[{{ $index }}][aroma]" x-model="aroma" @change="updateSummary()" required class="w-full text-xs p-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-teal-500 focus:outline-none">
                                        <option value="Segar/Normal">Segar / Normal</option>
                                        <option value="Berbau">Berbau Apek/Biasa</option>
                                        <option value="Tengik/Asam">Tengik / Asam</option>
                                        <option value="Busuk">Busuk / Tajam</option>
                                    </select>
                                </td>

                                <td class="p-3.5 text-center">
                                    <span x-show="isSesuai" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">
                                        <i class="fa-solid fa-check"></i> Sesuai
                                    </span>
                                    <span x-show="!isSesuai" x-cloak class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-800 border border-rose-300">
                                        <i class="fa-solid fa-xmark"></i> Tidak Sesuai
                                    </span>
                                </td>

                                <td class="p-3.5">
                                    <input type="text" name="items[{{ $index }}][catatan]" placeholder="opsional..." class="w-full text-xs p-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-teal-500 focus:outline-none">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Submit Button -->
            <div class="pt-4 flex justify-end">
                <button type="submit" class="px-8 py-3 bg-teal-600 hover:bg-teal-700 text-white font-extrabold text-sm rounded-xl shadow-lg shadow-teal-600/30 transition-all flex items-center gap-2">
                    <i class="fa-solid fa-circle-check"></i> Simpan Hasil Pengecekan & Terbitkan Invoice
                </button>
            </div>
        </div>
    </form>

</div>

<script>
    function checkForm() {
        return {
            itemsCount: {{ $menu->poItems->count() }},
            rekapSesuai: {{ $menu->poItems->count() }},
            rekapTidakSesuai: 0,
            updateSummary() {
                // Wait briefly for alpine data updates
                setTimeout(() => {
                    let sesuai = 0;
                    let tsk = 0;
                    document.querySelectorAll('tbody tr').forEach(tr => {
                        const isSesuaiBadge = tr.querySelector('span[x-show="isSesuai"]');
                        if (isSesuaiBadge && getComputedStyle(isSesuaiBadge).display !== 'none') {
                            sesuai++;
                        } else {
                            tsk++;
                        }
                    });
                    this.rekapSesuai = sesuai;
                    this.rekapTidakSesuai = tsk;
                }, 50);
            }
        }
    }
</script>
@endsection
