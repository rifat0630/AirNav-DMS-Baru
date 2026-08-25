<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'document_number',
        'title',
        'category_id',
        'tanggal_berlaku',
        'status',
        'file_name',
        'file_path',
        'google_drive_id',
        'google_file_name',
        'file_type',
        'file_size',
        'user_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function activityLogs() 
    {
        return $this->hasMany(activityLog::class);
    }
}