<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AngkaKreditEntry extends Model
{
    use HasFactory;
    protected $fillable = ['pegawai_id','sumber','deskripsi','nilai','tanggal','status'];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class,'pegawai_id');
    }
}
