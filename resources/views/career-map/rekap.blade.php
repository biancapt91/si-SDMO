@extends('layouts.app')

@section('sidebar')
    <div class="row">{{-- SIDEBAR --}}
    @include('career-map.sidebar')
    </div>
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0">
        <span style="font-size: 22px;"></span> 
        Rekap Peta Karier Pegawai
    </h3>
    <div>
        <button class="btn btn-success" onclick="window.print()">
            <i class="bi bi-printer"></i> Cetak
        </button>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm border-0" style="background: linear-gradient(135deg, #10b981, #34d399);">
            <div class="card-body text-white text-center">
                <i class="bi bi-people-fill" style="font-size: 32px;"></i>
                <h3 class="mt-2 mb-0">{{ $totalPegawai }}</h3>
                <small>Total Pegawai</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0" style="background: linear-gradient(135deg, #3b82f6, #60a5fa);">
            <div class="card-body text-white text-center">
                <i class="bi bi-check-circle-fill" style="font-size: 32px;"></i>
                <h3 class="mt-2 mb-0">{{ $memenuhiSyarat }}</h3>
                <small>Memenuhi Syarat</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0" style="background: linear-gradient(135deg, #ef4444, #f87171);">
            <div class="card-body text-white text-center">
                <i class="bi bi-x-circle-fill" style="font-size: 32px;"></i>
                <h3 class="mt-2 mb-0">{{ $belumMemenuhi }}</h3>
                <small>Belum Memenuhi</small>
            </div>
        </div>
    </div>
</div>

<!-- Search Form -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('career-map.rekap') }}" class="row g-3">
            <div class="col-md-10">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Cari nama pegawai..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i> Cari</button>
            </div>
            @if(request('search'))
            <div class="col-12">
                <a href="{{ route('career-map.rekap') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-x-circle"></i> Reset Pencarian
                </a>
                <span class="text-muted ms-2">Hasil pencarian: <strong>{{ $pegawais->total() }}</strong> pegawai ditemukan</span>
            </div>
            @endif
        </form>
    </div>
</div>

<!-- Table Card -->
<div class="card shadow-sm">
    <div class="card-header" style="background: linear-gradient(135deg, #1e3a8a, #1e40af); color: white;">
        <h5 class="mb-0"><i class="bi bi-table"></i> Data Peta Karier Pegawai</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-bordered mb-0 modern-table">
                <thead style="background: #f8f9fa; position: sticky; top: 0; z-index: 10;">
                    <tr>
                        <th class="text-center" style="width: 50px;">No</th>
                        <th style="min-width: 200px;">Pegawai</th>
                        <th style="min-width: 250px;">Jabatan</th>
                        <th style="min-width: 150px;">Jenis Jabatan</th>
                        <th style="min-width: 150px;">Pangkat/Golongan</th>
                        <th class="text-center" style="width: 100px;">AK Saat Ini</th>
                        <th class="text-center" style="width: 120px;">Kebutuhan<br>Pangkat</th>
                        <th class="text-center" style="width: 120px;">Kebutuhan<br>Jenjang</th>
                        <th class="text-center" style="min-width: 130px;">Estimasi<br>Kenaikan Pangkat</th>
                        <th class="text-center" style="min-width: 130px;">Estimasi<br>Kenaikan Jenjang</th>
                        <th class="text-center" style="width: 150px;">Status</th>
                        <th class="text-center" style="width: 80px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pegawais as $i => $p)
                        <tr>
                            <td class="text-center">{{ ($pegawais->currentPage() - 1) * $pegawais->perPage() + $i + 1 }}</td>
                            <td>
                                <div class="fw-semibold">{{ $p->nama }}</div>
                                <small class="text-muted">{{ $p->nip }}</small>
                            </td>
                            <td>
                                <div>{{ $p->jabatan_saat_ini ?? '-' }}</div>
                                <small class="text-muted">{{ $p->unit_kerja ?? '-' }}</small>
                            </td>
                            <td>
                                @php
                                    $jabatanSekarang = $p->riwayatJabatan->where('tmt_selesai', null)->first();
                                @endphp
                                {{ $jabatanSekarang->jenis_jabatan ?? '-' }}
                            </td>
                            <td>{{ $p->pangkat_golongan ?? '-' }}</td>
                            <td class="text-center">
                                @if($p->ak_saat_ini === '-')
                                    -
                                @else
                                    <span class="badge bg-info" style="font-size: 13px;">{{ number_format($p->ak_saat_ini, 2) }}</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($p->ak_kebutuhan_pangkat === '-')
                                    -
                                @elseif($p->ak_kebutuhan_pangkat == 0)
                                    <span class="badge bg-success">Terpenuhi</span>
                                @else
                                    <span class="badge bg-warning text-dark">{{ $p->ak_kebutuhan_pangkat }}</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($p->ak_kebutuhan_jenjang === '-')
                                    -
                                @elseif($p->ak_kebutuhan_jenjang == 0)
                                    <span class="badge bg-success">Terpenuhi</span>
                                @else
                                    <span class="badge bg-warning text-dark">{{ $p->ak_kebutuhan_jenjang }}</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($p->estimasi_pangkat == 0 || $p->estimasi_pangkat == '-')
                                    -
                                @else
                                    @php
                                        try {
                                            $tanggal = \Carbon\Carbon::parse($p->estimasi_pangkat);
                                            echo $tanggal->isoFormat('D MMMM YYYY');
                                        } catch (\Exception $e) {
                                            echo $p->estimasi_pangkat;
                                        }
                                    @endphp
                                @endif
                            </td>
                            <td class="text-center">
                                @if($p->estimasi_jenjang == 0 || $p->estimasi_jenjang == '-')
                                    -
                                @else
                                    @php
                                        try {
                                            $tanggal = \Carbon\Carbon::parse($p->estimasi_jenjang);
                                            echo $tanggal->isoFormat('D MMMM YYYY');
                                        } catch (\Exception $e) {
                                            echo $p->estimasi_jenjang;
                                        }
                                    @endphp
                                @endif
                            </td>
                            <td class="text-center">
                                @if($p->ket_color == 'green')
                                    <span class="badge bg-success">{{ $p->keterangan }}</span>
                                @else
                                    <span class="badge bg-danger">{{ $p->keterangan }}</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('career-map.edit-ak', $p->id) }}" 
                                   class="btn btn-sm btn-outline-primary" 
                                   title="Edit Riwayat AK">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="text-center py-4">
                                <i class="bi bi-inbox" style="font-size: 48px; color: #d1d5db;"></i>
                                <p class="text-muted mt-2">Belum ada data pegawai</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Pagination -->
    @if($pegawais->hasPages())
    <div class="card-footer bg-white">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                Menampilkan {{ $pegawais->firstItem() }} - {{ $pegawais->lastItem() }} dari {{ $pegawais->total() }} pegawai
            </div>
            <div>
                <nav>
                    <ul class="pagination mb-0">
                        @if ($pegawais->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link">Previous</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $pegawais->previousPageUrl() }}">Previous</a>
                            </li>
                        @endif

                        @foreach ($pegawais->links()->elements[0] as $page => $url)
                            @if ($page == $pegawais->currentPage())
                                <li class="page-item active">
                                    <span class="page-link">{{ $page }}</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                </li>
                            @endif
                        @endforeach

                        @if ($pegawais->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $pegawais->nextPageUrl() }}">Next</a>
                            </li>
                        @else
                            <li class="page-item disabled">
                                <span class="page-link">Next</span>
                            </li>
                        @endif
                    </ul>
                </nav>
            </div>
        </div>
    </div>
    @endif
