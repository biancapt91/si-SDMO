@extends('layouts.app')

@section('content')
<div class="container py-4">
  <a href="{{ route('career-map') }}" class="btn btn-link">&larr; Kembali</a>
  <h2>Career Map: {{ $pegawai->nama }}</h2>

  <div class="row">
    <div class="col-md-4">
      <div class="card p-3">
        <h5>Profil</h5>
        <p><strong>NIP:</strong> {{ $pegawai->nip }}</p>
        <p><strong>Jabatan:</strong> {{ $pegawai->jabatan_saat_ini ?? '—' }}</p>
        <p><strong>Jenjang:</strong> {{ \App\Services\AngkaKreditService::extractJenjangFromPegawai($pegawai) ?? '-' }}</p>
        <p><strong>Total AK:</strong> {{ number_format($ak->total_ak ?? 0,2) }}</p>
        <p><strong>Estimasi tercapai:</strong> {{ $prediksi }}</p>
      </div>
    </div>

    <div class="col-md-8">
      <div class="card p-3 mb-3">
        <h5>Kebutuhan</h5>
        <table class="table table-sm">
          <thead><tr><th>Target</th><th>Target AK</th><th>Kekurangan</th></tr></thead>
          <tbody>
            <tr><td>Naik Pangkat</td><td>{{ $pangkatNeed['target'] ?? '-' }}</td><td>{{ $pangkatNeed['needed'] ?? '-' }}</td></tr>
            <tr><td>Naik Jenjang</td><td>{{ $jenjangNeed['target'] ?? '-' }}</td><td>{{ $jenjangNeed['needed'] ?? '-' }}</td></tr>
          </tbody>
        </table>
      </div>

      <div class="card p-3">
        <h5>Eligibility</h5>
        <ul>
          <li>Masa pangkat (tahun): {{ $elig['masa_pangkat_years'] }}</li>
          <li>Predikat ok: {{ $elig['predikat_ok'] ? 'Ya' : 'Tidak' }}</li>
          <li>Uji kompetensi lulus: {{ $elig['uji_lulus'] ? 'Ya' : 'Tidak' }}</li>
          <li>AK pangkat ok: {{ $elig['ak_pangkat_ok'] ? 'Ya' : 'Tidak' }}</li>
        </ul>
      </div>
    </div>
  </div>

  <div class="mt-4 card p-3">
    <h5>Riwayat AK</h5>
    <table class="table table-sm">
      <thead><tr><th>Tgl</th><th>Sumber</th><th>Nilai</th><th>Status</th></tr></thead>
      <tbody>
        @foreach($pegawai->angkaKreditEntries()->orderBy('tanggal','desc')->get() as $e)
        <tr><td>{{ $e->tanggal }}</td><td>{{ $e->sumber }}</td><td>{{ number_format($e->nilai,2) }}</td><td>{{ $e->status }}</td></tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection
