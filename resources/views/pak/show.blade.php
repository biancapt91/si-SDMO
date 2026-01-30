@extends('layouts.app')

@section('sidebar')
<div class="row">{{-- SIDEBAR --}}
    @include('career-map.sidebar')
    </div>
@endsection

@section('content')
<style>
    .col-no {
        width: 1%;
        white-space: nowrap;
        text-align: center;
    }

.pak-paper {
    width: 250mm;              /* ukuran A4 */
    min-height: 297mm;
    background: #fff;
    padding: 20mm;
    box-shadow: 0 0 10px rgba(0,0,0,.15);
    font-size: 12px;
}

@media (max-width: 400px) {
    .pak-paper {
        width: 50%;
        padding: 12px;
    }
}

</style>

<div class="d-flex justify-content-center">

    <div class="pak-paper">

<div class="text-center mb-4">
    <h5 class="fw-bold">PENETAPAN ANGKA KREDIT</h5>
    <p>Nomor: {{ $ak->pakDocument->nomor_pak ?? '—' }}</p>
</div>

<table class="table table-bordered">

    <tr>
        <th width="1%">I</th>
        <th colspan="6">KETERANGAN PERORANGAN</th>
    </tr>
    <tr>
        <th rowspan="9"></th>
    </tr>
    <tr>
	<td class="col-no">1</td>
        <td width="40%">Nama</td>
        <td colspan="4">{{ $ak->pegawai->nama }}</td>
    </tr>
    <tr>
	<td class="col-no">2</td>
        <td>NIP</td>
        <td colspan="4">{{ $ak->pegawai->nip }}</td>
    </tr>
    <tr>
	<td class="col-no">3</td>
        <td>Nomor Seri KARPEG</td>
        <td colspan="4">{{ ' - ' }}</td>
    </tr>
    <tr>
	<td class="col-no">4</td>
        <td>Tempat/Tgl. Lahir</td>
        <td colspan="4">{{ $ak->pegawai->tanggal_lahir, ',', $ak->pegawai->tanggal_lahir ?? '-' }}</td>
    </tr>
    <tr>
	<td class="col-no">5</td>
        <td>Jenis Kelamin</td>
        <td colspan="4">{{ '-' ?? '-' }}</td>
    </tr>
    <tr>
	<td class="col-no">6</td>
        <td>Pangkat/Golongan Ruang/TMT</td>
        <td colspan="4">{{ $ak->pegawai->pangkat ?? '-' }}</td>
    </tr>
    <tr>
	<td class="col-no">7</td>
        <td>Jabatan</td>
        <td colspan="4">{{ $ak->pegawai->jabatan_saat_ini ?? '-' }}</td>
    </tr>
    <tr>
	<td class="col-no">8</td>
        <td>Unit Kerja</td>
        <td colspan="4">{{ $ak->pegawai->unit_kerja ?? '-' }}</td>
    </tr>
    <tr>
        <th colspan="7" class="text-center fw-bold">HASIL PENILAIAN ANGKA KREDIT</th>
    </tr>
