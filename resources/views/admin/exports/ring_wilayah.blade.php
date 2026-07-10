<html><head><meta charset="UTF-8">
<style>
body { font-family: Arial, sans-serif; font-size: 10pt; }
table { border-collapse: collapse; width: 100%; }
th {
    background: #1565c0;
    color: #fff;
    border: 1px solid #999;
    padding: 6px 10px;
    white-space: nowrap;
    text-align: center;
}
td { border: 1px solid #ccc; padding: 4px 8px; vertical-align: middle; }
.title-row td { background: #1565c0; color: #fff; font-size: 14pt; font-weight: bold; padding: 10px; }
.sub-row td  { background: #e3f2fd; color: #555; font-size: 9pt; padding: 4px 8px; }
.footer-row td { background: #e8f5e9; font-weight: bold; }
.num  { text-align: right; }
.ctr  { text-align: center; }
</style>
</head><body>
<table>
    <!-- Title -->
    <tr class="title-row">
        <td colspan="9">LAPORAN RING WILAYAH {{ $filter_pid ? (' — ' . ($rows[0]->nama_perusahaan ?? '')) : ' — SEMUA PERUSAHAAN' }}</td>
    </tr>
    <tr class="sub-row">
        <td colspan="9">
            Dicetak: {{ date('d M Y H:i') }} &nbsp;|&nbsp;
            Total perusahaan: {{ count($rows) }} &nbsp;|&nbsp;
            Keterangan: Hanya karyawan berstatus <strong>Aktif</strong>
        </td>
    </tr>
    <tr><td colspan="9" style="height:6px"></td></tr>

    <!-- Header -->
    <tr>
        <th>#</th>
        <th>Nama Perusahaan</th>
        <th>Ring 1</th>
        <th>Ring 2</th>
        <th>Ring 3</th>
        <th>Ring 4</th>
        <th>Belum Terpetakan</th>
        <th>Total Aktif</th>
        <th>% Ring 1 / 2 / 3 / 4 / Belum</th>
    </tr>

    <!-- Data -->
    @foreach ($rows as $i => $r)
        @php
            $tot = max((int)$r->total_aktif, 1);
            $p1  = round($r->ring1   / $tot * 100);
            $p2  = round($r->ring2   / $tot * 100);
            $p3  = round($r->ring3   / $tot * 100);
            $p4  = round($r->ring4   / $tot * 100);
            $pn  = round($r->no_ring / $tot * 100);
        @endphp
        <tr>
            <td class="ctr">{{ $i+1 }}</td>
            <td>{{ $r->nama_perusahaan }}</td>
            <td class="num">{{ number_format($r->ring1,   0, ',', '.') }}</td>
            <td class="num">{{ number_format($r->ring2,   0, ',', '.') }}</td>
            <td class="num">{{ number_format($r->ring3,   0, ',', '.') }}</td>
            <td class="num">{{ number_format($r->ring4,   0, ',', '.') }}</td>
            <td class="num">{{ number_format($r->no_ring, 0, ',', '.') }}</td>
            <td class="num">{{ number_format($r->total_aktif, 0, ',', '.') }}</td>
            <td class="ctr">{{ $p1 }} / {{ $p2 }} / {{ $p3 }} / {{ $p4 }} / {{ $pn }}%</td>
        </tr>
    @endforeach

    <!-- Footer total -->
    <tr class="footer-row">
        <td colspan="2" style="text-align:right">TOTAL</td>
        <td class="num">{{ number_format($grand_ring1, 0, ',', '.') }}</td>
        <td class="num">{{ number_format($grand_ring2, 0, ',', '.') }}</td>
        <td class="num">{{ number_format($grand_ring3, 0, ',', '.') }}</td>
        <td class="num">{{ number_format($grand_ring4, 0, ',', '.') }}</td>
        <td class="num">{{ number_format($grand_no,    0, ',', '.') }}</td>
        <td class="num">{{ number_format($grand_total, 0, ',', '.') }}</td>
        <td></td>
    </tr>
</table>
</body></html>
