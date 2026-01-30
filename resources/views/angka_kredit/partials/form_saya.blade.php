<form method="POST"
      action="{{ isset($isEdit) ? route('ak.update', $ak->id) : route('ak.store') }}">
    @csrf
    
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header border-0" style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);">
        <div class="d-flex align-items-center">
            <div class="rounded-circle p-2 me-3" style="background: rgba(255,255,255,0.2);">
                <i class="bi bi-calculator-fill text-white" style="font-size: 1.5rem;"></i>
            </div>
            <div>
                <h5 class="text-white fw-bold mb-0">Form Perhitungan Angka Kredit</h5>
                <small class="text-white opacity-75">Penilaian Angka Kredit Berkala</small>
            </div>
        </div>
    </div>
    <div class="card-body p-4">
        <div class="alert alert-info border-0 shadow-sm d-flex align-items-center mb-4" role="alert" style="background: linear-gradient(135deg, #e0f2fe 0%, #dbeafe 100%);">
            <div class="rounded-circle p-2 me-3" style="background: #3b82f6;">
                <i class="bi bi-info-circle-fill text-white" style="font-size: 1.25rem;"></i>
            </div>
            <div>
                <strong class="text-primary">Informasi Penting</strong><br>
                <small class="text-muted">Lengkapi formulir di bawah untuk menghitung angka kredit</small>
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
                    <input class="form-check-input"
                           type="radio"
                           name="jenis_penilaian"
                           value="tahunan"
                           checked>
                    <label class="form-check-label">Tahunan</label>
                </div>

                <div class="form-check">
                    <input class="form-check-input"
                           type="radio"
                           name="jenis_penilaian"
                           value="proporsional">
                    <label class="form-check-label">Proporsional</label>
                </div>
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
                <i class="bi bi-mortarboard me-1"></i>Klaim Pendidikan
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

        {{-- ================= SUBMIT ================= --}}
        <div class="mt-4">
            <button type="submit" class="btn btn-lg px-5" style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); border: none; color: white; box-shadow: 0 4px 12px rgba(30, 58, 138, 0.3); transition: all 0.3s;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(30, 58, 138, 0.4)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(30, 58, 138, 0.3)';">
                <i class="bi bi-save me-2"></i>Simpan Perhitungan
            </button>
            <a href="{{ route('ak.index') }}" class="btn btn-secondary btn-lg px-4">
                <i class="bi bi-arrow-left me-2"></i>Kembali
            </a>
        </div>
        
        <input type="hidden" name="pegawai_id" value="{{ $pegawaiSaya->id }}">
    </div>
</div>

<!-- MODAL REFERENSI AK DASAR -->
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

</form>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header border-0" style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);">
        <div class="d-flex align-items-center">
            <div class="rounded-circle p-2 me-3" style="background: rgba(255,255,255,0.2);">
                <i class="bi bi-clock-history text-white" style="font-size: 1.5rem;"></i>
            </div>
            <div>
                <h5 class="text-white fw-bold mb-0">Riwayat Perhitungan AK</h5>
                <small class="text-white opacity-75">Histori perhitungan angka kredit Anda</small>
            </div>
        </div>
    </div>
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width:40px">No</th>
                        <th class="text-center">Dibuat Pada</th>
                        <th class="text-center">Jabatan</th>
                        <th class="text-center">Periode</th>
                        <th class="text-center">Jenis Penilaian</th>
                        <th class="text-center">AK Total</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
        @forelse($riwayatSaya as $item)
        <tr>
            <td class="text-center">{{ $loop->iteration }}</td>
            <td class="text-center">{{ $item->created_at->format('d-m-Y H:i') }}</td>
            <td>{{ $item->jabatan }}</td>
            <td class="text-center">{{ $item->periode }}</td>
            <td class="text-center">
                <span class="badge bg-info">{{ $item->jenis_penilaian_label }}</span>
            </td>
            <td class="text-end"><strong>{{ number_format($item->ak_total,2) }}</strong></td>
            <td class="text-center">
                @if ($item->status == 'DRAFT')
                    <span class="badge bg-secondary"><i class="bi bi-file-earmark"></i> Draft</span>
                @elseif ($item->status == 'MENUNGGU_SDMO')
                    <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split"></i> Menunggu Verifikasi</span>
                @elseif ($item->status == 'DIVERIFIKASI_SDMO')
                    <span class="badge bg-info"><i class="bi bi-check-circle"></i> Diverifikasi</span>
                @elseif ($item->status == 'DISAHKAN_PPK')
                    <span class="badge bg-success"><i class="bi bi-check-circle-fill"></i> Disahkan</span>
                @endif
            </td>
	     <td class="text-center">
                <div class="btn-group" role="group">
                    <a href="{{ route('pak.show', $item->id) }}" 
                       class="btn btn-info btn-sm"
                       title="Lihat PAK">
                        <i class="bi bi-eye"></i>
                    </a>

                    @if ($item->status == 'DRAFT')
                        <!-- HAPUS -->
                        <form action="{{ route('ak.destroy', $item->id) }}"
                              method="POST"
                              class="d-inline"
                              onsubmit="return confirm('Yakin hapus draft ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                    title="Hapus Draft">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    @endif
                </div>
                
                @if ($item->status == 'DRAFT')
                    <!-- KIRIM USULAN -->
                    <form action="{{ route('ak.kirim-usulan', $item->id) }}"
                          method="POST"
                          class="d-inline mt-1"
                          onsubmit="return confirm('Kirim usulan AK ke SDMO?')">
                        @csrf
                        @method('PUT')
                        <button class="btn btn-success btn-sm w-100">
                            <i class="bi bi-send"></i> Kirim Usulan
                        </button>
                    </form>
                @endif
        </td>
        </tr>
        @empty
        <tr>
            <td colspan="8" class="text-center text-muted py-4">
                <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                <p class="mb-0 mt-2">Belum ada riwayat perhitungan AK</p>
            </td>
        </tr>
        @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-light">
        <h5 class="mb-0"><i class="bi bi-graph-up me-2"></i>Akumulasi Angka Kredit</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-primary">
                    <tr>
                        <th width="5%" class="text-center">No</th>
                        <th>Jabatan</th>
                        <th width="25%" class="text-end">Total Akumulasi AK</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($akumulasi as $i => $row)
                        <tr>
                            <td class="text-center">{{ $i + 1 }}</td>
                            <td><strong>{{ $row->jabatan }}</strong></td>
                            <td class="text-end">
                                <span class="badge bg-primary" style="font-size: 1rem;">
                                    {{ number_format($row->total_ak, 2) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">
                                <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                                <p class="mb-0 mt-2">Belum ada data akumulasi</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>


@push('scripts')
@include('angka_kredit.partials.script')
@endpush




