<!DOCTYPE html>
<html>
<head>
    <title>Tambah Pegawai</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

    <h2 class="mb-3">Tambah Pegawai</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('pegawai.store') }}" method="POST" class="w-50">
        @csrf

        <div class="mb-3">
            <label class="form-label">NIP</label>
            <input type="text" name="nip" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Nama</label>
            <input type="text" name="nama" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Pangkat/Golongan</label>
            <input type="text" name="pangkat_golongan" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">TMT Pangkat/Golongan</label>
            <input type="date" name="tmt_pangkat" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Jabatan_Saat_Ini</label>
            <input type="text" name="jabatan_saat_ini" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Kelas Jabatan</label>
            <input type="text" name="kelas_jabatan" class="form-control">
        </div>

        <div class="mb-3">
    	    <label class="form-label">Unit Kerja SK</label>
    	    <select name="unit_kerja" class="form-select">
        	<option value="">-- Pilih Unit Kerja --</option>
        	<option value="MAHKAMAH KONSTITUSI" {{ old('unit_kerja', $pegawai->unit_kerja ?? '') == 'MAHKAMAH KONSTITUSI' ? 'selected' : '' }}>MAHKAMAH KONSTITUSI</option>
        	<option value="KEPANITERAAN" {{ old('unit_kerja', $pegawai->unit_kerja ?? '') == 'KEPANITERAAN' ? 'selected' : '' }}>KEPANITERAAN</option>
        	<option value="BIRO PERENCANAAN DAN KEUANGAN" {{ old('unit_kerja', $pegawai->unit_kerja ?? '') == 'BIRO PERENCANAAN DAN KEUANGAN' ? 'selected' : '' }}>BIRO PERENCANAAN DAN KEUANGAN</option>
        	<option value="BIRO SUMBER DAYA MANUSIA DAN ORGANISASI" {{ old('unit_kerja', $pegawai->unit_kerja ?? '') == 'BIRO SUMBER DAYA MANUSIA DAN ORGANISASI' ? 'selected' : '' }}>BIRO SUMBER DAYA MANUSIA DAN ORGANISASI</option>
        	<option value="BIRO HUKUM DAN ADMINISTRASI KEPANITERAAN" {{ old('unit_kerja', $pegawai->unit_kerja ?? '') == 'BIRO HUKUM DAN ADMINISTRASI KEPANITERAAN' ? 'selected' : '' }}>BIRO HUKUM DAN ADMINISTRASI KEPANITERAAN</option>
        	<option value="BIRO HUBUNGAN MASYARAKAT DAN PROTOKOL" {{ old('unit_kerja', $pegawai->unit_kerja ?? '') == 'BIRO HUBUNGAN MASYARAKAT DAN PROTOKOL' ? 'selected' : '' }}>BIRO HUBUNGAN MASYARAKAT DAN PROTOKOL</option>
        	<option value="BIRO UMUM" {{ old('unit_kerja', $pegawai->unit_kerja ?? '') == 'BIRO UMUM' ? 'selected' : '' }}>BIRO UMUM</option>
        	<option value="PUSAT PENELITIAN DAN PENGKAJIAN PERKARA, DAN PENGELOLAAN PERPUSTAKAAN" {{ old('unit_kerja', $pegawai->unit_kerja ?? '') == 'PUSAT PENELITIAN DAN PENGKAJIAN PERKARA, DAN PENGELOLAAN PERPUSTAKAAN' ? 'selected' : '' }}>PUSAT PENELITIAN DAN PENGKAJIAN PERKARA, DAN PENGELOLAAN PERPUSTAKAAN</option>
        	<option value="PUSAT TEKNOLOGI INFORMASI DAN KOMUNIKASI" {{ old('unit_kerja', $pegawai->unit_kerja ?? '') == 'PUSAT TEKNOLOGI INFORMASI DAN KOMUNIKASI' ? 'selected' : '' }}>PUSAT TEKNOLOGI INFORMASI DAN KOMUNIKASI</option>
        	<option value="PUSAT PENDIDIKAN PANCASILA DAN KONSTITUSI" {{ old('unit_kerja', $pegawai->unit_kerja ?? '') == 'PUSAT PENDIDIKAN PANCASILA DAN KONSTITUSI' ? 'selected' : '' }}>PUSAT PENDIDIKAN PANCASILA DAN KONSTITUSI</option>
        	<option value="INSPEKTORAT" {{ old('unit_kerja', $pegawai->unit_kerja ?? '') == 'INSPEKTORAT' ? 'selected' : '' }}>INSPEKTORAT</option>
    	    </select>
	</div>

	<div class="mb-3">
            <label class="form-label">Unit Kerja ST</label>
            <select name="unit_kerja_st" class="form-select">
        	<option value="">-- Pilih Unit Kerja ST --</option>
		<option value="">-- Tidak Ada ST --</option>
        	<option value="MAHKAMAH KONSTITUSI" {{ old('unit_kerja_st', $pegawai->unit_kerja_st ?? '') == 'MAHKAMAH KONSTITUSI' ? 'selected' : '' }}>MAHKAMAH KONSTITUSI</option>
        	<option value="KEPANITERAAN" {{ old('unit_kerja_st', $pegawai->unit_kerja_st ?? '') == 'KEPANITERAAN' ? 'selected' : '' }}>KEPANITERAAN</option>
        	<option value="BIRO PERENCANAAN DAN KEUANGAN" {{ old('unit_kerja_st', $pegawai->unit_kerja_st ?? '') == 'BIRO PERENCANAAN DAN KEUANGAN' ? 'selected' : '' }}>BIRO PERENCANAAN DAN KEUANGAN</option>
        	<option value="BIRO SUMBER DAYA MANUSIA DAN ORGANISASI" {{ old('unit_kerja_st', $pegawai->unit_kerja_st ?? '') == 'BIRO SUMBER DAYA MANUSIA DAN ORGANISASI' ? 'selected' : '' }}>BIRO SUMBER DAYA MANUSIA DAN ORGANISASI</option>
        	<option value="BIRO HUKUM DAN ADMINISTRASI KEPANITERAAN" {{ old('unit_kerja_st', $pegawai->unit_kerja_st ?? '') == 'BIRO HUKUM DAN ADMINISTRASI KEPANITERAAN' ? 'selected' : '' }}>BIRO HUKUM DAN ADMINISTRASI KEPANITERAAN</option>
        	<option value="BIRO HUBUNGAN MASYARAKAT DAN PROTOKOL" {{ old('unit_kerja_st', $pegawai->unit_kerja_st ?? '') == 'BIRO HUBUNGAN MASYARAKAT DAN PROTOKOL' ? 'selected' : '' }}>BIRO HUBUNGAN MASYARAKAT DAN PROTOKOL</option>
        	<option value="BIRO UMUM" {{ old('unit_kerja_st', $pegawai->unit_kerja_st ?? '') == 'BIRO UMUM' ? 'selected' : '' }}>BIRO UMUM</option>
        	<option value="PUSAT PENELITIAN DAN PENGKAJIAN PERKARA, DAN PENGELOLAAN PERPUSTAKAAN" {{ old('unit_kerja_st', $pegawai->unit_kerja_st ?? '') == 'PUSAT PENELITIAN DAN PENGKAJIAN PERKARA, DAN PENGELOLAAN PERPUSTAKAAN' ? 'selected' : '' }}>PUSAT PENELITIAN DAN PENGKAJIAN PERKARA, DAN PENGELOLAAN PERPUSTAKAAN</option>
        	<option value="PUSAT TEKNOLOGI INFORMASI DAN KOMUNIKASI" {{ old('unit_kerja_st', $pegawai->unit_kerja_st ?? '') == 'PUSAT TEKNOLOGI INFORMASI DAN KOMUNIKASI' ? 'selected' : '' }}>PUSAT TEKNOLOGI INFORMASI DAN KOMUNIKASI</option>
        	<option value="PUSAT PENDIDIKAN PANCASILA DAN KONSTITUSI" {{ old('unit_kerja_st', $pegawai->unit_kerja_st ?? '') == 'PUSAT PENDIDIKAN PANCASILA DAN KONSTITUSI' ? 'selected' : '' }}>PUSAT PENDIDIKAN PANCASILA DAN KONSTITUSI</option>
        	<option value="INSPEKTORAT" {{ old('unit_kerja_st', $pegawai->unit_kerja_st ?? '') == 'INSPEKTORAT' ? 'selected' : '' }}>INSPEKTORAT</option>
		
    	    </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Penempatan</label>
            <input type="text" 
                   name="penempatan" 
                   class="form-control" 
                   value="{{ old('penempatan', $pegawai->penempatan ?? '') }}"
                   placeholder="Contoh: Ruang 301, Gedung A">
        </div>

        <div class="mb-3">
    	<label class="form-label">Status Penempatan</label>
    	<select name="status_penempatan" class="form-select">
        	<option value="">-- Pilih Status --</option>
        	<option value="SK" {{ old('status_penempatan', $pegawai->status_penempatan ?? '') == 'SK' ? 'selected' : '' }}>SK</option>
        	<option value="ST" {{ old('status_penempatan', $pegawai->status_penempatan ?? '') == 'ST' ? 'selected' : '' }}>ST</option>
            </select>
	</div>


	<div class="mb-3">
    	<label class="form-label">Jenis ASN</label>
    	<select name="jenis_asn" class="form-select" required>
        	<option value="">-- Pilih Jenis ASN --</option>
        	<option value="PNS" {{ old('jenis_asn', $pegawai->jenis_asn ?? '') == 'PNS' ? 'selected' : '' }}>PNS</option>
        	<option value="PPPK" {{ old('jenis_asn', $pegawai->jenis_asn ?? '') == 'PPPK' ? 'selected' : '' }}>PPPK</option>
    	</select>
	</div>

	<div class="mb-3">
    	<label class="form-label">Status ASN</label>
    	<select name="status_asn" class="form-select">
        	<option value="">-- Pilih Status ASN --</option>
        	<option value="Aktif" {{ old('status_asn', $pegawai->status_asn ?? '') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
        	<option value="Tugas Belajar" {{ old('status_asn', $pegawai->status_asn ?? '') == 'Tugas Belajar' ? 'selected' : '' }}>Tugas Belajar</option>
        	<option value="Pemberhentian Sementara" {{ old('status_asn', $pegawai->status_asn ?? '') == 'Pemberhentian Sementara' ? 'selected' : '' }}>Pemberhentian Sementara</option>
        	<option value="Bebas Tugas" {{ old('status_asn', $pegawai->status_asn ?? '') == 'Bebas Tugas' ? 'selected' : '' }}>Bebas Tugas</option>
        	<option value="CTLN" {{ old('status_asn', $pegawai->status_asn ?? '') == 'CTLN' ? 'selected' : '' }}>CTLN</option>
    	</select>
	</div>

	<div class="mb-3">
    	<label class="form-label">Jenis Kelamin</label>
    	<select name="jenis_kelamin" class="form-select" required>
        	<option value="">-- Pilih Jenis Kelamin --</option>
        	<option value="Laki-laki" {{ old('jenis_kelamin', $pegawai->jenis_kelamin ?? '') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
        	<option value="Perempuan" {{ old('jenis_kelamin', $pegawai->jenis_kelamin ?? '') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
    	</select>
	</div>

	<div class="mb-3">
    	    <label>Tempat Lahir</label>
    	    <input type="text" name="tempat_lahir" class="form-control" required>
	</div>
	
	<div class="mb-3">
    	    <label>Tanggal Lahir</label>
    	    <input type="date" name="tanggal_lahir" class="form-control" required>
	</div>


        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('pegawai.index') }}" class="btn btn-secondary">Kembali</a>
    </form>

</body>
</html>
