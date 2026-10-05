<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice - {{ $invoice->nomor_invoice }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 11px; color: #333; line-height: 1.4; margin: 0; padding: 15px; }
        .header { border-bottom: 2px solid #111; padding-bottom: 10px; margin-bottom: 20px; }
        .logo-title { font-size: 18px; font-weight: bold; text-transform: uppercase; color: #16a34a; }
        .subtitle { font-size: 10px; color: #666; }
        .inv-title { float: right; text-align: right; }
        .inv-number { font-size: 16px; font-weight: bold; font-family: monospace; }
        .clear { clear: both; }
        .meta-table { width: 100%; margin-bottom: 20px; border-collapse: collapse; }
        .meta-table td { padding: 6px; vertical-align: top; background: #f9fafb; border: 1px solid #e5e7eb; }
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .items-table th { background: #1f2937; color: #fff; text-transform: uppercase; font-size: 9px; padding: 8px; border: 1px solid #1f2937; }
        .items-table td { padding: 8px; border: 1px solid #e5e7eb; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .totals-table { width: 40%; float: right; border-collapse: collapse; margin-bottom: 30px; }
        .totals-table td { padding: 6px 10px; border-bottom: 1px solid #e5e7eb; }
        .grand-total { font-size: 13px; font-weight: bold; color: #16a34a; background: #f0fdf4; }
        .signature-section { margin-top: 40px; width: 100%; }
        .sig-box { width: 45%; float: left; text-align: center; }
        .sig-box-right { width: 45%; float: right; text-align: center; }
        .sig-space { height: 50px; }
    </style>
</head>
<body>

    <div class="header">
        <div class="inv-title">
            <div class="logo-title" style="color: #1f2937;">INVOICE PENAGIHAN</div>
            <div class="inv-number">{{ $invoice->nomor_invoice }}</div>
            <div class="subtitle">Tanggal: {{ $invoice->tanggal_invoice->format('d/m/Y') }}</div>
        </div>
        <div>
            <div class="logo-title">PROGRAM MBG INDONESIA</div>
            <div class="subtitle">Unit Penyedia Makanan Bergizi Gratis</div>
        </div>
        <div class="clear"></div>
    </div>

    <table class="meta-table">
        <tr>
            <td width="50%">
                <strong>Ditujukan Kepada:</strong><br>
                Mitra Supplier Bahan Makanan MBG<br>
                Penerimaan Dapur MBG
            </td>
            <td width="50%">
                <strong>Detail Menu Masakan:</strong><br>
                Menu: {{ $invoice->menu->nama_menu }}<br>
                Tanggal Masak: {{ $invoice->menu->tanggal->format('d/m/Y') }}
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="35%">Nama Barang / Bahan</th>
                <th width="15%">Merk</th>
                <th width="12%" class="text-center">Qty Terima</th>
                <th width="10%" class="text-center">Satuan</th>
                <th width="13%" class="text-right">Harga Satuan</th>
                <th width="15%" class="text-right">Total Harga</th>
            </tr>
        </thead>
        <tbody>
            @if($invoice->inspection && $invoice->inspection->inspectionItems->isNotEmpty())
                @foreach($invoice->inspection->inspectionItems as $idx => $item)
                    @php
                        $po = $item->poItem;
                        $lineTotal = $item->qty_terima * ($po->harga_satuan ?? 0);
                    @endphp
                    <tr>
                        <td class="text-center">{{ $idx + 1 }}</td>
                        <td class="font-bold">{{ $po->nama_bahan ?? '-' }}</td>
                        <td>{{ $po->merk ?: '-' }}</td>
                        <td class="text-center font-bold">{{ number_format($item->qty_terima, 2) }}</td>
                        <td class="text-center">{{ $po->satuan ?? '' }}</td>
                        <td class="text-right">Rp {{ number_format($po->harga_satuan ?? 0, 0, ',', '.') }}</td>
                        <td class="text-right font-bold">Rp {{ number_format($lineTotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            @else
                @foreach($invoice->menu->poItems as $idx => $po)
                    <tr>
                        <td class="text-center">{{ $idx + 1 }}</td>
                        <td class="font-bold">{{ $po->nama_bahan }}</td>
                        <td>{{ $po->merk ?: '-' }}</td>
                        <td class="text-center font-bold">{{ number_format($po->qty_pesan, 2) }}</td>
                        <td class="text-center">{{ $po->satuan }}</td>
                        <td class="text-right">Rp {{ number_format($po->harga_satuan, 0, ',', '.') }}</td>
                        <td class="text-right font-bold">Rp {{ number_format($po->total_harga, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>

    <table class="totals-table">
        <tr>
            <td>Subtotal PO:</td>
            <td class="text-right">Rp {{ number_format($invoice->subtotal_po, 0, ',', '.') }}</td>
        </tr>
        @if($invoice->potongan_ketidaksesuaian > 0)
        <tr>
            <td style="color: #dc2626;">Potongan QC:</td>
            <td class="text-right" style="color: #dc2626;">- Rp {{ number_format($invoice->potongan_ketidaksesuaian, 0, ',', '.') }}</td>
        </tr>
        @endif
        <tr class="grand-total">
            <td>GRAND TOTAL:</td>
            <td class="text-right">Rp {{ number_format($invoice->total_tagihan, 0, ',', '.') }}</td>
        </tr>
    </table>
    <div class="clear"></div>

    <div class="signature-section">
        <div class="sig-box">
            <p>Petugas Akuntansi / Finance</p>
            <div class="sig-space"></div>
            <p><strong>( Akuntan MBG )</strong></p>
        </div>
        <div class="sig-box-right">
            <p>Petugas Quality Control (QC)</p>
            <div class="sig-space"></div>
            <p><strong>( {{ $invoice->inspection->nama_pemeriksa ?? 'QC Staff' }} )</strong></p>
        </div>
        <div class="clear"></div>
    </div>

</body>
</html>
