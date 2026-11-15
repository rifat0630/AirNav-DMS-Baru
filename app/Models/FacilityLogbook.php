<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacilityLogbook extends Model
{
    use HasFactory;

    protected $table = 'logbooks';

    protected $fillable = [
        'user_id',
        'fasilitas_alat',
        'shift',
        'uraian_pekerjaan',
        'tanggal_terjadi',
        'tanggal_dibuat',
        'waktu_selesai',
        'paraf_teknisi',
        'paraf_teknisi_at',
        'mantek_id',
        'catatan_mantek',
        'paraf_mantek',
        'paraf_mantek_at',
        'status',
    ];

    protected $casts = [
        'tanggal_terjadi'  => 'date',
        'tanggal_dibuat'   => 'date',
        'waktu_selesai'    => 'datetime',
        'paraf_teknisi_at' => 'datetime',
        'paraf_mantek_at'  => 'datetime',
    ];

    public function teknisi()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function mantek()
    {
        return $this->belongsTo(User::class, 'mantek_id');
    }
}