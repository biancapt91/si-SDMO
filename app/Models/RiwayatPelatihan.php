<?php

protected $fillable = [
    'pegawai_id', 'nama_pelatihan', 'penyelenggara', 'tanggal_pelaksanaan'
];

public function pegawai()
{
    return $this->belongsTo(Pegawai::class);
}