@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="fw-bold mb-3">Halo SDMO</h2>
    <p>Silakan ajukan pertanyaan, masukan, atau permohonan layanan ke SDMO.</p>

    <form class="mt-3 p-3 border rounded bg-light" style="max-width: 600px;">
        <div class="mb-3">
            <label class="form-label">Nama</label>
            <input type="text" class="form-control" placeholder="Nama Anda">
        </div>

        <div class="mb-3">
            <label class="form-label">Email / Kontak</label>
            <input type="text" class="form-control" placeholder="Email / Nomor HP">
        </div>

        <div class="mb-3">
            <label class="form-label">Pesan</label>
            <textarea class="form-control" rows="4" placeholder="Tulis pesan Anda"></textarea>
        </div>

        <button class="btn btn-primary">Kirim Pesan</button>
    </form>
</div>
@endsection
