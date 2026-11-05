<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManagerTeknikAssignment extends Model
{
    use HasFactory;

    protected $table = 'manager_teknik_assignments';

    protected $fillable = [
        'manager_teknik_id',
        'technician_id',
        'title',
        'instruction',
        'attachment_path',
        'attachment_name',
        'target_date',
        'status',
        'technician_note',
    ];

    public function managerTeknik()
    {
        return $this->belongsTo(User::class, 'manager_teknik_id');
    }

    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }
}