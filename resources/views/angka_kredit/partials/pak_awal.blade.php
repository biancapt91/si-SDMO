@php
    /*
    |--------------------------------------------------------------------------
    | MAPPING DATA EDIT
    |--------------------------------------------------------------------------
    | pak_awal di DB : 1 / 0
    | jenis_penilaian di DB :
    |   - PAK_AWAL_PENGANGKATAN
    |   - PAK_AWAL_PERPINDAHAN
    |   - PAK_AWAL_PENYESUAIAN
    |   - PAK_AWAL_PROMOSI
    */

    // ===============================
    // 1. PAK AWAL (YA / TIDAK)
    // ===============================
    $pakAwalValue = old(
        'pak_awal',
        isset($ak)
            ? ($ak->pak_awal == 1 ? 'ya' : 'tidak')
            : null
    );

    // ===============================
    // 2. JENIS PAK AWAL (MAPPING)
    // ===============================
    $mapJenisPak = [
        'PAK_AWAL_PENGANGKATAN' => 'pak_awal_pengangkatan',
        'PAK_AWAL_PERPINDAHAN'  => 'pak_awal_perpindahan',
        'PAK_AWAL_PENYESUAIAN'  => 'pak_awal_penyesuaian',
        'PAK_AWAL_PROMOSI'      => 'pak_awal_promosi',
    ];

    $selectedJenisPak = old(
        'jenis_pak_awal',
        isset($ak) ? ($mapJenisPak[$ak->jenis_penilaian] ?? null) : null
    );
@endphp


{{-- ========================================================= --}}
{{-- PAK AWAL : YA / TIDAK --}}
{{-- ========================================================= --}}
<div class="card mb-4">
    <div class="card-body">

        <h5 class="fw-bold mb-3">PAK Awal</h5>

        <div class="form-check form-check-inline">
            <input class="form-check-input"
                   type="radio"
                   name="pak_awal"
                   id="pak_awal_ya"
                   value="ya"
                   {{ $pakAwalValue === 'ya' ? 'checked' : '' }}>
            <label class="form-check-label" for="pak_awal_ya">
                Ya
            </label>
        </div>

        <div class="form-check form-check-inline">
            <input class="form-check-input"
                   type="radio"
                   name="pak_awal"
                   id="pak_awal_tidak"
                   value="tidak"
                   {{ $pakAwalValue === 'tidak' ? 'checked' : '' }}>
            <label class="form-check-label" for="pak_awal_tidak">
                Tidak
            </label>
        </div>

    </div>
</div>


{{-- ========================================================= --}}
{{-- JENIS PAK AWAL --}}
{{-- ========================================================= --}}
<div id="pak-awal-wrapper"
     class="card mb-4 {{ $pakAwalValue === 'ya' ? '' : 'd-none' }}">
    <div class="card-body">

        <h5 class="fw-bold mb-3">Jenis PAK Awal</h5>

        @php
            $jenisPak = [
                'pak_awal_pengangkatan' => 'Pengangkatan Pertama',
                'pak_awal_perpindahan'  => 'Perpindahan dari Jabatan Lain',
                'pak_awal_penyesuaian'  => 'Penyesuaian / Penyetaraan',
                'pak_awal_promosi'      => 'Promosi',
            ];
        @endphp

        @foreach ($jenisPak as $key => $label)
            <div class="form-check">
                <input class="form-check-input"
                       type="radio"
                       name="jenis_pak_awal"
                       id="jenis_{{ $key }}"
                       value="{{ $key }}"
                       {{ $selectedJenisPak === $key ? 'checked' : '' }}>
                <label class="form-check-label" for="jenis_{{ $key }}">
                    {{ $label }}
                </label>
            </div>
        @endforeach

    </div>
</div>
