@extends('layouts.app')

@section('title', 'Edit PO Bahan')
@section('page_title', 'Form Edit PO Bahan & Tambahan Material')

@section('content')
<div x-data="poEditForm({{ \Illuminate\Support\Js::from($menu->poItems) }})" class="space-y-6 max-w-5xl mx-auto">

    <!-- Header & Back Button -->
    <div class="flex items-center justify-between">
        <div>
            <h3 class="font-extrabold text-slate-900 text-xl">Edit Pemesanan Bahan (PO)</h3>
            <p class="text-xs text-slate-500">Perbarui detail PO, ubah kuantitas/harga, atau tambah bahan masakan tambahan jika dibutuhkan.</p>
        </div>
        <a href="{{ route('po.show', $menu->id) }}" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold text-xs rounded-xl transition-colors flex items-center gap-2">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- PO Edit Form Card -->
    <form action="{{ route('po.update', $menu->id) }}" method="POST" class="bg-white rounded-3xl border border-slate-200/80 shadow-md p-6 md:p-8 space-y-8">
        @csrf
        @method('PUT')

        <!-- Section 1: Tanggal & Informasi Menu -->
        <div class="space-y-4">
            <div class="border-b border-slate-100 pb-3 flex items-center gap-2 text-emerald-700 font-bold text-sm">
                <i class="fa-solid fa-calendar-day"></i> 1. Informasi Tanggal & Menu Masakan
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Tanggal Masak <span class="text-rose-500">*</span></label>
                    <input type="date" name="tanggal" value="{{ old('tanggal', $menu->tanggal->format('Y-m-d')) }}" required class="w-full text-sm p-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 focus:outline-none shadow-sm">
                    @error('tanggal') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Menu MBG <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama_menu" value="{{ old('nama_menu', $menu->nama_menu) }}" placeholder="Contoh: Nasi Ayam Goreng Mentega" required class="w-full text-sm p-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 focus:outline-none shadow-sm">
                    @error('nama_menu') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Kategori</label>
                    <select name="kategori" class="w-full text-sm p-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 focus:outline-none shadow-sm">
                        <option value="B3" {{ old('kategori', $menu->kategori) === 'B3' ? 'selected' : '' }}>B3</option>
                        <option value="Siswa" {{ old('kategori', $menu->kategori) === 'Siswa' ? 'selected' : '' }}>Siswa</option>
                        <option value="Utama" {{ old('kategori', $menu->kategori) === 'Utama' ? 'selected' : '' }}>Utama / Siang</option>
                        <option value="Pagi" {{ old('kategori', $menu->kategori) === 'Pagi' ? 'selected' : '' }}>Pagi / Sarapan</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Catatan Tambahan (Opsional)</label>
                <textarea name="catatan" rows="2" placeholder="Catatan khusus atau alasan penambahan bahan..." class="w-full text-sm p-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 focus:outline-none shadow-sm">{{ old('catatan', $menu->catatan) }}</textarea>
            </div>
        </div>

        <!-- Section 2: Edit & Tambah List Bahan Makanan (Dynamic Table) -->
        <div class="space-y-4">
            <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                <div class="flex items-center gap-2 text-emerald-700 font-bold text-sm">
                    <i class="fa-solid fa-list-check"></i> 2. Rincian & Cover Tambahan Bahan Dipesan (PO)
                </div>
                <button type="button" @click="addItem()" class="px-3.5 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-bold rounded-lg border border-emerald-300 transition-colors flex items-center gap-1.5">
                    <i class="fa-solid fa-plus"></i> Tambah Bahan Ekstra / Cover
                </button>
            </div>

            <div class="overflow-x-auto border border-slate-200 rounded-2xl">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-100 text-slate-600 text-xs uppercase font-semibold">
                        <tr>
                            <th class="p-3 w-12 text-center">No</th>
                            <th class="p-3">Nama Item Bahan <span class="text-rose-500">*</span></th>
                            <th class="p-3 w-40">Merk (Opsional)</th>
                            <th class="p-3 w-28 text-center">Qty <span class="text-rose-500">*</span></th>
                            <th class="p-3 w-28">Satuan <span class="text-rose-500">*</span></th>
                            <th class="p-3 w-40">Harga Satuan (Rp) <span class="text-rose-500">*</span></th>
                            <th class="p-3 w-44 text-right">Subtotal (Rp)</th>
                            <th class="p-3 w-12 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        <template x-for="(item, index) in items" :key="index">
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="p-3 text-center text-xs font-bold text-slate-400" x-text="index + 1"></td>
                                <td class="p-2">
                                    <input type="hidden" :name="`items[${index}][id]`" x-model="item.id">
                                    <input type="text" :name="`items[${index}][nama_bahan]`" x-model="item.nama_bahan" placeholder="Nama bahan makanan" required class="w-full text-xs p-2.5 rounded-lg border border-slate-200 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                                </td>
                                <td class="p-2">
                                    <input type="text" :name="`items[${index}][merk]`" x-model="item.merk" placeholder="Merk" class="w-full text-xs p-2.5 rounded-lg border border-slate-200 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                                </td>
                                <td class="p-2">
                                    <input type="number" step="0.01" min="0.01" :name="`items[${index}][qty_pesan]`" x-model.number="item.qty_pesan" @input="calculateTotal()" required class="w-full text-xs p-2.5 rounded-lg border border-slate-200 text-center font-bold focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                                </td>
                                <td class="p-2">
                                    <select :name="`items[${index}][satuan]`" x-model="item.satuan" required class="w-full text-xs p-2.5 rounded-lg border border-slate-200 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                                        <option value="kg">kg</option>
                                        <option value="gram">gram</option>
                                        <option value="pcs">pcs</option>
                                        <option value="liter">liter</option>
                                        <option value="ikat">ikat</option>
                                        <option value="tray">tray</option>
                                        <option value="karung">karung</option>
                                        <option value="botol">botol</option>
                                        <option value="pack">pack</option>
                                    </select>
                                </td>
                                <td class="p-2">
                                    <input type="number" step="1" min="0" :name="`items[${index}][harga_satuan]`" x-model.number="item.harga_satuan" @input="calculateTotal()" required class="w-full text-xs p-2.5 rounded-lg border border-slate-200 font-semibold focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                                </td>
                                <td class="p-3 text-right font-extrabold text-slate-800" x-text="formatRupiah(item.qty_pesan * item.harga_satuan)">
                                </td>
                                <td class="p-2 text-center">
                                    <button type="button" @click="removeItem(index)" x-show="items.length > 1" class="text-rose-500 hover:text-rose-700 text-sm p-1.5 rounded-lg hover:bg-rose-50" title="Hapus Item">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Total Summary & Submit Button -->
        <div class="bg-slate-900 rounded-2xl p-6 text-white flex flex-col md:flex-row items-center justify-between gap-6 shadow-xl">
            <div>
                <span class="text-xs text-slate-400 uppercase tracking-wider font-semibold">Total Anggaran PO Baru</span>
                <div class="text-3xl font-extrabold text-emerald-400 mt-1" x-text="formatRupiah(grandTotal)"></div>
                <div class="text-xs text-slate-300 mt-0.5" x-text="`${items.length} item bahan dalam daftar PO`"></div>
            </div>

            <div class="flex items-center gap-3 w-full md:w-auto">
                <a href="{{ route('po.show', $menu->id) }}" class="w-full md:w-auto text-center px-5 py-3 bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold text-xs rounded-xl transition-colors">
                    Batal
                </a>
                <button type="submit" class="w-full md:w-auto px-6 py-3 bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-sm rounded-xl shadow-lg shadow-emerald-500/30 transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan PO
                </button>
            </div>
        </div>

    </form>

