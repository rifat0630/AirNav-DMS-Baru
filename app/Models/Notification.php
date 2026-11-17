<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';

    protected $fillable = [
        'user_id',
        'title',
        'message',
        'reference',
        'url',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];
}