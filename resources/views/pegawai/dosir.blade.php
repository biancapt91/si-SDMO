@extends('layouts.app')

@section('content')

@include('pegawai.sidebar')

<div class="content-right">

    <h3 class="d-flex justify-content-between align-items-center mb-3">
        <span>Dosir Pegawai - {{ $pegawai->nama }}</span>
    </h3>

    <!-- Form Upload -->
    <div class="card mb-4">
        <div class="card-header">Upload Dokumen</div>
        <div class="card-body">
            <form action="{{ route('pegawai.dosir.upload', $pegawai->id) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label>Nama Dokumen</label>
                    <input type="text" name="nama_dokumen" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>File Dokumen</label>
                    <input type="file" name="file" class="form-control" required>
                    <small class="text-muted">Format: pdf, jpg, png, doc, docx</small>
                </div>

                <button class="btn btn-primary">Upload</button>
            </form>
        </div>
    </div>

    <!-- Daftar Dokumen -->
    <h4>Daftar Dokumen</h4>

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Nama Dokumen</th>
                <th>File</th>
                <th>Tanggal Upload</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
        @forelse ($pegawai->dosir as $d)
            <tr>
                <td>{{ $d->nama_dokumen }}</td>

                <td>
                    <a href="{{ route('pegawai.dosir.download', $d->id) }}" target="_blank">
                        Download
                    </a>
                </td>

                <td>{{ $d->created_at->format('d-m-Y') }}</td>

                <td>
                    <form action="{{ route('pegawai.dosir.delete', $d->id) }}" method="POST" onsubmit="return confirm('Yakin hapus dokumen?')">
                        @csrf
                        @method('DELETE')

                        <button class="btn btn-danger btn-sm">Hapus</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center">Belum ada dokumen</td>
            </tr>
        @endforelse
        </tbody>
    </table>

</div>

@endsection