</div>

<script>
    function poEditForm(initialItems) {
        return {
            items: (initialItems && initialItems.length) 
                ? initialItems.map(item => ({
                    id: item.id || null,
                    nama_bahan: item.nama_bahan || '',
                    merk: item.merk || '',
                    qty_pesan: parseFloat(item.qty_pesan) || 0,
                    satuan: item.satuan || 'kg',
                    harga_satuan: parseFloat(item.harga_satuan) || 0
                }))
                : [{ id: null, nama_bahan: '', merk: '', qty_pesan: 1, satuan: 'kg', harga_satuan: 0 }],
            grandTotal: 0,
            init() {
                this.calculateTotal();
            },
            addItem() {
                this.items.push({
                    id: null,
                    nama_bahan: '',
                    merk: '',
                    qty_pesan: 1,
                    satuan: 'kg',
                    harga_satuan: 0
                });
                this.calculateTotal();
            },
            removeItem(index) {
                if (this.items.length > 1) {
                    this.items.splice(index, 1);
                    this.calculateTotal();
                }
            },
            calculateTotal() {
                this.grandTotal = this.items.reduce((sum, item) => {
                    const qty = parseFloat(item.qty_pesan) || 0;
                    const price = parseFloat(item.harga_satuan) || 0;
                    return sum + (qty * price);
                }, 0);
            },
            formatRupiah(amount) {
                return 'Rp ' + (amount || 0).toLocaleString('id-ID');
            }
        }
    }
</script>
@endsection
