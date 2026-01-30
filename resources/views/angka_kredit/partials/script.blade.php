<script>
document.addEventListener('DOMContentLoaded', function () {

    /* =====================================================
       ELEMENT UTAMA
    ====================================================== */
    const pakYa    = document.getElementById('pak_awal_ya');
    const pakTidak = document.getElementById('pak_awal_tidak');

    const pakAwalWrapper = document.getElementById('pak-awal-wrapper');
    const pakBerkalaForm = document.getElementById('form-pak_berkala');
    const submitPakBerkala = document.getElementById('submit-pak-berkala');
    const jenisPakAwal   = document.getElementById('jenis_pak_awal');
    const rentangBulan   = document.getElementById('rentangBulan');

    const subPerpindahan = document.getElementById('sub-perpindahan');

    /* =====================================================
       ELEMENT PERPINDAHAN
    ====================================================== */
    const radioJenisPerpindahan = document.querySelectorAll('input[name="jenis_perpindahan"]');
    const radioKesesuaian       = document.querySelectorAll('input[name="kesesuaian"]');

    const formJfKeJf        = document.getElementById('form-jf-ke-jf');
    const kesesuaianWrapper = document.getElementById('kesesuaian-wrapper');
    const formJaJfSesuai    = document.getElementById('form-ja-jf-sesuai');
    const formJaJfTidak     = document.getElementById('form-ja-jf-tidak-sesuai');

    /* =====================================================
       RESET TOTAL PAK AWAL
    ====================================================== */
    function resetPakAwal() {

        pakAwalWrapper?.classList.add('d-none');
        pakBerkalaForm?.classList.add('d-none');
        submitPakBerkala?.classList.add('d-none');
        subPerpindahan?.classList.add('d-none');

        if (jenisPakAwal) jenisPakAwal.value = '';

        document.querySelectorAll(
            '#form-pak_awal_pengangkatan, \
             #form-pak_awal_perpindahan, \
             #form-pak_awal_penyesuaian, \
             #form-pak_awal_promosi'
        ).forEach(el => el.classList.add('d-none'));

        document.querySelectorAll('#pak-awal-wrapper .list-group-item')
            .forEach(b => b.classList.remove('active'));

        resetPerpindahan();
    }


    /* =====================================================
       RESET KHUSUS PERPINDAHAN
    ====================================================== */
    function resetPerpindahan() {
        formJfKeJf?.classList.add('d-none');
        kesesuaianWrapper?.classList.add('d-none');
        formJaJfSesuai?.classList.add('d-none');
        formJaJfTidak?.classList.add('d-none');

        radioJenisPerpindahan.forEach(r => r.checked = false);
        radioKesesuaian.forEach(r => r.checked = false);
    }

    /* =====================================================
       PAK AWAL = YA / TIDAK
    ====================================================== */
    pakYa?.addEventListener('change', function () {
        if (this.checked) {
            resetPakAwal();
            pakAwalWrapper?.classList.remove('d-none');
        }
    });

    pakTidak?.addEventListener('change', function () {
        if (this.checked) {
            resetPakAwal();
            pakBerkalaForm?.classList.remove('d-none');
            submitPakBerkala?.classList.remove('d-none');
        }
    });

    /* =====================================================
       BUTTON JENIS PAK AWAL
    ====================================================== */
    document.querySelectorAll('#pak-awal-wrapper [data-jenis]')
        .forEach(btn => {
            btn.addEventListener('click', function () {

                const jenis = this.dataset.jenis;
                jenisPakAwal.value = jenis;

                document.querySelectorAll(
                    '#form-pak_awal_pengangkatan, \
                     #form-pak_awal_perpindahan, \
                     #form-pak_awal_penyesuaian, \
                     #form-pak_awal_promosi'
                ).forEach(el => el.classList.add('d-none'));

                subPerpindahan?.classList.add('d-none');

                const target = document.getElementById('form-' + jenis);
                if (target) target.classList.remove('d-none');

                document.querySelectorAll('#pak-awal-wrapper .list-group-item')
                    .forEach(b => b.classList.remove('active'));

                this.classList.add('active');

                // KHUSUS PERPINDAHAN
                if (jenis === 'pak_awal_perpindahan') {
                    subPerpindahan?.classList.remove('d-none');
                    resetPerpindahan();
                }
            });
        });

    /* =====================================================
       STEP 1 : JENIS PERPINDAHAN
    ====================================================== */
    function handleJenisPerpindahan() {

        formJfKeJf.classList.add('d-none');
        kesesuaianWrapper.classList.add('d-none');
        formJaJfSesuai.classList.add('d-none');
        formJaJfTidak.classList.add('d-none');

        const checked = document.querySelector(
            'input[name="jenis_perpindahan"]:checked'
        );

        if (!checked) return;

        if (checked.value === 'jf_ke_jf') {
            formJfKeJf.classList.remove('d-none');
        }

        if (checked.value === 'ja_ke_jf') {
            kesesuaianWrapper.classList.remove('d-none');
        }
    }

    radioJenisPerpindahan.forEach(radio => {
        radio.addEventListener('change', handleJenisPerpindahan);
    });
document.addEventListener('DOMContentLoaded', function () {
    if (!document.getElementById('sub_aksi_perpindahan').value) {
        document.getElementById('sub_aksi_perpindahan').value = 'jf_ke_jf';
    }
});

    /* =====================================================
       STEP 2 : KESESUAIAN JA → JF
    ====================================================== */
    radioKesesuaian.forEach(radio => {
        radio.addEventListener('change', function () {

            formJaJfSesuai.classList.add('d-none');
            formJaJfTidak.classList.add('d-none');

            if (this.value === 'sesuai') {
                formJaJfSesuai.classList.remove('d-none');
            }

            if (this.value === 'tidak_sesuai') {
                formJaJfTidak.classList.remove('d-none');
            }
        });
    });

    /* =====================================================
       AUTO HITUNG AK (TIDAK SESUAI)
    ====================================================== */
    const akMap = {
        'III/c|Ahli Pertama': 100,
        'III/d|Ahli Pertama': 100,
        'IV/a|Ahli Pertama': 100,
        'III/b|Ahli Muda': 50,
        'IV/a|Ahli Muda': 200,
        'IV/b|Ahli Muda': 200,
        'III/d|Ahli Madya': 100,
    };

    const golongan = document.getElementById('golongan');
    const jenjang  = document.getElementById('jenjang_tidak_sesuai');
    const akField  = document.getElementById('ak_otomatis');

    function hitungAKOtomatis() {
        const key = `${golongan?.value}|${jenjang?.value}`;
        akField.value = akMap[key] ?? '';
    }

    golongan?.addEventListener('change', hitungAKOtomatis);
    jenjang?.addEventListener('change', hitungAKOtomatis);

    /* =====================================================
       PAK BERKALA → PROPORSIONAL
    ====================================================== */
    document.querySelectorAll('input[name="jenis_penilaian"]')
        .forEach(el => {
            el.addEventListener('change', function () {
                if (this.value === 'proporsional') {
                    rentangBulan?.classList.remove('d-none');
                } else {
                    rentangBulan?.classList.add('d-none');
                }
            });
        });

    /* =====================================================
       INIT LOAD
    ====================================================== */
    resetPakAwal();

    /* =====================================================
       KLAIM PENDIDIKAN
    ====================================================== */
document.addEventListener('DOMContentLoaded', function () {
    const klaimYa = document.getElementById('klaim_ya');
    const klaimTidak = document.getElementById('klaim_tidak');
    const info = document.getElementById('info-klaim-pendidikan');

    klaimYa.addEventListener('change', () => {
        if (klaimYa.checked) info.classList.remove('d-none');
    });

    klaimTidak.addEventListener('change', () => {
        info.classList.add('d-none');
    });
});

});
</script>
