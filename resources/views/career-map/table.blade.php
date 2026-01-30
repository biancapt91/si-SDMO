@extends('layouts.app')

@section('content')
<div class="container py-4">

    <h3 class="fw-bold mb-3">
        <span style="font-size: 22px;">⭐</span> 
        Rekap Peta Karier Pegawai
    </h3>

<style>
    table.cm-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }
    table.cm-table th {
        background: #f8d7b6;
        padding: 6px;
        border: 1px solid #000;
        text-align: center;
        white-space: nowrap;
    }
    table.cm-table td {
        padding: 6px;
        border: 1px solid #000;
        vertical-align: middle;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .text-red { color: red; font-weight: bold; }
    .text-green { color: green; font-weight: bold; }
</style>

<table class="cm-table">
    <thead>
        <tr>
            <th>No</th>
            <th>Pegawai</th>
            <th>Jabatan</th>
            <th>Jenis Jabatan</th>
            <th>Jenjang</th>
            <th>Pangkat/<br>TMT Pangkat</th>
            <th>AK Saat Ini</th>
            <th>AK Kebutuhan<br>(Pangkat / Jenjang)</th>
            <th>Estimasi<br>Kenaikan Pangkat</th>
            <th>Estimasi<br>Kenaikan Jenjang</th>
            <th>Keterangan</th>
            <th>Aksi</th>
        </tr>
    </thead>

    <tbody>
        @foreach($pegawais as $i => $p)
            <tr>
                <td class="text-center">{{ $i+1 }}</td>

                <td>{{ $p->nama }}</td>

                <td style="max-width:240px">{{ $p->jabatan_saat_ini }}</td>

                <td>{{ $p->jenis_jabatan ?? '-' }}</td>

                <td>{{ $p->jenjang_extracted ?? '-' }}</td>

                <td>
                    {{ $p->pangkat ?? '-' }}<br>
                    <small>{{ $p->tmt_pangkat ?? '-' }}</small>
                </td>

                <td class="text-center">{{ number_format($p->ak_saat_ini,2) }}</td>

                <td>
                    P: {{ $p->ak_kebutuhan_pangkat ?? '-' }}<br>
                    J: {{ $p->ak_kebutuhan_jenjang ?? '-' }}
                </td>

                <td class="text-center">{{ $p->estimasi_pangkat ?? '-' }}</td>

                <td class="text-center">{{ $p->estimasi_jenjang ?? '-' }}</td>

                <td class="text-center">
                    @if($p->ket_color == 'green')
                        <span class="text-green">{{ $p->keterangan }}</span>
                    @else
                        <span class="text-red">{{ $p->keterangan }}</span>
                    @endif
                </td>

                <td class="text-center">
                    <a href="{{ url('/career-map/'.$p->id.'/edit') }}" class="btn btn-warning btn-sm">Edit</a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

</div>
@endsection
