<div class="container py-4">

    @if ($errors->any())
        <div class="alert alert-danger">
            {{ implode(', ', $errors->all()) }}
        </div>
    @endif

<form method="GET" action="{{ route('ak.index') }}" class="mb-4">
    <div class="card border-0 shadow-sm">
        <div class="card-header border-0" style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);">
            <div class="d-flex align-items-center">
                <div class="rounded-circle p-2 me-3" style="background: rgba(255,255,255,0.2);">
                    <i class="bi bi-people-fill text-white" style="font-size: 1.5rem;"></i>
                </div>
                <div>
                    <h5 class="text-white fw-bold mb-0">Pilih Pegawai</h5>
                    <small class="text-white opacity-75">Pilih pegawai untuk menghitung angka kredit</small>
                </div>
            </div>
        </div>
        <div class="card-body p-4">
            <div style="position: relative;">
                <input type="hidden" name="pegawai_id" id="pegawai_id_input" value="{{ request('pegawai_id') }}">
                <input type="text" 
                       id="pegawai_search" 
                       class="form-control form-control-lg" 
                       placeholder="Ketik minimal 2 karakter untuk mencari pegawai..."
                       value="{{ $pegawaiAktif ? $pegawaiAktif->nama . ' — ' . $pegawaiAktif->jabatan_saat_ini : '' }}"
                       autocomplete="off">
                <div id="pegawai_suggestions" class="card shadow-lg mt-1" style="position: absolute; z-index: 9999; display: none; max-height: 350px; overflow-y: auto; width: 100%; background: white;"></div>
            </div>
        </div>
    </div>
</form>

<style>
#pegawai_suggestions {
    border: 1px solid #d1d5db;
}
.suggestion-item {
    padding: 0.875rem 1.25rem;
    cursor: pointer;
    border-bottom: 1px solid #f3f4f6;
    transition: all 0.15s ease;
    background: white;
}
.suggestion-item:hover {
    background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
}
.suggestion-item:last-child {
    border-bottom: none;
}
.suggestion-item.active {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: white;
}
.suggestion-item.active small {
    color: rgba(255, 255, 255, 0.9) !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('pegawai_search');
    const hiddenInput = document.getElementById('pegawai_id_input');
    const suggestBox = document.getElementById('pegawai_suggestions');
    let suggestions = [];
    let selectedIndex = -1;
    let fetchTimer = null;

    searchInput.addEventListener('focus', function() {
        // Jika sudah ada suggestions dan input kosong, tampilkan semua pegawai
        if (this.value.trim().length >= 2) {
            triggerSearch(this.value.trim());
        }
    });

    searchInput.addEventListener('input', function() {
        const query = this.value.trim();
        
        if (fetchTimer) clearTimeout(fetchTimer);
        
        if (query.length < 2) {
            suggestBox.style.display = 'none';
            suggestBox.innerHTML = '';
            return;
        }

        fetchTimer = setTimeout(() => {
            triggerSearch(query);
        }, 300);
    });

    function triggerSearch(query) {
        console.log('Searching for:', query); // Debug log
        fetch('{{ route("pegawai.suggest") }}?q=' + encodeURIComponent(query))
            .then(r => {
                console.log('Response status:', r.status); // Debug log
                return r.json();
            })
            .then(data => {
                console.log('Received data:', data); // Debug log
                suggestions = data;
                if (!Array.isArray(suggestions) || suggestions.length === 0) {
                    suggestBox.innerHTML = '<div class="p-3 text-center text-muted"><small>Tidak ada hasil ditemukan</small></div>';
                    suggestBox.style.display = 'block';
                    return;
                }
                
                renderSuggestions();
                suggestBox.style.display = 'block';
                selectedIndex = -1;
            })
            .catch(err => {
                console.error('Error fetching suggestions:', err);
                suggestBox.innerHTML = '<div class="p-3 text-center text-danger"><small>Terjadi kesalahan saat mencari data</small></div>';
                suggestBox.style.display = 'block';
            });
    }

    function renderSuggestions() {
        suggestBox.innerHTML = '';
        suggestions.forEach((item, idx) => {
            const div = document.createElement('div');
            div.className = 'suggestion-item';
            div.innerHTML = `
                <div class="fw-semibold">${item.nama}</div>
                <small class="text-muted">${item.jabatan_saat_ini || '-'}</small>
            `;
            div.addEventListener('click', () => selectSuggestion(idx));
            suggestBox.appendChild(div);
        });
    }

    function selectSuggestion(idx) {
        if (idx < 0 || idx >= suggestions.length) return;
        const selected = suggestions[idx];
        searchInput.value = selected.nama + ' — ' + (selected.jabatan_saat_ini || '-');
        hiddenInput.value = selected.id;
        suggestBox.style.display = 'none';
        
        // Submit form untuk load data pegawai
        searchInput.closest('form').submit();
    }

    searchInput.addEventListener('keydown', function(e) {
        if (suggestBox.style.display === 'none') return;
        
        const items = suggestBox.querySelectorAll('.suggestion-item');
        
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            selectedIndex = Math.min(selectedIndex + 1, suggestions.length - 1);
            updateHighlight(items);
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            selectedIndex = Math.max(selectedIndex - 1, -1);
            updateHighlight(items);
        } else if (e.key === 'Enter') {
            e.preventDefault();
            if (selectedIndex >= 0) {
                selectSuggestion(selectedIndex);
            }
        } else if (e.key === 'Escape') {
            suggestBox.style.display = 'none';
        }
    });

    function updateHighlight(items) {
        items.forEach((item, idx) => {
            if (idx === selectedIndex) {
                item.classList.add('active');
                item.scrollIntoView({ block: 'nearest' });
            } else {
                item.classList.remove('active');
            }
        });
    }

    // Close suggestions when clicking outside
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !suggestBox.contains(e.target)) {
            suggestBox.style.display = 'none';
        }
    });
});
</script>

