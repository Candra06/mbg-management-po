<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        th { background-color: #0d9488; color: #ffffff; font-weight: bold; border: 1px solid #000000; }
        td { border: 1px solid #cccccc; }
    </style>
</head>
<body>
    <h3>LAPORAN PENGECEKAN KUALITAS (QC) BAHAN MBG</h3>
    <table>
        <thead>
            <tr>
                <th>Waktu Cek</th>
                <th>Menu</th>
                <th>Pemeriksa</th>
                <th>Nama Bahan</th>
                <th>Qty Pesan</th>
                <th>Qty Terima</th>
                <th>Warna</th>
                <th>Aroma</th>
                <th>Status Kesesuaian</th>
                <th>Catatan QC</th>
            </tr>
        </thead>
        <tbody>
            @foreach($inspections as $ins)
                @foreach($ins->inspectionItems as $item)
                    <tr>
                        <td>{{ $ins->tanggal_cek ? $ins->tanggal_cek->format('Y-m-d H:i') : '' }}</td>
                        <td>{{ $ins->menu->nama_menu ?? '' }}</td>
                        <td>{{ $ins->nama_pemeriksa }}</td>
                        <td>{{ $item->poItem->nama_bahan ?? '' }}</td>
                        <td>{{ $item->poItem->qty_pesan ?? 0 }}</td>
                        <td>{{ $item->qty_terima }}</td>
                        <td>{{ $item->warna }}</td>
                        <td>{{ $item->aroma }}</td>
                        <td>{{ $item->status_jumlah }}</td>
                        <td>{{ $item->catatan }}</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</body>
</html>
