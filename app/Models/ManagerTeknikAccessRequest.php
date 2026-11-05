<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManagerTeknikAccessRequest extends Model
{
    use HasFactory;

    protected $table = 'manager_teknik_access_requests';

    protected $fillable = [
        'user_id',
        'reason',
        'status',
        'approved_by',
        'approved_at',
        'expires_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}