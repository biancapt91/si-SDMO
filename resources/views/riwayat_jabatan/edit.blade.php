@extends('layouts.app')

@section('content')
<div class="container">

    <h3>Edit Riwayat Jabatan</h3>
    <hr>

    <form action="{{ route('riwayat-jabatan.update', $riwayat->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Jabatan</label>
            <input type="text" name="jabatan" class="form-control" value="{{ $riwayat->jabatan }}" required>
        </div>

        <div class="mb-3">
    	<label class="form-label">Jenis Jabatan</label>
    	<select name="jenis_jabatan" class="form-select" required>
        	<option value="">-- Pilih Jenis Jabatan --</option>
        	<option value="Pelaksana" {{ old('jenis_jabatan', $riwayat->jenis_jabatan ?? '') == 'Pelaksana' ? 'selected' : '' }}>Pelaksana</option>
		<option value="JF Keterampilan" {{ old('jenis_jabatan', $riwayat->jenis_jabatan ?? '') == 'JF Keterampilan' ? 'selected' : '' }}>JF Keterampilan</option>
        	<option value="JF Ahli Pertama" {{ old('jenis_jabatan', $riwayat->jenis_jabatan ?? '') == 'JF Ahli Pertama' ? 'selected' : '' }}>JF Ahli Pertama</option>
        	<option value="JF Ahli Muda" {{ old('jenis_jabatan', $riwayat->jenis_jabatan ?? '') == 'JF Ahli Muda' ? 'selected' : '' }}>JF Ahli Muda</option>
        	<option value="JF Ahli Madya" {{ old('jenis_jabatan', $riwayat->jenis_jabatan ?? '') == 'JF Ahli Madya' ? 'selected' : '' }}>JF Ahli Madya</option>
        	<option value="JF Ahli Utama" {{ old('jenis_jabatan', $riwayat->jenis_jabatan ?? '') == 'JF Ahli Utama' ? 'selected' : '' }}>JF Ahli Utama</option>
        	<option value="Administrator" {{ old('jenis_jabatan', $riwayat->jenis_jabatan ?? '') == 'Administrator' ? 'selected' : '' }}>Administrator</option>
        	<option value="Pengawas" {{ old('jenis_jabatan', $riwayat->jenis_jabatan ?? '') == 'Pengawas' ? 'selected' : '' }}>Pengawas</option>
        	<option value="JPT Pratama" {{ old('jenis_jabatan', $riwayat->jenis_jabatan ?? '') == 'JPT Pratama' ? 'selected' : '' }}>JPT Pratama</option>
        	<option value="JPT Madya" {{ old('jenis_jabatan', $riwayat->jenis_jabatan ?? '') == 'JPT Madya' ? 'selected' : '' }}>JPT Madya</option>
    	   </select>
	</div>

        <div class="mb-3">
            <label>TMT Mulai</label>
            <input type="date" name="tmt_mulai" class="form-control" value="{{ $riwayat->tmt_mulai }}" required>
        </div>

        <div class="mb-3">
            <label>TMT Selesai</label>
            <input type="date" name="tmt_selesai" class="form-control" value="{{ $riwayat->tmt_selesai }}">
            <small class="text-muted">Kosongkan jika jabatan masih diemban (sedang berlangsung)</small>
        </div>

        <div class="mb-3">
            <label>Unit Kerja</label>
            <input type="text" name="unit_kerja" class="form-control" value="{{ $riwayat->unit_kerja }}" required>
        </div>

        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ route('pegawai.show', $riwayat->pegawai_id) }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
