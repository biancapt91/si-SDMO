@extends('layouts.app')

@section('content')

<style>
    /* ==== KLEON STYLE ==== */
    .kleon-card {
        border: none !important;
        border-radius: 20px !important;
        box-shadow: 0 4px 18px rgba(0,0,0,0.06) !important;
        transition: 0.2s;
    }
    .kleon-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.09) !important;
    }
    .kleon-header {
        font-weight: 700;
        font-size: 1.25rem;
        color: #5b21b6;
    }
    .chart-card-header {
        background: linear-gradient(135deg, #6d28d9, #9333ea);
        border-radius: 20px 20px 0 0 !important;
        color: white !important;
        font-weight: bold;
        padding: 14px 20px;
    }
</style>

<div class="container py-4">

    <h2 class="fw-bold mb-4" style="color: #5b21b6;">Dashboard</h2>

    <!-- =============================== -->
    <!-- STAT CARDS ala KLEON -->
    <!-- =============================== -->
    <div class="row g-4 mb-4">

        <div class="col-md-3">
            <div class="card kleon-card p-3" style="background: #ede9fe;">
                <h6 class="text-muted">Total Pegawai</h6>
                <h2 class="fw-bold text-purple-700">{{ $totalPegawai }}</h2>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card kleon-card p-3" style="background: #dcfce7;">
                <h6 class="text-muted">Jenis ASN</h6>
                <p class="mb-1">PNS: <strong>{{ $jumlahASN['PNS'] ?? 0 }}</strong></p>
                <p class="mb-0">PPPK: <strong>{{ $jumlahASN['PPPK'] ?? 0 }}</strong></p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card kleon-card p-3" style="background: #cffafe;">
                <h6 class="text-muted">Pegawai ST</h6>
                <h2 class="fw-bold text-info">{{ $totalST }}</h2>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card kleon-card p-3" style="background: #fef9c3; cursor: pointer;" 
                 data-bs-toggle="modal" 
                 data-bs-target="#modalPensiunSoon"
                 role="button">
                <h6 class="text-muted">Akan Pensiun ≤ 1 Tahun</h6>
                <h2 class="fw-bold text-warning">{{ $pensiunSoon->count() }}</h2>
                <small class="text-muted"><i class="bi bi-hand-index"></i> Klik untuk lihat detail</small>
            </div>
        </div>

    </div>

    <!-- =============================== -->
    <!-- CHART SECTIONS ala KLEON -->
    <!-- =============================== -->
    <div class="row g-4">

        <div class="col-md-6">
            <div class="card kleon-card h-100">
                <div class="chart-card-header">
                    Jumlah Pegawai Berdasarkan Unit Kerja
                </div>
                <div class="card-body p-4">
                    <canvas id="chartUnitKerja" height="120"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card kleon-card h-100">
                <div class="chart-card-header" style="background: linear-gradient(135deg,#059669,#10b981);">
                    Jumlah Pegawai ST per Unit Kerja
                </div>
                <div class="card-body p-4">
                    <canvas id="chartST" height="120"></canvas>
                </div>
            </div>
        </div>

    </div>

<!-- =============================== -->
    <!-- CHART ROW 2 (TAMBAHAN BARU) -->
    <!-- =============================== -->
    <div class="row g-4 mt-4">

        <div class="col-md-6">
            <div class="card kleon-card h-100">
                <div class="chart-card-header" style="background: linear-gradient(135deg,#4f46e5,#6366f1);">
                    Jumlah Pegawai Berdasarkan Jabatan
                </div>
                <div class="card-body p-4">
                    <canvas id="chartJabatan" height="50"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card kleon-card h-100">
                <div class="chart-card-header" style="background: linear-gradient(135deg,#db2777,#f472b6);">
                    Jumlah Pegawai Berdasarkan Jenis Jabatan
                </div>
                <div class="chart-wrapper">
   			 <canvas id="chartJenisJabatan" width="50"></canvas>

   			 <div id="legendJenisJabatan" class="chart-legend-right"></div>
               </div>
        </div>

    </div>

</div>

<!-- =============================== -->
<!-- MODAL DAFTAR PEGAWAI PENSIUN -->
<!-- =============================== -->
<div class="modal fade" id="modalPensiunSoon" tabindex="-1" aria-labelledby="modalPensiunSoonLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg,#f59e0b,#fbbf24); color: white;">
                <h5 class="modal-title" id="modalPensiunSoonLabel">
                    <i class="bi bi-alarm"></i> Daftar Pegawai Akan Pensiun ≤ 1 Tahun
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if ($pensiunSoon->count() == 0)
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> Tidak ada pegawai yang akan pensiun dalam 12 bulan ke depan.
                    </div>
                @else
                    <table class="table table-striped table-hover align-middle">
                        <thead style="background: #5b21b6; color:white;">
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>NIP</th>
                                <th>Unit Kerja</th>
                                <th>Tanggal Pensiun</th>
                                <th>Sisa Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pensiunSoon as $p)
                                <tr class="{{ $p->diff <= 3 ? 'table-danger' : ($p->diff <= 6 ? 'table-warning' : '') }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $p->nama }}</td>
                                    <td>{{ $p->nip }}</td>
                                    <td>{{ $p->unit_kerja }}</td>
                                    <td>@tanggal($p->tanggal_pensiun)</td>
                                    <td>
                                        @if($p->diff <= 3)
                                            <span class="badge bg-danger">
                                                {{ $p->sisa_tahun }} tahun {{ $p->sisa_bulan }} bulan {{ $p->sisa_hari }} hari lagi
                                            </span>
                                        @elseif($p->diff <= 6)
                                            <span class="badge bg-warning">
                                                {{ $p->sisa_tahun }} tahun {{ $p->sisa_bulan }} bulan {{ $p->sisa_hari }} hari lagi
                                            </span>
                                        @else
                                            {{ $p->sisa_tahun }} tahun {{ $p->sisa_bulan }} bulan {{ $p->sisa_hari }} hari lagi
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>


