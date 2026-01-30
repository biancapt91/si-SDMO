<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AkKumulatif extends Model
{
    use HasFactory;
    protected $table = 'ak_kumulatif';
    public $incrementing = false;
    public $timestamps = false;
    protected $primaryKey = 'pegawai_id';
    protected $fillable = ['pegawai_id','total_ak','ak_utama','ak_penunjang','last_updated'];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class,'pegawai_id');
    }
}