@if($pegawaiAktif)
    <form id="form-hitung-ak-pegawai" method="POST" action="{{ route('ak.store') }}">
    @csrf
    
    <!-- Input hidden untuk pegawai_id yang dipilih -->
    <input type="hidden" name="pegawai_id" value="{{ $pegawaiAktif->id }}">



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

	<div id="pak-awal-wrapper" class="card border-0 shadow-sm mb-4 d-none">
	    <div class="card-header border-0" style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle p-2 me-3" style="background: rgba(255,255,255,0.2);">
                        <i class="bi bi-file-earmark-check-fill text-white" style="font-size: 1.5rem;"></i>
                    </div>
                    <div>
                        <h5 class="text-white fw-bold mb-0">Jenis PAK Awal</h5>
                        <small class="text-white opacity-75">Pilih jenis PAK Awal yang sesuai</small>
                    </div>
                </div>
            </div>
	    <div class="card-body p-4">

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

	{{-- Tombol Submit --}}
	<div id="submit-pak-berkala" class="mt-4 d-none">
	    <button type="submit" form="form-hitung-ak-pegawai" class="btn btn-lg px-5" style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); border: none; color: white; box-shadow: 0 4px 12px rgba(30, 58, 138, 0.3); transition: all 0.3s;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(30, 58, 138, 0.4)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(30, 58, 138, 0.3)';">
	        <i class="bi bi-save me-2"></i>Simpan Perhitungan
	    </button>
	    <a href="{{ route('ak.index') }}" class="btn btn-secondary btn-lg px-4">
	        <i class="bi bi-arrow-left me-2"></i>Kembali
	    </a>
	</div>

</form>

@else
    <div class="alert alert-info">
        <i class="bi bi-info-circle me-2"></i>Silakan pilih pegawai terlebih dahulu untuk melakukan perhitungan angka kredit.
    </div>
@endif
        
<hr>

<h4 class="fw-bold mt-5">Riwayat Perhitungan AK Pegawai</h4>

<table class="table table-sm">
    <thead>
        <tr>
            <th class="text-center" style="width:40px">No</th>
            <th class="text-center">Dibuat Pada</th>
            <th class="text-center">Nama</th>
            <th class="text-center">Jabatan</th>
            <th class="text-center">Periode</th>
            <th class="text-center">Jenis Penilaian</th>
            <th class="text-center">AK Total</th>
            <th class="text-center">Status</th>
            <th class="text-center">Aksi</th>
        </tr>
    </thead>
    
        @forelse($riwayatPegawai as $item)
        <tr>
            <td class="text-center">{{ $loop->iteration }}</td>
            <td class="text-center">{{ $item->created_at->format('d-m-Y H:i') }}</td>
            <td>
               <a href="{{ route('pegawai.show', $item->pegawai->id) }}">
    		{{ $item->pegawai->nama }}
		</a>
            </td>
            <td class="text-center">{{ $item->jabatan }}</td>
            <td class="text-center">{{ $item->periode }}</td>
            <td class="text-center">{{ $item->jenis_penilaian_label }}</td>
            <td class="text-center">{{ number_format($item->ak_total,2) }}</td>
            <td class="text-center">

    		@if ($item->status == 'DRAFT')
        	<span class="badge bg-secondary">Draft</span>

    		@elseif ($item->status == 'MENUNGGU_SDMO')
        	<span class="badge bg-warning text-dark">
            	Menunggu Verifikasi SDMO
        	</span>

    		@elseif ($item->status == 'DIVERIFIKASI_SDMO')
        	<span class="badge bg-info">Diverifikasi SDMO</span>

    		@elseif ($item->status == 'DISAHKAN_PPK')
        	<span class="badge bg-success">Disahkan</span>

    		@endif
	     </td>
	     <td class="text-center">

                <a href="{{ route('pak.show', $item->id) }}" class="btn btn-info btn-sm"
                    title="Lihat PAK">
        		<i class="bi bi-eye"></i>
                </a>

                @if ($item->status == 'DRAFT')

        		<!-- HAPUS -->
        		<form action="{{ route('ak.destroy', $item->id) }}"
              		method="POST"
              		class="d-inline"
              		onsubmit="return confirm('Yakin hapus draft ini?')">
            		@csrf
            		@method('DELETE')
            		<button type="submit" class="btn btn-danger btn-sm"
                	title="Hapus Draft">
                	<i class="bi bi-trash"></i>
            		</button>
        		</form>

        		<!-- KIRIM USULAN -->
        		<form action="{{ route('ak.kirim-usulan', $item->id) }}"
              		method="POST"
              		class="d-inline"
              		onsubmit="return confirm('Kirim usulan AK ke SDMO?')">
            		@csrf
            		@method('PUT')
            		<button class="btn btn-sm" style="background:#22c55e;border-color:#22c55e;color:#fff">
                	Kirim Usulan
            		</button>
        		</form>

    		@endif
             </td>
        </tr>
        @empty

        @endforelse
    
</table>
</div>

@push('scripts')
@include('angka_kredit.partials.script')
@endpush
