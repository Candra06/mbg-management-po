<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        th { background-color: #0891b2; color: #ffffff; font-weight: bold; border: 1px solid #000000; }
        td { border: 1px solid #cccccc; }
    </style>
</head>
<body>
    <h3>LAPORAN INVOICE TERBIT MBG</h3>
    <table>
        <thead>
            <tr>
                <th>Nomor Invoice</th>
                <th>Tanggal Invoice</th>
                <th>Menu Masakan</th>
                <th>Subtotal PO (Rp)</th>
                <th>Potongan QC (Rp)</th>
                <th>Total Tagihan (Rp)</th>
                <th>Status Invoice</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoices as $inv)
                <tr>
                    <td>{{ $inv->nomor_invoice }}</td>
                    <td>{{ $inv->tanggal_invoice->format('Y-m-d') }}</td>
                    <td>{{ $inv->menu->nama_menu ?? '' }}</td>
                    <td>{{ $inv->subtotal_po }}</td>
                    <td>{{ $inv->potongan_ketidaksesuaian }}</td>
                    <td>{{ $inv->total_tagihan }}</td>
                    <td>{{ ucfirst($inv->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
