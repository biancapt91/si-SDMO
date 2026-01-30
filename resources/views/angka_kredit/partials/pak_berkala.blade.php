

<div id="form-pak_berkala" class="d-none">
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <div class="alert alert-info border-0 shadow-sm d-flex align-items-center mb-0" role="alert" style="background: linear-gradient(135deg, #e0f2fe 0%, #dbeafe 100%);">
                <div class="rounded-circle p-2 me-3" style="background: #3b82f6;">
                    <i class="bi bi-info-circle-fill text-white" style="font-size: 1.25rem;"></i>
                </div>
                <div>
                    <strong class="text-primary">Penilaian Angka Kredit Berkala</strong><br>
                    <small class="text-muted">Lengkapi formulir di bawah untuk menghitung angka kredit</small>
                </div>
            </div>
        </div>
    </div>

    {{-- ================= PERIODE ================= --}}
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold">
                <i class="bi bi-calendar3 me-1"></i>Periode Penilaian (Tahun)
            </label>
            <select name="periode" class="form-select" required>
        @for ($i = now()->year; $i >= now()->year - 5; $i--)
            <option value="{{ $i }}"
                {{ old('periode') == $i ? 'selected' : '' }}>
                {{ $i }}
            </option>
        @endfor
        </select>
    </div>

    {{-- ================= JENIS PENILAIAN ================= --}}
    <div class="col-md-6 mb-3">
        <label class="form-label fw-semibold">
            <i class="bi bi-card-checklist me-1"></i>Jenis Penilaian
        </label><br>

        <div class="form-check">
            <input class="form-check-input jenis-penilaian"
                   type="radio"
                   name="jenis_penilaian"
                   value="tahunan"
                   checked>
            <label class="form-check-label">Tahunan</label>
        </div>

        <div class="form-check">
            <input class="form-check-input jenis-penilaian"
                   type="radio"
                   name="jenis_penilaian"
                   value="proporsional">
            <label class="form-check-label">Proporsional</label>
        </div>
    </div>
</div>

    {{-- ================= RENTANG BULAN (PROPORSIONAL) ================= --}}
    <div id="rentangBulan" class="row mb-3 d-none">
        <div class="col-md-6">
            <label class="form-label fw-semibold">Bulan Awal</label>
            <select name="bulan_awal" class="form-select">
                @foreach ([
                    'Januari','Februari','Maret','April','Mei','Juni',
                    'Juli','Agustus','September','Oktober','November','Desember'
                ] as $bulan)
                    <option value="{{ $bulan }}">{{ $bulan }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label fw-semibold">Bulan Akhir</label>
            <select name="bulan_akhir" class="form-select">
                @foreach ([
                    'Januari','Februari','Maret','April','Mei','Juni',
                    'Juli','Agustus','September','Oktober','November','Desember'
                ] as $bulan)
                    <option value="{{ $bulan }}">{{ $bulan }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- ================= PREDIKAT KINERJA ================= --}}
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold">
                <i class="bi bi-star-fill me-1"></i>Predikat Kinerja
            </label>
            <select name="predikat_kinerja_tahunan"
                    class="form-select"
                    required>
            <option value="">-- Pilih --</option>
            <option value="1.50">Sangat Baik</option>
            <option value="1.00">Baik</option>
            <option value="0.75">Butuh Perbaikan</option>
            <option value="0.50">Kurang</option>
            <option value="0.25">Sangat Kurang</option>
        </select>
    </div>

    {{-- ================= JENJANG / KOEFISIEN ================= --}}
    <div class="col-md-6 mb-3">
        <label class="form-label fw-semibold">
            <i class="bi bi-bar-chart-steps me-1"></i>Jenjang Jabatan
        </label>
        <select name="koef_jenjang" class="form-select">
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
    </div>

    {{-- ================= MASA KEPANGKATAN ================= --}}
    <div class="mb-3">
        <label class="form-label fw-semibold">
            <i class="bi bi-calendar-range me-1"></i>Masa Kepangkatan (Opsional)
        </label>
        <small class="text-muted d-block mb-2">Klaim masa kepangkatan terakhir sebelum menjadi JF</small>
        
        <div class="row g-2">
            <div class="col-md-2">
                <div class="input-group">
                    <input type="number"
                           min="0"
                           max="3"
                           name="masa_kepangkatan_tahun"
                           class="form-control"
                           placeholder="0"
                           value="{{ old('masa_kepangkatan_tahun', 0) }}">
                    <span class="input-group-text">Tahun</span>
                </div>
            </div>
            <div class="col-md-2">
                <div class="input-group">
                    <input type="number"
                           min="0"
                           max="11"
                           name="masa_kepangkatan_bulan"
                           class="form-control"
                           placeholder="0"
                           value="{{ old('masa_kepangkatan_bulan', 0) }}">
                    <span class="input-group-text">Bulan</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ================= AK DASAR ================= --}}
    <div class="mb-3">
        <label class="form-label fw-semibold">
            <i class="bi bi-calculator me-1"></i>Angka Kredit Dasar
        </label>
	    	<button type="button"
            class="btn btn-outline-primary btn-sm"
            data-bs-toggle="modal"
            data-bs-target="#modalReferensiAKDasar">
        📘 Tabel Referensi
    	</button>
        <input type="number"
               step="0.01"
               name="ak_dasar"
               class="form-control"
               placeholder="Contoh: 100">
        <small class="text-muted">
            Diisi jika ada ketentuan khusus (opsional)
        </small>
    </div>

{{-- ================= KLAIM PENDIDIKAN ================= --}}
<div class="mb-3">
    <label class="form-label fw-semibold">
        Klaim Pendidikan
    </label>

    <div class="form-check">
        <input class="form-check-input"
               type="radio"
               name="klaim_pendidikan"
               id="klaim_pendidikan_ya"
               value="ya">
        <label class="form-check-label" for="klaim_pendidikan_ya">
            Ya
        </label>
    </div>

    <div class="form-check">
        <input class="form-check-input"
               type="radio"
               name="klaim_pendidikan"
               id="klaim_pendidikan_tidak"
               value="tidak"
               checked>
        <label class="form-check-label" for="klaim_pendidikan_tidak">
            Tidak
        </label>
    </div>
</div>

</div>
{{-- Akhir form-pak_berkala --}}



<!-- MODAL REFERENSI PENYESUAIAN -->
<div class="modal fade" id="modalReferensiAKDasar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title fw-bold">
                    Tabel Referensi Angka Kredit Dasar
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body text-center">

                {{-- GANTI src DENGAN GAMBAR TABEL REFERENSI --}}
                <img src="{{ asset('/AK Dasar.jpg') }}"
                     class="img-fluid rounded border"
                     alt="Tabel Referensi AK Dasar">

                <p class="text-muted mt-3">
                    *Gunakan tabel ini sebagai acuan pengisian Angka Kredit Dasar
                </p>

            </div>

            <div class="modal-footer">
                <button type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                    Tutup
                </button>
            </div>

        </div>
    </div>
</div>



