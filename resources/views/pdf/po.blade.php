<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Purchase Order (PO) - {{ $menu->nama_menu }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 11px; color: #333; line-height: 1.4; margin: 0; padding: 15px; }
        .header { border-bottom: 2px solid #059669; padding-bottom: 10px; margin-bottom: 20px; }
        .logo-title { font-size: 18px; font-weight: bold; text-transform: uppercase; color: #059669; }
        .subtitle { font-size: 10px; color: #666; }
        .po-title { float: right; text-align: right; }
        .po-number { font-size: 16px; font-weight: bold; font-family: monospace; color: #111827; }
        .clear { clear: both; }
        .meta-table { width: 100%; margin-bottom: 20px; border-collapse: collapse; }
        .meta-table td { padding: 8px; vertical-align: top; background: #f9fafb; border: 1px solid #e5e7eb; }
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .items-table th { background: #059669; color: #fff; text-transform: uppercase; font-size: 9px; padding: 8px; border: 1px solid #059669; }
        .items-table td { padding: 8px; border: 1px solid #e5e7eb; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .totals-table { width: 45%; float: right; border-collapse: collapse; margin-bottom: 30px; }
        .totals-table td { padding: 8px 10px; border-bottom: 1px solid #e5e7eb; }
        .grand-total { font-size: 13px; font-weight: bold; color: #059669; background: #ecfdf5; }
        .signature-section { margin-top: 50px; width: 100%; }
        .sig-box { width: 45%; float: left; text-align: center; }
        .sig-box-right { width: 45%; float: right; text-align: center; }
        .sig-space { height: 55px; }
    </style>
</head>
<body>

    <div class="header">
        <div class="po-title">
            <div class="logo-title" style="color: #111827;">SURAT PESANAN (PO)</div>
            <div class="po-number">PO-MBG-{{ $menu->tanggal->format('Ymd') }}-{{ str_pad($menu->id, 4, '0', STR_PAD_LEFT) }}</div>
            <div class="subtitle">Tanggal Masak: {{ $menu->tanggal->format('d F Y') }}</div>
        </div>
        <div>
            <div class="logo-title">PROGRAM MBG INDONESIA</div>
            <div class="subtitle">Dapur Penyedia Makanan Bergizi Gratis</div>
        </div>
        <div class="clear"></div>
    </div>

    <table class="meta-table">
        <tr>
            <td width="50%">
                <strong>Informasi Menu:</strong><br>
                Nama Menu: <strong>{{ $menu->nama_menu }}</strong><br>
                Kategori: {{ $menu->kategori }}<br>
                Total Item: {{ $menu->poItems->count() }} Bahan
            </td>
            <td width="50%">
                <strong>Catatan / Pemesanan:</strong><br>
                {{ $menu->catatan ?: 'Tidak ada catatan tambahan.' }}
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th width="6%" class="text-center">No</th>
                <th width="34%">Nama Item Bahan</th>
                <th width="15%">Merk</th>
                <th width="12%" class="text-center">Qty Pesan</th>
                <th width="10%" class="text-center">Satuan</th>
                <th width="13%" class="text-right">Harga Satuan</th>
                <th width="10%" class="text-right">Total Harga</th>
            </tr>
        </thead>
        <tbody>
            @foreach($menu->poItems as $idx => $item)
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td class="font-bold">{{ $item->nama_bahan }}</td>
                    <td>{{ $item->merk ?: '-' }}</td>
                    <td class="text-center font-bold">{{ number_format($item->qty_pesan, 2) }}</td>
                    <td class="text-center">{{ $item->satuan }}</td>
                    <td class="text-right">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                    <td class="text-right font-bold" style="color: #047857;">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals-table">
        <tr class="grand-total">
            <td><strong>TOTAL ANGGARAN PO:</strong></td>
            <td class="text-right"><strong>Rp {{ number_format($menu->total_po, 0, ',', '.') }}</strong></td>
        </tr>
    </table>
    <div class="clear"></div>

    <div class="signature-section">
        <div class="sig-box">
            <div>Dibuat Oleh (Akuntan / Bagian PO)</div>
            <div class="sig-space"></div>
            <div><strong>( ______________________ )</strong></div>
        </div>
        <div class="sig-box-right">
            <div>Disetujui Oleh (Kepala SPPG MBG)</div>
            <div class="sig-space"></div>
            <div><strong>( ______________________ )</strong></div>
        </div>
        <div class="clear"></div>
    </div>

</body>
</html>
