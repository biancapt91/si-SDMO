<!DOCTYPE html>
<html>
<head>
    <title>Riwayat Jabatan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

<h3>Riwayat Jabatan: {{ $pegawai->nama }}</h3>

<a href="{{ route('riwayat.create', $pegawai->id) }}" class="btn btn-primary mb-3">
    + Tambah Riwayat Jabatan
</a>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif


<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Jabatan</th>
            <th>Unit Kerja</th>
            <th>TMT Mulai</th>
            <th>TMT Selesai</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($riwayat as $r)
	<tr>
            <td>{{ $r->jabatan ?? '-' }}</td>
            <td>{{ $r->unit_kerja }}</td>
            <td>{{ \Carbon\Carbon::parse ($r->tmt_mulai)->translatedFormat('d F Y') }}</td>
            <td>{{ $r->tmt_selesai ?? '-' }}</td>
            <td>
                <a href="{{ route('riwayat.edit', $r->id) }}" class="btn btn-sm btn-warning">Edit</a>

                <form action="{{ route('riwayat.destroy', $r->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Yakin hapus?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger">Hapus</button>
                </form>
            </td>
        </tr>
        @empty
        <tr><td colspan="5" class="text-center">Belum ada riwayat</td></tr>
        @endforelse
    </tbody>
</table>

<a href="{{ route('pegawai.index') }}" class="btn btn-secondary mt-3">Kembali ke Pegawai</a>

</body>
</html>
