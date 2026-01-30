@extends('layouts.app')

@section('content')

@include('pegawai.sidebar')

<div class="content-right">
    <h2 class="fw-bold mb-4">Profil Pegawai</h2>
    <div class="alert alert-warning">
        <i class="bi bi-exclamation-triangle"></i>
        Data pegawai Anda tidak ditemukan dalam sistem.
        <br>
        Silakan hubungi administrator untuk menambahkan data pegawai Anda.
    </div>
</div>

@endsection
