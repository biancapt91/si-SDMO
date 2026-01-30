<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenetapanAk extends Model
{
    use HasFactory;
    protected $fillable = ['pegawai_id','dokumen_no','tanggal','total_ak','jenis_penetapan','file_path'];
    public function pegawai() { return $this->belongsTo(Pegawai::class,'pegawai_id'); }
}
