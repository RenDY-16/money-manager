<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan Kost AJ Lanraki</title>
</head>
<body>
<table border="1">
    <tr>
        <th colspan="6" style="font-size:18px;text-align:left;">Laporan Keuangan Kost AJ Lanraki</th>
    </tr>
    <tr>
        <td colspan="6">Filter: {{ $filterLabel }}</td>
    </tr>
    <tr>
        <td colspan="6">Tanggal Export: {{ now()->locale('id')->translatedFormat('d M Y H:i') }}</td>
    </tr>
</table>
<br>
<table border="1">
    <thead>
        <tr>
            <th>Total Pemasukan</th>
            <th>Total Pengeluaran</th>
            <th>Saldo Bersih Periode</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>{{ $totalPemasukan }}</td>
            <td>{{ $totalPengeluaran }}</td>
            <td>{{ $saldoBersih }}</td>
        </tr>
    </tbody>
</table>
<br>

@if($selectedType !== 'pengeluaran')
<table border="1">
    <thead>
        <tr><th colspan="6" style="text-align:left;">Daftar Pemasukan</th></tr>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Kategori</th>
            <th>Penghuni</th>
            <th>Keterangan</th>
            <th>Nominal</th>
        </tr>
    </thead>
    <tbody>
        @forelse($latestPemasukan as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                <td>{{ $item->kategori === 'pembayaran_kost' ? 'Pembayaran Kost' : 'Pemasukan Lainnya' }}</td>
                <td>{{ optional($item->penghuni)->nama ?? 'Pemasukan lainnya' }}</td>
                <td>{{ $item->keterangan ?: '-' }}</td>
                <td>{{ $item->jumlah }}</td>
            </tr>
        @empty
            <tr><td colspan="6">Tidak ada data pemasukan.</td></tr>
        @endforelse
    </tbody>
</table>
<br>
@endif

@if($selectedType !== 'pemasukan')
<table border="1">
    <thead>
        <tr><th colspan="5" style="text-align:left;">Daftar Pengeluaran</th></tr>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Kategori</th>
            <th>Keterangan</th>
            <th>Nominal</th>
        </tr>
    </thead>
    <tbody>
        @forelse($latestPengeluaran as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                <td>{{ $item->kategori }}</td>
                <td>{{ $item->keterangan ?: '-' }}</td>
                <td>{{ $item->jumlah }}</td>
            </tr>
        @empty
            <tr><td colspan="5">Tidak ada data pengeluaran.</td></tr>
        @endforelse
    </tbody>
</table>
<br>
@endif

<table border="1">
    <thead>
        <tr><th colspan="4" style="text-align:left;">Distribusi Pengeluaran</th></tr>
        <tr>
            <th>No</th>
            <th>Kategori</th>
            <th>Jumlah Transaksi</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @forelse($pengeluaranKategori as $index => $kategori)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $kategori['kategori'] }}</td>
                <td>{{ $kategori['jumlah_transaksi'] }}</td>
                <td>{{ $kategori['total'] }}</td>
            </tr>
        @empty
            <tr><td colspan="4">Tidak ada data distribusi pengeluaran.</td></tr>
        @endforelse
    </tbody>
</table>
</body>
</html>
