@extends('layouts.app')
@section('sidebar')
    <div class="row">{{-- SIDEBAR --}}
    @include('pengembangan-kompetensi.sidebar')
    </div>
@endsection
@section('content')

<div class="container-fluid py-4">

    <h3 class="fw-bold mb-1">Pengembangan Kompetensi</h3>
    <p class="text-muted mb-4">
        Data standar, capaian, gap, dan rencana pengembangan kompetensi pegawai.
    </p>

        {{-- KONTEN --}}
        <div class="col-md-9">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    @yield('kompetensi-content')
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
