<div id="form-pak_awal_pengangkatan" class="mb-4 d-none">
    <div class="card-body">

        <h5 class="fw-bold mb-3">Pengangkatan Pertama</h5>

        {{-- ================= MASA KERJA CPNS ================= --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">
                Masa Kerja PNS / CPNS
            </label>

            <div class="row g-2">
                <div class="col-md-3">
                    <input type="number"
                           min="0"
                           name="masa_kerja_tahun"
                           id="masa_kerja_tahun"
                           class="form-control"
                           placeholder="Tahun">
                </div>

                <div class="col-md-3">
                    <input type="number"
                           min="0"
                           max="11"
                           name="masa_kerja_bulan"
                           id="masa_kerja_bulan"
                           class="form-control"
                           placeholder="Bulan">
                </div>
            </div>

            <small class="text-muted">
                Contoh: 1 Tahun 10 Bulan → otomatis dihitung <strong>1,83</strong>
            </small>

            {{-- nilai hasil konversi (dipakai controller) --}}
            <input type="hidden"
                   name="masa_kerja_total"
                   id="masa_kerja_total">
        </div>


        {{-- ================= PREDIKAT KINERJA ================= --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Predikat Kinerja</label>
            <select name="predikat_kinerja" class="form-select">
                <option value="">-- Pilih --</option>
                <option value="150">Sangat Baik</option>
                <option value="100">Baik</option>
                <option value="75">Butuh Perbaikan</option>
                <option value="50">Kurang</option>
                <option value="25">Sangat Kurang</option>
            </select>

        </div>

        {{-- ================= JENJANG / KOEFISIEN ================= --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Jenjang Jabatan</label>
            <select name="jenjang" class="form-select">
                <option value="">-- Pilih --</option>
                <option value="3.75">Pemula</option>
                <option value="5">Terampil</option>
                <option value="12.5">Mahir</option>
                <option value="25">Penyelia</option>
                <option value="12.5">Ahli Pertama</option>
                <option value="25">Ahli Muda</option>
                <option value="37.5">Ahli Madya</option>
                <option value="50">Ahli Utama</option>
            </select>
        </div>

<!-- ================= SUBMIT ================= -->
        <div class="mt-4">
            <button class="btn btn-primary">
                Simpan
            </button>
            <a href="{{ route('ak.index') }}" class="btn btn-secondary">
	    <onclick="
  	    document.getElementById('jenis_pak_awal').value='pak_awal_pengangkatan';">
                Kembali
            </a>
        </div>

        {{-- ================= INFO RUMUS ================= --}}
        <small class="text-muted">
            Rumus: <br>
            <strong>Masa Kerja CPNS × Predikat Kinerja × Koefisien Jenjang</strong>
        </small>

    </div>
</div>
