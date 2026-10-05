<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Form QC Manual - {{ $menu->nama_menu }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 10px;
            color: #1e293b;
            line-height: 1.3;
            margin: 0;
            padding: 12px;
        }

        .header {
            border-bottom: 2px solid #0284c7;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }

        .logo-title {
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            color: #0284c7;
        }

        .subtitle {
            font-size: 9px;
            color: #64748b;
        }

        .form-title {
            float: right;
            text-align: right;
        }

        .form-code {
            font-size: 13px;
            font-weight: bold;
            font-family: monospace;
            color: #0f172a;
        }

        .clear {
            clear: both;
        }

        .instruction-box {
            background: #f0f9ff;
            border: 1px solid #bae6fd;
            padding: 6px 10px;
            border-radius: 4px;
            margin-bottom: 12px;
            font-size: 9px;
            color: #0369a1;
        }

        .meta-table {
            width: 100%;
            margin-bottom: 12px;
            border-collapse: collapse;
        }

        .meta-table td {
            padding: 6px;
            vertical-align: top;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .items-table th {
            background: #0284c7;
            color: #fff;
            text-transform: uppercase;
            font-size: 8px;
            padding: 6px;
            border: 1px solid #0284c7;
        }

        .items-table td {
            padding: 6px;
            border: 1px solid #cbd5e1;
            vertical-align: middle;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .font-bold {
            font-weight: bold;
        }

        .blank-line {
            display: inline-block;
            border-bottom: 1px dashed #475569;
            width: 80%;
            height: 12px;
        }

        .checkbox-cell {
            font-size: 8px;
            white-space: nowrap;
        }

        .signature-section {
            margin-top: 30px;
            width: 100%;
        }

        .sig-box {
            width: 45%;
            float: left;
            text-align: center;
        }

        .sig-box-right {
            width: 45%;
            float: right;
            text-align: center;
        }

        .sig-space {
            height: 50px;
        }
    </style>
</head>

<body>

    <div class="header">
        <div class="form-title">
            <div class="logo-title" style="color: #0f172a;">FORM CHECKLIST QC MANUAL</div>
            <div class="form-code">
                QC-MANUAL-{{ $menu->tanggal->format('Ymd') }}-{{ str_pad($menu->id, 4, '0', STR_PAD_LEFT) }}</div>
            <div class="subtitle">Tgl Masak: {{ $menu->tanggal->format('d F Y') }}</div>
        </div>
        <div>
            <div class="logo-title">PROGRAM MBG INDONESIA</div>
            <div class="subtitle">Unit Pengawasan & Penjaminan Mutu Kualitas Bahan Baku</div>
        </div>
        <div class="clear"></div>
    </div>

    <div class="instruction-box">
        <strong>PANDUAN PEMERIKSAAN LAPANGAN:</strong> Gunakan Lembar Form QC Manual ini untuk melakukan pencatatan
        checklist fisik secara langsung saat pengiriman bahan dari Supplier tiba di Dapur MBG.
    </div>

    <table class="meta-table">
        <tr>
            <td width="50%">
                <strong>Detail Menu Masakan:</strong><br>
                Nama Menu: <strong>{{ $menu->nama_menu }}</strong><br>
                Kategori Menu: {{ $menu->kategori }}
            </td>
            <td width="50%">
                <strong>Petugas & Waktu Kedatangan:</strong><br>
                Nama Pemeriksa QC: ___________________________<br>
                Jam Kedatangan Barang: _______ : _______ WIB
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th width="4%" class="text-center">No</th>
                <th width="24%">Nama Item Bahan</th>
                <th width="12%">Merk</th>
                <th width="10%" class="text-center">Target Qty</th>
                <th width="10%" class="text-center">Qty Terima (Fisik)</th>
                <th width="12%" class="text-center">Kondisi Warna</th>
                <th width="12%" class="text-center">Kondisi Aroma</th>
                <th width="16%" class="text-center">Status Keputusan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($menu->poItems as $idx => $item)
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td class="font-bold">{{ $item->nama_bahan }}</td>
                    <td>{{ $item->merk ?: '-' }}</td>
                    <td class="text-center font-bold">{{ number_format($item->qty_pesan, 2) }} {{ $item->satuan }}</td>
                    <td class="text-center font-bold" style="background: #fafafa;">
                        [ &nbsp;&nbsp; ] {{ $item->satuan }}
                    </td>
                    <td class="checkbox-cell text-center">
                        [ &nbsp; ] Segar &nbsp; [ &nbsp; ] Layu/Bercak
                    </td>
                    <td class="checkbox-cell text-center">
                        [ &nbsp; ] Normal &nbsp; [ &nbsp; ] Bau/Asam
                    </td>
                    <td class="checkbox-cell text-center font-bold">
                        [ &nbsp; ] LOLOS &nbsp;&nbsp; [ &nbsp; ] TOLAK
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="border: 1px solid #cbd5e1; padding: 8px; background: #f8fafc; border-radius: 4px; margin-bottom: 20px;">
        <strong>Catatan / Temuan Pengecekan Fisik:</strong>
        <div style="height: 35px; border-bottom: 1px dashed #cbd5e1; margin-top: 4px;"></div>
    </div>

    <div class="signature-section">
        <div class="sig-box">
            <div>Kurir / Supplier Pengirim</div>
            <div class="sig-space"></div>
            <div><strong>( ______________________ )</strong></div>
        </div>
        <div class="sig-box-right">
            <div>Petugas QC Inspector Dapur</div>
            <div class="sig-space"></div>
            <div><strong>( ______________________ )</strong></div>
        </div>
        <div class="clear"></div>
    </div>

</body>

</html>