</tr>
    <tr>
        <th>II</th>
        <th colspan="2">PENETAPAN ANGKA KREDIT</th>
        <th>Lama</th>
        <th>Baru</th>
        <th>Jumlah</th>
	<th>Keterangan</th>
    </tr>
    <tr>
        <th rowspan="6"></th>
    </tr>
    <tr>
        <td>1</td>
        <td>AK Dasar yang diberikan</td>
        <td>-</td>
        <td>{{ $ak->ak_dasar ?? 0 }}</td>
        <td>{{ $ak->ak_dasar + "0",2 }}</td>
        <td>{{ '-' }}</td>
    </tr>
    <tr>
    	<td>2</td>
    	<td>AK JF Lama</td>
    	<td>-</td>
    	<td class="text-center">{{ number_format($ak_jf_lama, 2) }}</td>
    	<td class="text-center">{{ number_format($ak_jf_lama, 2) }}</td>
    	<td>-</td>
    </tr>
    <tr>
    	<td>3</td>
    	<td>AK Penyesuaian/Penyetaraan</td>
    	<td>-</td>
    	<td class="text-center">{{ number_format($ak_penyesuaian, 2) }}</td>
    	<td class="text-center">{{ number_format($ak_penyesuaian, 2) }}</td>
    	<td>-</td>
    </tr>
    <tr>
    	<td>4</td>
    	<td>AK Konversi</td>
    	<td>-</td>
    	<td class="text-center">{{ number_format($ak_konversi, 2) }}</td>
    	<td class="text-center">{{ number_format($ak_konversi, 2) }}</td>
    	<td>-</td>
    </tr>
    <tr>
    	<td>5</td>
    	<td>AK yang diperoleh dari peningkatan pendidikan</td>
    	<td>-</td>
    	<td class="text-center">{{ number_format($ak_pendidikan, 2) }}</td>
    	<td class="text-center">{{ number_format($ak_pendidikan, 2) }}</td>
    	<td>-</td>
    </tr>
    <tr>
        <th colspan="3" class="text-left">JUMLAH ANGKA KREDIT KUMULATIF</th>
    	<th class="text-center">{{ number_format($ak_total, 2) }}</th>
    	<th class="text-center">{{ number_format($ak_total, 2) }}</th>
    	<th class="text-center">{{ number_format($ak_total, 2) }}</th>
    	<th>-</th>
    </tr>
    <tr>
        <th colspan="3" class="text-center">Keterangan</th>
        <th colspan="2" class="text-center">Pangkat</th>
        <th colspan="2" class="text-center">Jenjang Jabatan</th>
    </tr>
    <tr>
        <td colspan="3" class="text-left">Angka Kredit Minimal yang harus dipenuhi untuk kenaikan pangkat/jenjang</th>
        <td colspan="2" class="text-center">{{ $ak->ak_total }}</th>
        <td colspan="2" class="text-center">{{ $ak->ak_total }}</th>
    </tr>
    <tr>
        <td colspan="3" class="text-left">
    	@if($ak->ak_total > $ak->ak_minimal)
        Kelebihan/<span style="text-decoration: line-through;">Kekurangan</span>*) 
        Angka Kredit yang harus dicapai untuk kenaikan pangkat
    	@else
        <span style="text-decoration: line-through;">Kelebihan</span>/Kekurangan*) 
        Angka Kredit yang harus dicapai untuk kenaikan pangkat
    	@endif
	</td>
        <td colspan="2" class="text-center">{{ $ak->ak_total }}</th>
        <td colspan="2" class="text-center">{{ $ak->ak_total }}</th>
    </tr>
    <tr>
        <td colspan="3" class="text-left">
    	@if($ak->ak_total > $ak->ak_total)
        Kelebihan/<span style="text-decoration: line-through;">Kekurangan</span>*) 
        Angka Kredit yang harus dicapai untuk kenaikan jenjang
    	@else
        <span style="text-decoration: line-through;">Kelebihan</span>/Kekurangan*) 
        Angka Kredit yang harus dicapai untuk kenaikan jenjang
    	@endif
	</td>
        <td colspan="2" class="text-center">{{ $ak->ak_total }}</th>
        <td colspan="2" class="text-center">{{ $ak->ak_total }}</th>
    </tr>
    <tr>
    	<td colspan="7" style="font-family: 'Times New Roman', serif; font-size: 12pt;">
        <strong>DAPAT / <span style="text-decoration: line-through;">TIDAK DAPAT*</span></strong>
    	DIPERTIMBANGKAN UNTUK KENAIKAN PANGKAT/JENJANG JABATAN
    	SETINGKAT LEBIH TINGGI MENJADI
    	<span style="border-bottom:1px dotted #000; padding:0 6px;">
        {{ $pangkat_naik ?? '-' }}
    	</span>
    	JENJANG
    	<span style="border-bottom:1px dotted #000; padding:0 6px;">
        {{ $jenjang_naik ?? '-' }}
    	</span>
    	</td>
    </tr>

</table>


<div style="font-family:'Times New Roman', serif; font-size:12pt; margin-top:20px;">

    <table width="100%" cellspacing="0" cellpadding="0">
        <tr>
            <!-- KIRI -->
            <td width="60%" valign="top">
                <strong>ASLI</strong> Penetapan Angka Kredit untuk:<br>
                Jabatan Fungsional yang bersangkutan.<br><br>

                <strong>Tembusan disampaikan kepada:</strong><br>
                1. Pimpinan Instansi Pengusul;<br>
                2. Pejabat Penilai Kinerja;<br>
                3. Sekretaris Tim Penilai yang bersangkutan; dan<br>
                4. Pejabat Pimpinan Tinggi Pratama yang membidangi<br>
                &nbsp;&nbsp;&nbsp;kepegawaian/Bagian yang membidangi kepegawaian<br>
                &nbsp;&nbsp;&nbsp;yang bersangkutan;
            </td>

            <!-- KANAN -->
            <td width="40%" valign="top">
                Ditetapkan di
                <span style="border-bottom:1px dotted #000; padding:0 6px;">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                </span><br>

                Pada tanggal
                <span style="border-bottom:1px dotted #000; padding:0 6px;">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                </span><br><br>

                <strong>Pejabat Penilai Kinerja</strong><br><br><br><br>

                Nama Lengkap
                <span style="border-bottom:1px dotted #000; padding:0 6px;">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                </span><br>

                NIP.
                <span style="border-bottom:1px dotted #000; padding:0 6px;">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                </span>
            </td>
        </tr>
    </table>

    <br><br>

    <div style="font-size:11pt;">
        *) coret yang tidak perlu<br>
        **) dapat ditambahkan AK sesuai ketentuan peraturan perundang-undangan.
    </div>

</div>
	
 
<div class="mt-3">
        <a href="{{ route('pak.pdf', $ak->id) }}"
           class="btn btn-danger">
           Download PDF
        </a>

        <a href="{{ url()->previous() }}"
           class="btn btn-secondary">
           Kembali
        </a>
    </div>
</div>

@if($ak->status === 'MENUNGGU_SDMO')
<form method="POST" action="{{ route('pak.verifikasi.sdmo', $ak->id) }}">
    @csrf
    <button class="btn btn-success">Verifikasi SDMO</button>
</form>
@endif

@section('content')



