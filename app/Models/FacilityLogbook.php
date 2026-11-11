<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacilityLogbook extends Model
{
    use HasFactory;

    protected $table = 'facility_logbooks';

    protected $fillable = [
        'technician_id',
        'technicians',
        'log_datetime',
        'tanggal_kejadian',
        'tanggal_dibuat',
        'tanggal_waktu_selesai',
        'action_notes',
        'user_id',
        'signature_type',
        'signature_file',
        'qr_token',
        'signature_status',
        'status',
        'paraf_teknisi',
        'paraf_teknisi_name',
        'paraf_teknisi_at',
        'paraf_mantek',
        'paraf_mantek_name',
        'paraf_mantek_at',
        'signed_at',
        'signed_by',
    ];

    public function technician()
    {
        return $this->belongsTo(Technician::class, 'technician_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}