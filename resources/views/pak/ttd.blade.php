<h4>Penetapan Angka Kredit</h4>
<p>Silakan membaca dokumen sebelum tanda tangan.</p>

<form method="POST" action="{{ route('pak.ttd.ppk', $ak->id) }}">
@csrf
<button class="btn btn-primary">
    Tanda Tangan Digital
</button>
</form>

<form method="POST" action="{{ route('pak.tolak.ppk', $ak->id) }}">
@csrf
<textarea name="catatan" class="form-control" required
    placeholder="Alasan penolakan"></textarea>
<button class="btn btn-danger mt-2">
    Tolak / Batalkan
</button>
</form>
