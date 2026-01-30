@extends('layouts.app')
@section('sidebar')
<div class="row">{{-- SIDEBAR --}}
    @include('career-map.sidebar')
    </div>
@endsection
@section('content')

{{-- Header Section --}}
<div class="mb-4">
    <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);">
        <div class="card-body p-4">
            <div class="d-flex align-items-center">
                <div class="me-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" 
                         style="width: 64px; height: 64px; background: rgba(255,255,255,0.2);">
                        <i class="bi bi-calculator-fill text-white" style="font-size: 2rem;"></i>
                    </div>
                </div>
                <div class="flex-grow-1 text-white">
                    <h3 class="fw-bold mb-1">Perhitungan Angka Kredit</h3>
                    <p class="mb-0 opacity-90">Kelola dan hitung angka kredit pegawai dengan mudah dan akurat</p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- NAV TAB - Modern Design --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-0">
        <ul class="nav nav-pills nav-fill p-2" role="tablist" style="gap: 8px;">
            <li class="nav-item">
                <button class="nav-link active rounded-3 py-3"
                        data-bs-toggle="tab"
                        data-bs-target="#tab-pegawai"
                        type="button"
                        style="transition: all 0.3s;">
                    <i class="bi bi-people-fill me-2"></i>
                    <span class="fw-semibold">Hitung AK Pegawai</span>
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link rounded-3 py-3"
                        data-bs-toggle="tab"
                        data-bs-target="#tab-saya"
                        type="button"
                        style="transition: all 0.3s;">
                    <i class="bi bi-person-circle me-2"></i>
                    <span class="fw-semibold">Hitung AK Saya</span>
                </button>
            </li>
        </ul>
    </div>
</div>

<style>
    .nav-pills .nav-link {
        color: #64748b;
        background: transparent;
    }
    .nav-pills .nav-link:hover {
        background: #f1f5f9;
        color: #1e3a8a;
    }
    .nav-pills .nav-link.active {
        background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(30, 58, 138, 0.3);
    }
</style>

{{-- TAB CONTENT --}}
<div class="tab-content">

    {{-- TAB PEGAWAI --}}
    <div class="tab-pane fade show active" id="tab-pegawai">
        @include('angka_kredit.partials.form_pegawai')

    </div>

    {{-- TAB SAYA --}}
    <div class="tab-pane" id="tab-saya">
        @include('angka_kredit.partials.form_saya')

    </div>

</div>

@endsection
