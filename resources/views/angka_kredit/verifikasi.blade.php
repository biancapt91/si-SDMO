@extends('layouts.app')

@section('sidebar')
@include('career-map.sidebar')
@endsection

@section('content')
<h4>Verifikasi Angka Kredit</h4>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Nama Pegawai</th>
            <th>Periode</th>
            <th>AK Total</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $item)
        <tr>
            <td>{{ $item->pegawai->nama }}</td>
            <td>{{ $item->periode }}</td>
            <td>{{ $item->ak_total }}</td>
            <td>
                <a href="{{ route('pak.show', $item->id) }}"
                   class="btn btn-sm btn-info">
                    Lihat PAK
                </a>

                <form action="{{ route('ak.verifikasi', $item->id) }}"
                      method="POST"
                      class="d-inline">
                    @csrf
                    <button class="btn btn-sm btn-success">
                        Verifikasi
                    </button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
