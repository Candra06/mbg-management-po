<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Rekap Pengecekan QC MBG</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 11px; color: #333; line-height: 1.4; margin: 0; padding: 15px; }
        .header { border-bottom: 2px solid #111; padding-bottom: 10px; margin-bottom: 20px; }
        .logo-title { font-size: 16px; font-weight: bold; color: #0d9488; }
        .subtitle { font-size: 10px; color: #666; }
        .clear { clear: both; }
        .summary-box { background: #f0fdf4; border: 1px solid #bbf7d0; padding: 10px; margin-bottom: 20px; border-radius: 6px; }
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .items-table th { background: #0f172a; color: #fff; text-transform: uppercase; font-size: 9px; padding: 7px; border: 1px solid #0f172a; }
        .items-table td { padding: 7px; border: 1px solid #e2e8f0; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .badge-sesuai { color: #15803d; font-weight: bold; }
        .badge-tidak { color: #b91c1c; font-weight: bold; }
    </style>
</head>
<body>

    <div class="header">
        <div class="logo-title">LAPORAN QUALITY CONTROL (QC) PENERIMAAN BAHAN MBG</div>
        <div class="subtitle">Program Makan Bergizi Gratis - Verifikasi Mutu & Kesesuaian Fisik</div>
    </div>

    <table style="width: 100%; margin-bottom: 15px;">
        <tr>
            <td><strong>Nama Menu:</strong> {{ $inspection->menu->nama_menu }}</td>
            <td><strong>Tanggal Menu:</strong> {{ $inspection->menu->tanggal->format('d/m/Y') }}</td>
            <td><strong>Petugas QC:</strong> {{ $inspection->nama_pemeriksa }}</td>
        </tr>
    </table>

    <div class="summary-box">
        <strong>REKAPITULASI HASIL INSPEKSI:</strong><br>
        • Total Item Dipesan: {{ $inspection->total_item }} Item<br>
        • Jumlah Item Sesuai: <span class="badge-sesuai">{{ $inspection->total_sesuai }} Item</span><br>
        • Jumlah Item Tidak Sesuai: <span class="badge-tidak">{{ $inspection->total_tidak_sesuai }} Item</span><br>
        • Status QC: <strong>{{ $inspection->status_keseluruhan }}</strong>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="30%">Nama Bahan / Merk</th>
                <th width="12%" class="text-center">Qty Pesan</th>
                <th width="12%" class="text-center">Qty Terima</th>
                <th width="12%" class="text-center">Warna</th>
                <th width="12%" class="text-center">Aroma</th>
                <th width="17%" class="text-center">Status Kesesuaian</th>
            </tr>
        </thead>
        <tbody>
            @foreach($inspection->inspectionItems as $idx => $item)
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td>
                        <strong>{{ $item->poItem->nama_bahan ?? '-' }}</strong><br>
                        <small>Merk: {{ $item->poItem->merk ?: '-' }}</small>
                    </td>
                    <td class="text-center">{{ number_format($item->poItem->qty_pesan ?? 0, 2) }} {{ $item->poItem->satuan ?? '' }}</td>
                    <td class="text-center"><strong>{{ number_format($item->qty_terima, 2) }} {{ $item->poItem->satuan ?? '' }}</strong></td>
                    <td class="text-center">{{ $item->warna }}</td>
                    <td class="text-center">{{ $item->aroma }}</td>
                    <td class="text-center">
                        @if($item->status_jumlah == 'sesuai')
                            <span class="badge-sesuai">✓ SESUAI</span>
                        @else
                            <span class="badge-tidak">✗ TIDAK SESUAI</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
