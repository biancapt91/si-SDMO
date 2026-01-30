@extends('layouts.app')

@section('content')

@include('pegawai.sidebar')   <!-- Tambahkan sidebar -->

<div class="content-right">   <!-- Geser konten ke kanan -->

    <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Detail Pegawai</h3>

    <a href="{{ route('pegawai.dosir', $pegawai->id) }}" class="btn btn-success text-white">
    Dosir
    </a>
</div>
<hr>

    <div class="d-flex justify-content-between align-items-center mb-3">
       <h4 class="mb-0">{{ $pegawai->nama }}</h4>

       <a href="{{ route('pegawai.edit', $pegawai->id) }}" class="btn btn-primary">
        Edit Pegawai
       </a>
    </div>

    <table class="table">
        <tr>
            <th>NIP</th>
            <td>{{ $pegawai->nip }}</td>
        </tr>

        <tr>
            <th>Pangkat/Golongan</th>
            <td>{{ $pegawai->pangkat_golongan ?? '-' }}</td>
        </tr>

        <tr>
            <th>TMT Pangkat/Golongan</th>
            <td>{{ $pegawai->tmt_pangkat ? \Carbon\Carbon::parse($pegawai->tmt_pangkat)->format('d F Y') : '-' }}</td>
        </tr>

        <tr>
            <th>Jabatan</th>
            <td>{{ $pegawai->jabatan_saat_ini ?? '-' }}</td>
        </tr>

        <tr>
            <th>Kelas Jabatan</th>
            <td>{{ $pegawai->kelas_jabatan ?? '-' }}</td>
        </tr>

        <tr>
            <th>Unit Kerja SK</th>
            <td>{{ $pegawai->unit_kerja ?? '-' }}</td>
        </tr>

        <tr>
            <th>Unit Kerja ST</th>
            <td>{{ $pegawai->unit_kerja_st ?? '-' }}</td>
        </tr>

        <tr>
            <th>Penempatan</th>
            <td>{{ $pegawai->penempatan ?? '-' }}</td>
        </tr>

        <tr>
            <th>Status Penempatan</th>
            <td>
                @if($pegawai->status_penempatan == 'SK')
                    <span class="badge bg-success">SK</span>
                @elseif($pegawai->status_penempatan == 'ST')
                    <span class="badge bg-info">ST</span>
                @else
                    {{ $pegawai->status_penempatan ?? '-' }}
                @endif
            </td>
        </tr>

        <tr>
            <th>Jenis ASN</th>
            <td>{{ $pegawai->jenis_asn ?? '-' }}</td>
        </tr>

	    <tr>
            <th>Jenis Kelamin</th>
            <td>{{ $pegawai->jenis_kelamin ?? '-' }}</td>
        </tr>
        
	    <tr>
            <th>Tempat Lahir</th>
            <td>{{ $pegawai->tempat_lahir ?? '-' }}</td>
        </tr>
        
	    <tr>
            <th>Tanggal Lahir</th>
            <td>@tanggal($pegawai->tanggal_lahir)</td>
        </tr>

        <tr>
            <th>Tanggal Pensiun</th>
            <td>@tanggal($pegawai->tanggal_pensiun)</td>
        </tr>

        <tr>
            <th>Status ASN</th>
            <td>{{ $pegawai->status_asn ?? '-' }}</td>
        </tr>
    </table>

    <br>

    <h4>Riwayat Jabatan</h4>

    <a href="{{ route('riwayat-jabatan.create', $pegawai->id) }}" class="btn btn-primary mb-3">
        + Tambah Riwayat Jabatan
    </a>

    <table class="table">
        <thead>
            <tr>
                <th>Jabatan</th>
                <th>Jenis Jabatan</th>
                <th>TMT Mulai</th>
                <th>TMT Selesai</th>
                <th>Unit Kerja</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($pegawai->riwayatJabatan as $r)
                <tr>
                    <td>{{ $r->jabatan }}</td>
                    <td>{{ $r->jenis_jabatan }}</td>
                    <td>@tanggal($r->tmt_mulai)</td>
                    <td>
                        @if($r->tmt_selesai)
                            @tanggal($r->tmt_selesai)
                        @else
                            <span class="badge bg-success">Sedang diemban</span>
                        @endif
                    </td>
                    <td>{{ $r->unit_kerja }}</td>

                    <td>
                        <a href="{{ route('riwayat-jabatan.edit', $r->id) }}" class="btn btn-sm btn-warning">
                            Edit
                        </a>

                        <form action="{{ route('riwayat-jabatan.destroy', $r->id) }}" 
                              method="POST" 
                              style="display:inline-block;">
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Yakin ingin menghapus?')">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Belum ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <a href="{{ route('pegawai.index') }}" class="btn btn-secondary mt-3">← Kembali</a>

</div>

@endsection
