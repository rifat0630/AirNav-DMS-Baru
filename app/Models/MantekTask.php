<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MantekTask extends Model
{
    use HasFactory;

    protected $table = 'mantek_tasks';

    protected $fillable = [
        'title',
        'assigned_to',
        'target_date',
        'instruction',
        'attachment',
        'status_terbit',
        'status_pengerjaan',
    ];

    public function technician()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}