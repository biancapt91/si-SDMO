<!DOCTYPE html>
<html>
<head>
    <title>Edit Pegawai</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

<h2 class="mb-3">Edit Pegawai</h2>

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('pegawai.update', $pegawai->id) }}" method="POST" class="w-50">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label class="form-label">NIP</label>
        <input type="text" name="nip" class="form-control" value="{{ $pegawai->nip }}">
    </div>

    <div class="mb-3">
        <label class="form-label">Nama</label>
        <input type="text" name="nama" class="form-control" value="{{ $pegawai->nama }}">
    </div>

    <div class="mb-3">
        <label class="form-label">Pangkat/Golongan</label>
        <input type="text" name="pangkat_golongan" class="form-control" value="{{ $pegawai->pangkat_golongan }}">
    </div>

    <div class="mb-3">
        <label class="form-label">TMT Pangkat/Golongan</label>
        <input type="date" name="tmt_pangkat" class="form-control" value="{{ $pegawai->tmt_pangkat }}">
    </div>

    <div class="mb-3">
        <label class="form-label">Jabatan_Saat_Ini</label>
        <input type="text" name="jabatan_saat_ini" class="form-control" value="{{ $pegawai->jabatan_saat_ini }}">
    </div>

    <div class="mb-3">
        <label class="form-label">Kelas Jabatan</label>
        <input type="text" name="kelas_jabatan" class="form-control" value="{{ $pegawai->kelas_jabatan }}">
    </div>

    <!-- UNIT KERJA SK -->
    <div class="mb-3">
        <label class="form-label">Unit Kerja SK</label>
        <select name="unit_kerja" class="form-select">
            <option value="">-- Pilih Unit Kerja --</option>

            @php
                $unitList = [
                    "MAHKAMAH KONSTITUSI","KEPANITERAAN","BIRO PERENCANAAN DAN KEUANGAN","BIRO HUKUM DAN ADMINISTRASI KEPANITERAAN",
                    "Biro HUBUNGAN MASYARAKAT DAN PROTOKOL","BIRO UMUM","PUSAT PENELITIAN DAN PENGKAJIAN PERKARA, DAN PENGELOLAAN PERPUSTAKAAN",
                    "PUSAT TEKNOLOGI INFORMASI DAN KOMUNIKASI","PUSAT PENDIDIKAN PANCASILA DAN KONSTITUSI","INSPEKTORAT"
                ];
            @endphp

            @foreach($unitList as $unit)
                <option value="{{ $unit }}"
                    {{ old('unit_kerja', $pegawai->unit_kerja) == $unit ? 'selected' : '' }}>
                    {{ $unit }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- UNIT KERJA ST -->
    <div class="mb-3">
        <label class="form-label">Unit Kerja ST</label>
        <select name="unit_kerja_st" class="form-select">
            <option value="">-- Pilih Unit Kerja ST --</option>
            <option value="NONE" {{ old('unit_kerja_st', $pegawai->unit_kerja_st) == null ? 'selected' : '' }}>-- Tidak Ada ST --</option>

            @foreach($unitList as $unit)
                <option value="{{ $unit }}"
                    {{ old('unit_kerja_st', $pegawai->unit_kerja_st) == $unit ? 'selected' : '' }}>
                    {{ $unit }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- PENEMPATAN -->
    <div class="mb-3">
        <label class="form-label">Penempatan</label>
        <input type="text" 
               name="penempatan" 
               class="form-control" 
               value="{{ old('penempatan', $pegawai->penempatan) }}"
               placeholder="Contoh: Ruang 301, Gedung A">
    </div>

    <!-- STATUS PENEMPATAN -->
    <div class="mb-3">
        <label class="form-label">Status Penempatan</label>
        <select name="status_penempatan" class="form-select">
            <option value="">-- Pilih Status --</option>
            <option value="SK" {{ old('status_penempatan', $pegawai->status_penempatan) == 'SK' ? 'selected' : '' }}>SK</option>
            <option value="ST" {{ old('status_penempatan', $pegawai->status_penempatan) == 'ST' ? 'selected' : '' }}>ST</option>
        </select>
    </div>

    <!-- JENIS ASN -->
    <div class="mb-3">
        <label class="form-label">Jenis ASN</label>
        <select name="jenis_asn" class="form-select" required>
            <option value="">-- Pilih Jenis ASN --</option>
            <option value="PNS" {{ old('jenis_asn', $pegawai->jenis_asn) == 'PNS' ? 'selected' : '' }}>PNS</option>
            <option value="PPPK" {{ old('jenis_asn', $pegawai->jenis_asn) == 'PPPK' ? 'selected' : '' }}>PPPK</option>
        </select>
    </div>

    <!-- STATUS ASN -->
    <div class="mb-3">
        <label class="form-label">Status ASN</label>
        <select name="status_asn" class="form-select">
            <option value="">-- Pilih Status ASN --</option>
            <option value="Aktif" {{ old('status_asn', $pegawai->status_asn) == 'Aktif' ? 'selected' : '' }}>Aktif</option>
            <option value="Tugas Belajar" {{ old('status_asn', $pegawai->status_asn) == 'Tugas Belajar' ? 'selected' : '' }}>Tugas Belajar</option>
            <option value="Pemberhentian Sementara" {{ old('status_asn', $pegawai->status_asn) == 'Pemberhentian Sementara' ? 'selected' : '' }}>Pemberhentian Sementara</option>
            <option value="Bebas Tugas" {{ old('status_asn', $pegawai->status_asn) == 'Bebas Tugas' ? 'selected' : '' }}>Bebas Tugas</option>
            <option value="CTLN" {{ old('status_asn', $pegawai->status_asn) == 'CTLN' ? 'selected' : '' }}>CTLN</option>
        </select>
    </div>

    <div class="mb-3">
    	<label class="form-label">Jenis Kelamin</label>
    	<select name="jenis_kelamin" class="form-select" required>
        	<option value="">-- Pilih Jenis Kelamin --</option>
        	<option value="Laki-laki" {{ old('jenis_kelamin', $pegawai->jenis_kelamin ?? '') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
        	<option value="Perempuan" {{ old('jenis_kelamin', $pegawai->jenis_kelamin ?? '') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
        <label class="form-label">Tempat Lahir</label>
        <input type="text" name="tempat_lahir" class="form-control" value="{{ $pegawai->tempat_lahir }}">
    </div>

    <!-- TANGGAL LAHIR -->
    <div class="mb-3">
        <label class="form-label">Tanggal Lahir</label>
        <input type="date" name="tanggal_lahir" class="form-control" value="{{ $pegawai->tanggal_lahir }}">
    </div>

    <button type="submit" class="btn btn-primary">Update</button>
    <a href="{{ route('pegawai.index') }}" class="btn btn-secondary">Kembali</a>

</form>

</body>
</html>
