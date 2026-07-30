<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacilityLogbook extends Model
{
    use HasFactory;

    protected $fillable = [
        'log_datetime',
        'action_notes',
        'technicians',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}