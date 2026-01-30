<input type="hidden" name="jenis_pak_awal" value="pak_awal_perpindahan">
<div id="form-pak_awal_perpindahan" class="mb-4 d-none">
    <div class="card-body">

        {{-- =====================================================
             FORM A : JF → JF
             (hanya AK di JF sebelumnya)
        ====================================================== --}}
        <div id="form-jf-ke-jf" class="d-none mb-4">
            <h6 class="fw-bold mb-3">
                Jabatan Fungsional ke Jabatan Fungsional Lainnya
            </h6>

            <div class="mb-3">
                <label class="form-label fw-semibold">
                    Angka Kredit di Jabatan Fungsional Sebelumnya
                </label>
                <input type="number"
                       step="0.01"
                       name="ak_jf_sebelumnya"
		       id="ak_jf_sebelumnya"
                       class="form-control">
            </div>
	<!-- ================= SUBMIT ================= -->
		<button type="submit"
        	name="sub_aksi_perpindahan"
        	value="jf_ke_jf"
        	class="btn btn-primary">
    		💾 Simpan Perpindahan JF → JF
		</button>

	</div>
        
        {{-- =====================================================
             FORM B : JA → JF
             (lanjutan setelah pilih "JA → JF")
        ====================================================== --}}

        {{-- SUB STEP : KESESUAIAN --}}
        <div id="kesesuaian-wrapper" class="d-none mb-4">
            <h6 class="fw-bold mb-2">
                Jabatan Administrasi ke Jabatan Fungsional
            </h6>

            <label class="form-label fw-semibold">
                Kesesuaian Jenjang & Golongan Ruang
            </label>

            <div class="form-check">
                <input class="form-check-input"
                       type="radio"
                       name="kesesuaian"
                       value="sesuai">
                <label class="form-check-label">
                    Jenjang dan Golongan Ruang Sesuai
                </label>
            </div>

            <div class="form-check">
                <input class="form-check-input"
                       type="radio"
                       name="kesesuaian"
                       value="tidak_sesuai">
                <label class="form-check-label">
                    Jenjang dan Golongan Ruang Tidak Sesuai
                </label>
            </div>
        </div>

        {{-- =====================================================
             FORM B1 : JA → JF (SESUAI)
             (FORM LAMA — DIPERTAHANKAN)
        ====================================================== --}}
        <div id="form-ja-jf-sesuai" class="d-none mb-4">

            {{-- ================= PREDIKAT KINERJA ================= --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">
                    Predikat Kinerja
                </label>
                <select name="predikat_kinerja" class="form-select">
                    <option value="">-- Pilih --</option>
                    <option value="1.5">Sangat Baik</option>
                    <option value="1">Baik</option>
                    <option value="0.75">Butuh Perbaikan</option>
                    <option value="0.5">Kurang</option>
                    <option value="0.25">Sangat Kurang</option>
                </select>
            </div>

            {{-- ================= JENJANG / KOEFISIEN ================= --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">
                    Jenjang Jabatan
                </label>
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

            {{-- ================= MASA KERJA KEPANGKATAN (OPSIONAL) ================= --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">
                    <i class="bi bi-calendar-range me-1"></i>Masa Kerja Kepangkatan (Opsional)
                </label>
                <small class="text-muted d-block mb-2">Maksimal 3 tahun. Pembulatan: ≥6 bulan = naik, &lt;6 bulan = turun</small>

                <div class="row g-2">
                    <div class="col-md-2">
                        <div class="input-group">
                            <input type="number"
                                   min="0"
                                   max="3"
                                   name="masa_kepangkatan_tahun"
                                   id="masa_kepangkatan_tahun"
                                   class="form-control"
                                   placeholder="0"
                                   value="0">
                            <span class="input-group-text">Tahun</span>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="input-group">
                            <input type="number"
                                   min="0"
                                   max="11"
                                   name="masa_kepangkatan_bulan"
                                   id="masa_kepangkatan_bulan"
                                   class="form-control"
                                   placeholder="0"
                                   value="0">
                            <span class="input-group-text">Bulan</span>
                        </div>
                    </div>
                </div>

                <input type="hidden"
                       name="masa_kerja_kepangkatan"
                       id="masa_kerja_kepangkatan">
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

            <div small class="text-muted">
                Rumus:
                <strong>
                    × Predikat Kinerja × Koefisien Jenjang × Masa Kerja Kepangkatan 
                    + Angka Kredit Dasar
                </strong>
            </small>
	    </div>

		<button type="submit"
        name="sub_aksi_perpindahan"
        value="ja_jf_sesuai"
        class="btn btn-primary">
    	💾 Simpan
	</button>

        </div>

        {{-- =====================================================
             FORM B2 : JA → JF (TIDAK SESUAI)
             (AK OTOMATIS)
        ====================================================== --}}
        <div id="form-ja-jf-tidak-sesuai" class="d-none mb-4">
            <h6 class="fw-bold mb-3">
                Penetapan Angka Kredit (Tidak Sesuai)
            </h6>

            <div class="row">
                <div class="col-md-4">
                    <label class="form-label">Golongan Ruang</label>
                    <select id="golongan" class="form-select">
    			<option value="">-- Pilih --</option>
    			<option value="III/b">III/b</option>
    			<option value="III/c">III/c</option>
    			<option value="III/d">III/d</option>
    			<option value="IV/a">IV/a</option>
    			<option value="IV/b">IV/b</option>
		    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Jenjang</label>
                    <select id="jenjang_tidak_sesuai" class="form-select">
    			<option value="">-- Pilih --</option>
    			<option value="Ahli Pertama">Ahli Pertama</option>
    			<option value="Ahli Muda">Ahli Muda</option>
    			<option value="Ahli Madya">Ahli Madya</option>
		    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Angka Kredit</label>
                    <input type="number"
                           id="ak_otomatis"
                           name="ak_otomatis"
                           class="form-control"
                           readonly>
                
</div>
</div>

        	<br><button type="submit"
        	name="sub_aksi_perpindahan"
        	value="ja_jf_tidak_sesuai"
        	class="btn btn-primary">
    		💾 Simpan
		</button> 
        </div>
    </div>

</div>

<script>
function setSubAksi(value) {
    document.getElementById('sub_aksi_perpindahan').value = value;
}

/* === SAAT FORM DITAMPILKAN === */
document.addEventListener('DOMContentLoaded', function () {

    // JF → JF
    const jfForm = document.getElementById('form-jf-ke-jf');
    if (jfForm) {
        jfForm.addEventListener('click', () => setSubAksi('jf_ke_jf'));
    }

    // JA → JF SESUAI
    const jaSesuai = document.getElementById('form-ja-jf-sesuai');
    if (jaSesuai) {
        jaSesuai.addEventListener('click', () => setSubAksi('ja_jf_sesuai'));
    }

    // JA → JF TIDAK SESUAI
    const jaTidak = document.getElementById('form-ja-jf-tidak-sesuai');
    if (jaTidak) {
        jaTidak.addEventListener('click', () => setSubAksi('ja_jf_tidak_sesuai'));
    }

});
</script>

