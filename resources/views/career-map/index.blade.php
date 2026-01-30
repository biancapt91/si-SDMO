@extends('layouts.app')

@section('sidebar')
    <div class="row">{{-- SIDEBAR --}}
    @include('career-map.sidebar')
    </div>
@endsection

@section('content')

<h3 class="fw-bold mb-4">
    <span style="font-size: 22px;"></span> 
    Peta Karier Saya
</h3>

<div class="card shadow-sm mb-4">
    <div class="card-header" style="background: linear-gradient(135deg, #1e3a8a, #1e40af); color: white;">
        <h5 class="mb-0"><i class="bi bi-person-badge"></i> Informasi Pegawai</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <td width="40%"><strong>Nama</strong></td>
                        <td>: {{ $pegawai->nama }}</td>
                    </tr>
                    <tr>
                        <td><strong>NIP</strong></td>
                        <td>: {{ $pegawai->nip }}</td>
                    </tr>
                    <tr>
                        <td><strong>Pangkat/Golongan</strong></td>
                        <td>: {{ $pegawai->pangkat_golongan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>TMT Pangkat/Golongan</strong></td>
                        <td>: {{ $pegawai->tmt_pangkat ? \Carbon\Carbon::parse($pegawai->tmt_pangkat)->format('d-m-Y') : '-' }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <td width="40%"><strong>Jabatan</strong></td>
                        <td>: {{ $pegawai->jabatan_saat_ini ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Unit Kerja</strong></td>
                        <td>: {{ $pegawai->unit_kerja ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Jenis Jabatan</strong></td>
                        <td>: {{ $pegawai->jenis_jabatan_aktif ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>TMT Jabatan</strong></td>
                        <td>: {{ $pegawai->tmt_mulai_aktif ? \Carbon\Carbon::parse($pegawai->tmt_mulai_aktif)->format('d-m-Y') : '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-header" style="background: linear-gradient(135deg, #1e3a8a, #1e40af); color: white;">
        <h5 class="mb-0"><i class="bi bi-graph-up-arrow"></i> Angka Kredit</h5>
    </div>
    <div class="card-body">
        <div class="row text-center">
            <div class="col-md-4">
                <div class="p-3 border rounded" style="background: #f0f9ff;">
                    <h6 class="text-muted mb-2">AK Saat Ini</h6>
                    <h2 class="text-primary mb-0">
                        @if($pegawai->ak_saat_ini === '-')
                            -
                        @else
                            {{ number_format($pegawai->ak_saat_ini, 2) }}
                        @endif
                    </h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 border rounded" style="background: #fef3c7;">
                    <h6 class="text-muted mb-2">Kebutuhan AK Pangkat</h6>
                    <h2 class="text-warning mb-0">{{ $pegawai->ak_kebutuhan_pangkat ?? '-' }}</h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 border rounded" style="background: #fce7f3;">
                    <h6 class="text-muted mb-2">Kebutuhan AK Jenjang</h6>
                    <h2 class="text-danger mb-0">{{ $pegawai->ak_kebutuhan_jenjang ?? '-' }}</h2>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card shadow-sm mb-4">
            <div class="card-header" style="background: linear-gradient(135deg, #10b981, #34d399); color: white;">
                <h5 class="mb-0"><i class="bi bi-calendar-event"></i> Estimasi Kenaikan Pangkat</h5>
            </div>
            <div class="card-body text-center">
                @if($pegawai->estimasi_pangkat == 0 || $pegawai->estimasi_pangkat == '-')
                    <h4 class="text-primary">-</h4>
                @else
                    <h4 class="text-primary">
                        @php
                            try {
                                $tanggal = \Carbon\Carbon::parse($pegawai->estimasi_pangkat);
                                echo $tanggal->isoFormat('D MMMM YYYY');
                            } catch (\Exception $e) {
                                echo $pegawai->estimasi_pangkat;
                            }
                        @endphp
                    </h4>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm mb-4">
            <div class="card-header" style="background: linear-gradient(135deg, #f59e0b, #fbbf24); color: white;">
                <h5 class="mb-0"><i class="bi bi-calendar-check"></i> Estimasi Kenaikan Jenjang</h5>
            </div>
            <div class="card-body text-center">
                @if($pegawai->estimasi_jenjang == 0 || $pegawai->estimasi_jenjang == '-')
                    <h4 class="text-primary">-</h4>
                @else
                    <h4 class="text-primary">
                        @php
                            try {
                                $tanggal = \Carbon\Carbon::parse($pegawai->estimasi_jenjang);
                                echo $tanggal->isoFormat('D MMMM YYYY');
                            } catch (\Exception $e) {
                                echo $pegawai->estimasi_jenjang;
                            }
                        @endphp
                    </h4>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header" style="background: linear-gradient(135deg, #7c3aed, #a78bfa); color: white;">
        <h5 class="mb-0"><i class="bi bi-clipboard-check"></i> Status Kenaikan</h5>
    </div>
    <div class="card-body text-center">
        <h3>
            <span class="badge" style="background: {{ $pegawai->ket_color }}; font-size: 18px; padding: 12px 24px;">
                {{ $pegawai->keterangan }}
            </span>
        </h3>
    </div>
</div>

{{-- Riwayat Perhitungan AK --}}
<div class="card shadow-sm mt-4">
    <div class="card-header" style="background: linear-gradient(135deg, #1e3a8a, #1e40af); color: white;">
        <h5 class="mb-0"><i class="bi bi-clock-history"></i> Riwayat Perhitungan Angka Kredit</h5>
    </div>
    <div class="card-body">
        @if($riwayatAK->isEmpty())
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i>Belum ada riwayat perhitungan angka kredit.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead style="background: #f8fafc;">
                        <tr>
                            <th width="5%">No</th>
                            <th width="15%">Tanggal</th>
                            <th width="15%">Periode</th>
                            <th>Jabatan</th>
                            <th width="15%">Jenis Penilaian</th>
                            <th width="12%" class="text-end">AK Total</th>
                            <th width="12%" class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($riwayatAK as $index => $ak)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($ak->created_at)->format('d M Y') }}</td>
                            <td>{{ $ak->periode ?? '-' }}</td>
                            <td>{{ $ak->jabatan ?? '-' }}</td>
                            <td>
                                @if($ak->pak_awal)
                                    <span class="badge bg-warning text-dark">PAK Awal</span>
                                @else
                                    <span class="badge bg-info">{{ strtoupper($ak->jenis_penilaian ?? '-') }}</span>
                                @endif
                            </td>
                            <td class="text-end fw-bold">{{ number_format($ak->ak_total, 2) }}</td>
                            <td class="text-center">
                                @if($ak->status == 'DRAFT' || $ak->status == 'Draft')
                                    <span class="badge bg-secondary">Draft</span>
                                @elseif($ak->status == 'Menunggu Verifikasi SDMO')
                                    <span class="badge bg-warning text-dark">Menunggu Verifikasi</span>
                                @elseif($ak->status == 'Disetujui SDMO')
                                    <span class="badge bg-success">Disetujui</span>
                                @else
                                    <span class="badge bg-primary">{{ $ak->status }}</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot style="background: #f8fafc;">
                        <tr>
                            <td colspan="5" class="text-end fw-bold">Total Akumulasi:</td>
                            <td class="text-end fw-bold text-primary" style="font-size: 1.1em;">
                                {{ number_format($riwayatAK->sum('ak_total'), 2) }}
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif
    </div>
</div>

@endsection
