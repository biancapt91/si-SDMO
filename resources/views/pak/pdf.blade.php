<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>PAK</title>
    <style>
        body {
            font-family: "Times New Roman", serif;
            font-size: 12pt;
            line-height: 1.4;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td, th {
            border: 1px solid #000;
            padding: 6px;
        }
        .no-border td {
            border: none;
        }
        .center {
            text-align: center;
        }
    </style>
</head>
<body>

<h3 class="center">PENETAPAN ANGKA KREDIT</h3>

<br>

<table class="no-border">
    <tr>
        <td width="25%">Nama</td>
        <td>: {{ $ak->pegawai->nama }}</td>
    </tr>
    <tr>
        <td>NIP</td>
        <td>: {{ $ak->pegawai->nip }}</td>
    </tr>
    <tr>
        <td>Golongan</td>
        <td>: {{ $ak->golongan ?? '-' }}</td>
    </tr>
    <tr>
        <td>Periode Penilaian</td>
        <td>
            :
            @if($ak->jenis_penilaian === 'proporsional')
                {{ $ak->bulan_awal }} s.d {{ $ak->bulan_akhir }} {{ $ak->periode }}
            @else
                s.d. 31 Desember {{ $ak->periode }}
            @endif
        </td>
    </tr>
</table>

<br>

<table>
    <tr>
        <th width="70%">Uraian</th>
        <th width="30%">Angka Kredit</th>
    </tr>
    <tr>
        <td>AK Kinerja</td>
        <td class="center">{{ number_format($ak->ak_hasil,2) }}</td>
    </tr>
    <tr>
        <td>AK Pendidikan</td>
        <td class="center">{{ number_format($ak->ak_tambahan,2) }}</td>
    </tr>
    <tr>
        <td>AK Dasar</td>
        <td class="center">{{ number_format($ak->ak_dasar,2) }}</td>
    </tr>
    <tr>
        <td>AK JF Lama</td>
        <td class="center">{{ number_format($ak->ak_jf_lama,2) }}</td>
    </tr>
    <tr>
        <th>Total Angka Kredit</th>
        <th class="center">{{ number_format($ak->ak_total,2) }}</th>
    </tr>
</table>

<br><br>

<table class="no-border">
    <tr>
        <td width="50%" class="center">
            Pejabat Penilai Kinerja<br><br><br><br>
            ( ___________________ )
        </td>
        <td width="50%" class="center">
            SDMO<br><br><br><br>
            ( ___________________ )
        </td>
    </tr>
</table>

</body>
</html>
