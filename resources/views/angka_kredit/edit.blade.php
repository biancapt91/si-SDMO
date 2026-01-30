@php
    $isEdit = true;
@endphp

@extends('layouts.app')

@section('content')
<div class="container py-4">

    <h4 class="fw-bold mb-3">Edit Perhitungan Angka Kredit</h4>

        @method('PUT')

        <!-- ================= PAK AWAL ================= -->
        <div class="mb-3">
    <label class="form-label fw-semibold">PAK Awal</label><br>

    <div class="form-check form-check-inline">
        <input class="form-check-input"
               type="radio"
               name="pak_awal"
               id="pak_awal_ya"
               value="ya">
        <label class="form-check-label" for="pak_awal_ya">Ya</label>
    </div>

	<div class="form-check form-check-inline">
                <input class="form-check-input"
                       type="radio"
                       name="pak_awal"
                       id="pak_awal_tidak"
                       value="tidak">
                <label class="form-check-label" for="pak_awal_tidak">Tidak</label>
            </div>
        </div>

	<div id="pak-awal-wrapper" class="card mb-4 d-none">
	    <div class="card-body">
                <h5 class="fw-bold mb-3">Jenis PAK Awal</h5>

    		<div class="list-group">
        	<button type="button" class="list-group-item list-group-item-action"
                data-jenis="pak_awal_pengangkatan">
            	1. Pengangkatan Pertama
        	</button>
	
<style>

/* Radio button: saat dipilih */
.form-check-input:checked {
    background-color: #000080;
    border-color: #000;
}
</style>
		<div class="list-group-item list-group-item-action"
     			data-jenis="pak_awal_perpindahan"
     			id="btn-perpindahan">

    			<div class="pak-title fw">
        		2. Perpindahan dari Jabatan Lain
    			</div>

    			{{-- SUB PILIHAN (AWALNYA HIDDEN) --}}
    			<div id="sub-perpindahan"
         			class="mt-3 ps-3 d-none"
				onclick="event.stopPropagation()">

        		<div class="form-check">
            		<input class="form-check-input"
                   	type="radio"
                   	name="jenis_perpindahan"
                   	value="jf_ke_jf"
			onclick="event.stopPropagation()">
            		<label class="form-check-label">
                	Jabatan Fungsional ke Jabatan Fungsional Lainnya
            		</label>
        		</div>

        		<div class="form-check">
            		<input class="form-check-input"
                   	type="radio"
                   	name="jenis_perpindahan"
                   	value="ja_ke_jf"
			onclick="event.stopPropagation()">
            		<label class="form-check-label">
                	Jabatan Administrasi ke Jabatan Fungsional
            		</label>
        	     </div>
    		   </div>
	       	</div>

        	<button type="button" class="list-group-item list-group-item-action"
                data-jenis="pak_awal_penyesuaian">
            	3. Penyesuaian / Penyetaraan
        	</button>

        	<button type="button" class="list-group-item list-group-item-action"
                data-jenis="pak_awal_promosi">
            	4. Promosi
        	</button>
    	</div>
	
    	<input type="hidden" name="jenis_pak_awal" id="jenis_pak_awal">
    	<input type="hidden" name="bulan_awal" id="rentangbulan">
	<input type="hidden" name="sub_aksi_perpindahan" id="sub_aksi_perpindahan">
	<input type="hidden" name="pak_awal" id="pak_awal_hidden">
	</div>
      </div>

        <!-- ================= FORM DINAMIS ================= -->
   	{{-- Form PAK Awal --}}
	@include('angka_kredit.partials.pak_awal_pengangkatan')
	@include('angka_kredit.partials.pak_awal_perpindahan')
	@include('angka_kredit.partials.pak_awal_penyesuaian')
	@include('angka_kredit.partials.pak_awal_promosi')

	 {{-- PAK Tidak Awal --}}
	@include('angka_kredit.partials.pak_berkala')

	{{-- SCRIPT (WAJIB, JANGAN DIHAPUS) --}}
        @include('angka_kredit.partials.script')

	<div class="mt-4">
	<button type="submit" class="btn btn-primary">
    	Simpan Perubahan
	</button>
	<a href="{{ route('ak.index') }}" class="btn btn-secondary">Batal</a>

	</div>
    
</div>
@endsection

<script>
window.addEventListener('load', function () {

    setTimeout(function () {

        // 1️⃣ Trigger PAK Awal
        @if ($ak->pak_awal === 'ya')
            document.getElementById('pak_awal_ya')?.dispatchEvent(new Event('change'));
            document.getElementById('pak_awal_ya')?.click();
        @else
            document.getElementById('pak_awal_tidak')?.dispatchEvent(new Event('change'));
            document.getElementById('pak_awal_tidak')?.click();
        @endif

        // 2️⃣ Trigger jenis PAK Awal
        @if ($ak->pak_awal === 'ya')
            @if ($ak->jenis_pak_awal === 'pengangkatan')
                document.getElementById('btn-pengangkatan')?.click();
            @elseif ($ak->jenis_pak_awal === 'perpindahan')
                document.getElementById('btn-perpindahan')?.click();
            @elseif ($ak->jenis_pak_awal === 'penyesuaian')
                document.getElementById('btn-penyesuaian')?.click();
            @elseif ($ak->jenis_pak_awal === 'promosi')
                document.getElementById('btn-promosi')?.click();
            @endif
        @endif

    }, 300); // ⏱️ delay kecil tapi krusial

});
</script>
