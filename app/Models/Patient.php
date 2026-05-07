<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Patient extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'national_id', 'first_name', 'last_name',
        'date_of_birth', 'gender', 'phone',
        'username', 'password',
    ];

    protected $hidden = ['password'];

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}