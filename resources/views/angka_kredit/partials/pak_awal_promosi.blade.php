<form method="POST" action="{{ route('ak.store') }}">
@csrf
<div id="form-pak_awal_promosi" class="mb-4 d-none">
    <div class="card-body">

        <h5 class="fw-bold mb-3">Promosi</h5>

        {{-- ================= PREDIKAT KINERJA ================= --}}
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label fw-semibold">
                    Predikat Kinerja Tahun 1
                </label>
                <select name="predikat_th1" class="form-select">
                    <option value="">-- Pilih --</option>
                    <option value="150">Sangat Baik</option>
                    <option value="100">Baik</option>
                    <option value="75">Butuh Perbaikan</option>
                    <option value="50">Kurang</option>
                    <option value="25">Sangat Kurang</option>
                </select>
            </div>

            {{-- ================= JENJANG / KOEFISIEN ================= --}}
            <div class="col-md-4 mb-3">
                <label class="form-label fw-semibold">
                    Jenjang / Koefisien
                </label>
                <select name="jenjang_th1" class="form-select">
                    <option value="">-- Pilih --</option>
                    <option value="3.75">Pemula</option>
                    <option value="5">Terampil</option>
                    <option value="12.5">Mahir / Ahli Pertama</option>
                    <option value="25">Penyelia / Ahli Muda</option>
                    <option value="37.5">Ahli Madya</option>
                    <option value="50">Ahli Utama</option>
                </select>
            </div>
        </div>

	{{-- ================= PREDIKAT KINERJA TAHUN 2 ================= --}}
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label fw-semibold">
                    Predikat Kinerja Tahun 2
                </label>
                <select name="predikat_th2" class="form-select">
                    <option value="">-- Pilih --</option>
                    <option value="150">Sangat Baik</option>
                    <option value="100">Baik</option>
                    <option value="75">Butuh Perbaikan</option>
                    <option value="50">Kurang</option>
                    <option value="25">Sangat Kurang</option>
                </select>
            </div>

            {{-- ================= JENJANG / KOEFISIEN ================= --}}
            <div class="col-md-4 mb-3">
                <label class="form-label fw-semibold">
                    Jenjang / Koefisien
                </label>
                <select name="jenjang_th2" class="form-select">
                    <option value="">-- Pilih --</option>
                    <option value="3.75">Pemula</option>
                    <option value="5">Terampil</option>
                    <option value="12.5">Mahir / Ahli Pertama</option>
                    <option value="25">Penyelia / Ahli Muda</option>
                    <option value="37.5">Ahli Madya</option>
                    <option value="50">Ahli Utama</option>
                </select>
            </div>
        </div>


        {{-- ================= AK DASAR ================= --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">
                Angka Kredit Dasar
            </label>
            <input type="number"
                   step="0.01"
                   name="ak_dasar"
                   class="form-control">
        </div>

        {{-- ================= INFO RUMUS ================= --}}
        <div class="alert alert-light border">
            <small class="text-muted">
                <strong>Rumus Perhitungan:</strong><br>
                (Predikat Tahun 1 × Koefisien)
                + (Predikat Tahun 2 × Koefisien)
                + Angka Kredit Dasar
            </small>
        </div>

 	<div class="mt-4">
            <button type="submit" class="btn btn-primary">
	     Simpan
            </button>
            <a href="{{ route('ak.index') }}" class="btn btn-secondary">
	    <onclick="
  	    document.getElementById('jenis_pak_awal').value='pak_awal_promosi';">
                Kembali
            </a>
        </div>


    </div>
</div>