</div>

<style>
.modern-table {
    font-size: 14px;
}

.modern-table thead th {
    font-weight: 600;
    font-size: 16px;
    color: #111924;
    border-bottom: 2px solid #2d4c8a;
    padding: 10px;
    text-align: center;
    vertical-align: middle;
}

.modern-table tbody tr {
    transition: all 0.2s ease;
}

.modern-table tbody tr:hover {
    background-color: #f9fafb;
    transform: scale(1.001);
}

.modern-table td {
    padding: 12px;
    vertical-align: middle;
}

/* Pagination Styling */
.pagination {
    margin: 0;
}

.pagination .page-link {
    padding: 0.375rem 0.65rem;
    font-size: 13px;
    line-height: 1.2;
    display: flex;
    align-items: center;
    justify-content: center;
}

.pagination .page-link svg {
    width: 10px !important;
    height: 10px !important;
    max-width: 10px !important;
    max-height: 10px !important;
}

.pagination .page-item.active .page-link {
    background-color: #1e3a8a;
    border-color: #1e3a8a;
}

.pagination .page-link:hover {
    background-color: #f3f4f6;
}

@media print {
    .btn, .sidebar-left, .navbar {
        display: none !important;
    }
    
    .content-wrapper {
        margin-left: 0 !important;
        padding: 20px !important;
    }
    
    .card {
        box-shadow: none !important;
        border: 1px solid #ddd !important;
    }
}
</style>

@endsection
