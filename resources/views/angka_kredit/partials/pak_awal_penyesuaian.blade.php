<input type="hidden" name="jenis_pak_awal_penyesuaian" value="pak_awal_penyesuaian">
<div id="form-pak_awal_penyesuaian" class="mb-4 d-none">
    <div class="card-body">

        <div class="d-flex align-items-center mb-3">
    <h5 class="fw-bold mb-0 me-3">
        Penyesuaian / Penyetaraan
    </h5>

     </div>
        {{-- ================= AK PENYESUAIAN ================= --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Angka Kredit Penyesuaian/Penyetaraan</label>
	
    	<button type="button"
            class="btn btn-outline-primary btn-sm"
            data-bs-toggle="modal"
            data-bs-target="#modalReferensiPenyesuaian">
        📘 Tabel Referensi
    	</button>
	    <input type="number"
            step="0.01"
            name="ak_penyesuaian"
            class="form-control">
	</div>


        {{-- ================= AK DASAR ================= --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Angka Kredit Dasar</label>
            <input type="number"
                   step="0.01"
                   name="ak_dasar"
                   class="form-control">
        </div>

        {{-- ================= INFO RUMUS ================= --}}
        <div class="alert alert-light border">
            <small class="text-muted">
                <strong>Rumus Perhitungan:</strong><br>
                Angka Kredit Penyesuaian (berdasarkan tabel) + Angka Kredit Dasar
            </small>
        </div>

        <div class="mt-4">
            <button class="btn btn-primary">
                Simpan
            </button>
            <a href="{{ route('ak.index') }}" class="btn btn-secondary">
	    <onclick="
  	    document.getElementById('jenis_pak_awal').value='pak_awal_penyesuaian';">
                Kembali
            </a>
        </div>

    </div>
</div>

<!-- MODAL REFERENSI PENYESUAIAN -->
<div class="modal fade" id="modalReferensiPenyesuaian" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title fw-bold">
                    Tabel Referensi Angka Kredit Penyesuaian / Penyetaraan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body text-center">

                {{-- GANTI src DENGAN GAMBAR TABEL REFERENSI --}}
                <img src="{{ asset('/AK Penyesuaian.jpg') }}"
                     class="img-fluid rounded border"
                     alt="Tabel Referensi Penyesuaian">

                <p class="text-muted mt-3">
                    *Gunakan tabel ini sebagai acuan pengisian Angka Kredit Penyesuaian/Penyetaraan
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

