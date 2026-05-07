<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'first_name', 'last_name', 'role',
        'username', 'password',
        'profile_photo', 'is_active', 'last_seen',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'last_seen' => 'datetime',
        'is_active' => 'boolean',
    ];

    // ✅ هذا هو الحل — نحذف getAuthIdentifierName ونخلي Laravel يستعمل id
    public function getAuthPassword()
    {
        return $this->password;
    }

    // login بـ username
    public function findForPassport($username)
    {
        return $this->where('username', $username)->first();
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function isOnline()
    {
        return $this->last_seen && $this->last_seen->diffInMinutes(now()) < 5;
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'receptionist_id');
    }

    public function results()
    {
        return $this->hasMany(Result::class, 'biologist_id');
    }

    public function validatedResults()
    {
        return $this->hasMany(Result::class, 'doctor_id');
    }
}