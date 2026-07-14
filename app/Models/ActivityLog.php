<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    /**
     * Kolom yang boleh diisi.
     */
    protected $fillable = [
        'user_id',
        'document_id',
        'activity',
        'description',
    ];

    /**
     * Relasi ke User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Document.
     */
    public function document()
    {
        return $this->belongsTo(Document::class);
    }
}