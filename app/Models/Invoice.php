<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_number', 'patient_id', 'receptionist_id',
        'total_amount', 'qr_code_path', 'status',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function receptionist()
    {
        return $this->belongsTo(User::class, 'receptionist_id');
    }

    public function tests()
    {
        return $this->belongsToMany(Test::class, 'invoice_tests')
                    ->withPivot('price_at_time')
                    ->withTimestamps();
    }

    public function results()
    {
        return $this->hasMany(Result::class);
    }

    // هل كل النتائج validated
    public function allValidated()
    {
        return $this->results()->count() > 0
            && $this->results()->where('status', '!=', 'validated')->count() === 0;
    }
}