<!-- =============================== -->
<!-- CHART JS (DATA TETAP SAMA) -->
<!-- =============================== -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
new Chart(document.getElementById('chartUnitKerja'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($chartUnitKerja->pluck('unit_kerja')) !!},
        datasets: [{
            label: 'Jumlah Pegawai',
            data: {!! json_encode($chartUnitKerja->pluck('total')) !!},
            backgroundColor: '#8b5cf6'
        }]
    },
    options: { 
        responsive: true,
        scales: { y: { beginAtZero: true }}
    }
});

new Chart(document.getElementById('chartST'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($chartST->pluck('unit_kerja_st')) !!},
        datasets: [{
            data: {!! json_encode($chartST->pluck('total')) !!},
            backgroundColor: ['#7c3aed','#6ee7b7','#60a5fa','#f472b6','#fbbf24']
        }]
    }
});

new Chart(document.getElementById('chartJabatan'), {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($chartJabatan->pluck('jabatan_saat_ini')) !!},
        datasets: [{
            data: {!! json_encode($chartJabatan->pluck('total')) !!},
            backgroundColor: ['#7c3aed','#6ee7b7','#60a5fa','#f472b6','#fbbf24']
        }]
    }
});

const chartJenisJabatan = new Chart(document.getElementById('chartJenisJabatan'), {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($chartJenisJabatan->pluck('jenis_jabatan')) !!},
        datasets: [{
            data: {!! json_encode($chartJenisJabatan->pluck('total')) !!},
            backgroundColor: ['#7c3aed','#6ee7b7','#60a5fa','#f472b6','#fbbf24']
        }]
    },
options: {
        plugins: {
            legend: { display: true } // 
        }
    }
});

// BUAT LEGEND CUSTOM DI KANAN
const legendContainer = document.getElementById('legendJenisJabatan');
const labels = chartJenisJabatan.data.labels;
const colors = chartJenisJabatan.data.datasets[0].backgroundColor;
const values = chartJenisJabatan.data.datasets[0].data;

labels.forEach((label, i) => {
    const item = document.createElement('div');
    item.innerHTML = `
        <span class="box" style="background:${colors[i]}"></span>
        ${label} — ${values[i]}
    `;
    legendContainer.appendChild(item);
});
</script>

@endsection
