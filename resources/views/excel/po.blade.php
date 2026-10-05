<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        th { background-color: #15803d; color: #ffffff; font-weight: bold; border: 1px solid #000000; }
        td { border: 1px solid #cccccc; }
    </style>
</head>
<body>
    <h3>LAPORAN PEMESANAN BAHAN (PO) MBG</h3>
    <table>
        <thead>
            <tr>
                <th>Tanggal Menu</th>
                <th>Nama Menu</th>
                <th>Kategori</th>
                <th>Nama Bahan</th>
                <th>Merk</th>
                <th>Qty Pesan</th>
                <th>Satuan</th>
                <th>Harga Satuan (Rp)</th>
                <th>Total Harga (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($menus as $menu)
                @foreach($menu->poItems as $item)
                    <tr>
                        <td>{{ $menu->tanggal->format('Y-m-d') }}</td>
                        <td>{{ $menu->nama_menu }}</td>
                        <td>{{ $menu->kategori }}</td>
                        <td>{{ $item->nama_bahan }}</td>
                        <td>{{ $item->merk }}</td>
                        <td>{{ $item->qty_pesan }}</td>
                        <td>{{ $item->satuan }}</td>
                        <td>{{ $item->harga_satuan }}</td>
                        <td>{{ $item->total_harga }}</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</body>
</html>
