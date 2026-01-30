@extends('layouts.app')

@section('sidebar')
    <div class="row">
        @include('career-map.sidebar')
    </div>
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">
            <i class="bi bi-pencil-square"></i> Edit Riwayat Angka Kredit
        </h3>
        <p class="text-muted mb-0">{{ $pegawai->nama }} - {{ $pegawai->nip }}</p>
    </div>
    <div>
        <a href="{{ route('career-map.rekap') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Info Pegawai Card -->
<div class="card shadow-sm mb-4">
    <div class="card-header" style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);">
        <div class="d-flex align-items-center">
            <div class="rounded-circle p-2 me-3" style="background: rgba(255,255,255,0.2);">
                <i class="bi bi-person-badge-fill text-white" style="font-size: 1.5rem;"></i>
            </div>
            <div>
                <h5 class="text-white fw-bold mb-0">Informasi Pegawai</h5>
                <small class="text-white opacity-75">Data pegawai saat ini</small>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-sm table-borderless">
                    <tr>
                        <td width="150"><strong>Nama</strong></td>
                        <td>: {{ $pegawai->nama }}</td>
                    </tr>
                    <tr>
                        <td><strong>NIP</strong></td>
                        <td>: {{ $pegawai->nip }}</td>
                    </tr>
                    <tr>
                        <td><strong>Jabatan</strong></td>
                        <td>: {{ $pegawai->jabatan_saat_ini }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-sm table-borderless">
                    <tr>
                        <td width="180"><strong>Unit Kerja</strong></td>
                        <td>: {{ $pegawai->unit_kerja }}</td>
                    </tr>
                    <tr>
                        <td><strong>Pangkat/Gol</strong></td>
                        <td>: {{ $pegawai->pangkat_golongan }}</td>
                    </tr>
                    <tr>
                        <td><strong>TMT Pangkat/Gol</strong></td>
                        <td>: {{ $pegawai->tmt_pangkat ? \Carbon\Carbon::parse($pegawai->tmt_pangkat)->format('d/m/Y') : '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Tanggal Pensiun</strong></td>
                        <td>: {{ $pegawai->tanggal_pensiun ? \Carbon\Carbon::parse($pegawai->tanggal_pensiun)->format('d/m/Y') : '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Status ASN</strong></td>
                        <td>: {{ $pegawai->jenis_asn ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Total AK</strong></td>
                        <td>: <span class="badge bg-info">{{ number_format($pegawai->angkaKredits->sum('ak_total'), 2) }}</span></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Button Tambah AK -->
<div class="mb-3">
    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalTambahAk">
        <i class="bi bi-plus-circle"></i> Tambah Riwayat AK
    </button>
</div>

<!-- Table Riwayat AK -->
<div class="card shadow-sm">
    <div class="card-header" style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);">
        <h5 class="text-white mb-0">
            <i class="bi bi-table"></i> Riwayat Perhitungan Angka Kredit
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead style="background: #f8f9fa;">
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th style="min-width: 120px;">Tanggal</th>
                        <th style="min-width: 200px;">Periode</th>
                        <th style="min-width: 200px;">Jabatan</th>
                        <th style="min-width: 150px;">Jenis Penilaian</th>
                        <th class="text-center">AK Total</th>
                        <th style="min-width: 100px;">Status</th>
                        <th class="text-center" style="width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pegawai->angkaKredits as $i => $ak)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($ak->tanggal_penetapan)->format('d/m/Y') }}</td>
                            <td>
                                {{ \Carbon\Carbon::parse($ak->periode_awal)->format('d/m/Y') }} -
                                {{ \Carbon\Carbon::parse($ak->periode_akhir)->format('d/m/Y') }}
                            </td>
                            <td>{{ $ak->jabatan }}</td>
                            <td>{{ $ak->jenis_penilaian }}</td>
                            <td class="text-center">
                                <span class="badge bg-primary">{{ number_format($ak->ak_total, 2) }}</span>
                            </td>
                            <td>
                                @if($ak->status == 'verified')
                                    <span class="badge bg-success">Verified</span>
                                @elseif($ak->status == 'signed')
                                    <span class="badge bg-info">Signed</span>
                                @else
                                    <span class="badge bg-secondary">Draft</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <button type="button" 
                                        class="btn btn-sm btn-outline-primary me-1" 
                                        onclick="editAk({{ $ak->id }})"
                                        title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="{{ route('career-map.delete-ak', $ak->id) }}" 
                                      method="POST" 
                                      class="d-inline"
                                      onsubmit="return confirm('Yakin ingin menghapus riwayat AK ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center py-4">
                                <i class="bi bi-inbox" style="font-size: 48px; color: #d1d5db;"></i>
                                <p class="text-muted mt-2">Belum ada riwayat perhitungan AK</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if($pegawai->angkaKredits->count() > 0)
                <tfoot style="background: #f8f9fa;">
                    <tr>
                        <td colspan="8" class="text-end fw-bold">Total Akumulasi AK:</td>
                        <td class="text-center">
                            <span class="badge bg-success fs-6">{{ number_format($pegawai->angkaKredits->sum('ak_total'), 2) }}</span>
                        </td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah AK -->
<div class="modal fade" id="modalTambahAk" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('career-map.store-ak', $pegawai->id) }}" method="POST">
                @csrf
                <div class="modal-header" style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);">
                    <h5 class="modal-title text-white"><i class="bi bi-plus-circle"></i> Tambah Riwayat AK</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Penetapan <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_penetapan" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jenis Penilaian <span class="text-danger">*</span></label>
                            <select name="jenis_penilaian" class="form-select" required>
                                <option value="">-- Pilih --</option>
                                <option value="PAK Awal">PAK Awal</option>
                                <option value="PAK Berkala Tahunan">PAK Berkala Tahunan</option>
                                <option value="PAK Berkala Proporsional">PAK Berkala Proporsional</option>
                                <option value="PAK Integrasi">PAK Integrasi</option>
                                <option value="PAK Konversi">PAK Konversi</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Periode Awal <span class="text-danger">*</span></label>
                            <input type="date" name="periode_awal" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Periode Akhir <span class="text-danger">*</span></label>
                            <input type="date" name="periode_akhir" class="form-control" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Jabatan <span class="text-danger">*</span></label>
                        <input type="text" name="jabatan" class="form-control" value="{{ $pegawai->jabatan_saat_ini }}" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Predikat Kinerja</label>
                            <input type="number" name="predikat_kinerja" class="form-control" step="0.01">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Koefisien</label>
                            <input type="number" name="koefisien" class="form-control" step="0.01">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">AK Dasar</label>
                            <input type="number" name="ak_dasar" class="form-control" step="0.01">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">AK Total <span class="text-danger">*</span></label>
                        <input type="number" name="ak_total" class="form-control" step="0.01" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit AK -->
<div class="modal fade" id="modalEditAk" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="formEditAk" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header" style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);">
                    <h5 class="modal-title text-white"><i class="bi bi-pencil-square"></i> Edit Riwayat AK</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Penetapan <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_penetapan" id="edit_tanggal_penetapan" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jenis Penilaian <span class="text-danger">*</span></label>
                            <select name="jenis_penilaian" id="edit_jenis_penilaian" class="form-select" required>
                                <option value="">-- Pilih --</option>
                                <option value="PAK Awal">PAK Awal</option>
                                <option value="PAK Berkala Tahunan">PAK Berkala Tahunan</option>
                                <option value="PAK Berkala Proporsional">PAK Berkala Proporsional</option>
                                <option value="PAK Integrasi">PAK Integrasi</option>
                                <option value="PAK Konversi">PAK Konversi</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Periode Awal <span class="text-danger">*</span></label>
                            <input type="date" name="periode_awal" id="edit_periode_awal" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Periode Akhir <span class="text-danger">*</span></label>
                            <input type="date" name="periode_akhir" id="edit_periode_akhir" class="form-control" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Jabatan <span class="text-danger">*</span></label>
                        <input type="text" name="jabatan" id="edit_jabatan" class="form-control" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Predikat Kinerja</label>
                            <input type="number" name="predikat_kinerja" id="edit_predikat_kinerja" class="form-control" step="0.01">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Koefisien</label>
                            <input type="number" name="koefisien" id="edit_koefisien" class="form-control" step="0.01">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">AK Dasar</label>
                            <input type="number" name="ak_dasar" id="edit_ak_dasar" class="form-control" step="0.01">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">AK Total <span class="text-danger">*</span></label>
                        <input type="number" name="ak_total" id="edit_ak_total" class="form-control" step="0.01" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <textarea name="keterangan" id="edit_keterangan" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const akData = @json($pegawai->angkaKredits);

function editAk(id) {
    const ak = akData.find(item => item.id === id);
    if (!ak) return;
    
    // Set form action
    document.getElementById('formEditAk').action = `/career-map/ak/${id}`;
    
    // Fill form fields
    document.getElementById('edit_tanggal_penetapan').value = ak.tanggal_penetapan;
    document.getElementById('edit_periode_awal').value = ak.periode_awal;
    document.getElementById('edit_periode_akhir').value = ak.periode_akhir;
    document.getElementById('edit_jabatan').value = ak.jabatan;
    document.getElementById('edit_jenis_penilaian').value = ak.jenis_penilaian;
    document.getElementById('edit_predikat_kinerja').value = ak.predikat_kinerja || '';
    document.getElementById('edit_koefisien').value = ak.koefisien || '';
    document.getElementById('edit_ak_dasar').value = ak.ak_dasar || '';
    document.getElementById('edit_ak_total').value = ak.ak_total;
    document.getElementById('edit_keterangan').value = ak.keterangan || '';
    
    // Show modal
    new bootstrap.Modal(document.getElementById('modalEditAk')).show();
}
</script>

<style>
.table th {
    font-size: 13px;
    font-weight: 600;
}

.table td {
    font-size: 13px;
    vertical-align: middle;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
}
</style>

@endsection
