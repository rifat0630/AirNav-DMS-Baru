<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use App\Models\Document;
use App\Models\ActivityLog;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email' ,
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi ke tabel documents.
     */
    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    /**
     * Relasi ke tabel activity_logs.
     */
    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }
    public function isAdmin()
{
    return $this->role === 'admin';
}

public function isTeknisi()
{
    return $this->role === 'teknisi';
}

public function isPegawai()
{
    return $this->role === 'pegawai';
}
}