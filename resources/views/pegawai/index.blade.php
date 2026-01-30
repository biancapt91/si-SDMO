@extends('layouts.app')

@section('content')

@include('pegawai.sidebar')

<div class="content-right">
    
    <!-- Statistics Cards - Modern Design -->
    <style>
        .stat-card {
            background: linear-gradient(135deg, #3FA9F5 0%, #EEF2F6 100%);
            border-radius: 12px;
            border: 3px solid #ccccce;
            padding: 20px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            text-align: center;
            min-width: 120px;
            max-width: 150px;
            width: 100%;
        }
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.12);
        }
        .stat-card h6 {
            color: #353a43;
            font-size: 0.75rem;
            font-weight: 600;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            min-height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .stat-card h2 {
            color: #374151;
            font-size: 2rem;
            font-weight: 700;
            margin: 0;
        }
    </style>

    <div class="mb-4">
        <!-- Semua card dalam 1 baris --> <div class="row g-3">
            @php
                $semuaJenis = ['JPT Madya', 'JPT Pratama', 'Pengawas', 'Administrator', 'Pelaksana', 'JF Ahli Utama', 'JF Ahli Madya', 'JF Ahli Muda', 'JF Ahli Pertama', 'JF Keterampilan'];
                $statsData = $jenisJabatanStats->keyBy('jenis_jabatan');
            @endphp
            @foreach($semuaJenis as $jenis)
                <div class="col">
                    <div class="stat-card">
                        <h6>{{ $jenis }}</h6>
                        <h2>{{ $statsData->get($jenis)->total ?? 0 }}</h2>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <h2 class="fw-bold mb-4">Daftar Pegawai</h2>

    <div class="d-flex gap-2 mb-3">
        <a href="{{ route('pegawai.create') }}" class="btn btn-primary">
            + Tambah Pegawai
        </a>
        <a href="{{ route('pegawai.export.excel') }}" class="btn btn-success">
            <i class="bi bi-file-earmark-excel"></i> Download Excel
        </a>
    </div>
    
@if(auth()->user()->isAdmin())
<form action="{{ route('pegawai.import') }}"
      method="POST"
      enctype="multipart/form-data"
      class="card p-3 mb-3">

    @csrf

    <div class="mb-2">
        <label class="form-label fw-bold">Import Data Pegawai (Excel)</label>
        <input type="file"
               name="file"
               class="form-control"
               accept=".xlsx,.xls,.csv"
               required>
    </div>

    <div class="mb-2">
        <a href="{{ route('pegawai.template.excel') }}" class="btn btn-link btn-sm">Download Template Excel</a>
    </div>

    <button class="btn btn-primary btn-sm">
        Import Excel
    </button>

</form>
@endif

@if(session('success'))
    <div class="alert alert-success mt-2">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger mt-2">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<br>
<hr>
<form method="GET" class="row g-2 mb-3 align-items-end">

    <div class="col-md-3">
        <input type="text" 
               name="search" 
               class="form-control" 
               placeholder="Ketik nama atau NIP..." 
               value="{{ request('search') }}">
    </div>

    <div class="col-md-2">
        <select name="jabatan" class="form-select">
            <option value="">-- Semua Jabatan --</option>
            @foreach ($listJabatan as $j)
                <option value="{{ $j }}" {{ request('jabatan') == $j ? 'selected' : '' }}>
                    {{ $j }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-2">
        <select name="unit_kerja" class="form-select">
            <option value="">-- Semua Unit Kerja --</option>
            @foreach ($listUnit as $u)
                <option value="{{ $u }}" {{ request('unit_kerja') == $u ? 'selected' : '' }}>
                    {{ $u }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-2">
        <select name="jenis_asn" class="form-select">
            <option value="">-- Semua ASN --</option>
            @foreach ($listJenis as $j)
                <option value="{{ $j }}" {{ request('jenis_asn') == $j ? 'selected' : '' }}>
                    {{ $j }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-2">
        <select name="jenis_jabatan" class="form-select">
            <option value="">-- Semua Jenis Jabatan --</option>
            @foreach ($listJenisJabatan as $jj)
                <option value="{{ $jj }}" {{ request('jenis_jabatan') == $jj ? 'selected' : '' }}>
                    {{ $jj }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-1">
        <button type="submit" class="btn btn-primary w-100" title="Filter">
            <i class="bi bi-funnel"></i>
        </button>
    </div>

    @if(request()->anyFilled(['search', 'jabatan', 'unit_kerja', 'jenis_asn', 'jenis_jabatan']))
    <div class="col-12">
        <a href="{{ route('pegawai.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-x-circle"></i> Reset Filter
        </a>
        @if(request('search'))
        <span class="text-muted ms-2">
            Hasil pencarian: <strong>{{ $pegawai->total() }}</strong> pegawai
        </span>
        @endif
    </div>
    @endif

</form>

    @includeIf('pegawai.partials.table', ['pegawai' => $pegawai])
</div>

@endsection
