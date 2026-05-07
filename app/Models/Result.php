<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    protected $fillable = [
        'invoice_id', 'test_id', 'biologist_id',
        'value', 'status', 'doctor_id',
        'doctor_note', 'submitted_at', 'validated_at',
    ];

    protected $casts = [
        'submitted_at'  => 'datetime',
        'validated_at'  => 'datetime',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function test()
    {
        return $this->belongsTo(Test::class);
    }

    public function biologist()
    {
        return $this->belongsTo(User::class, 'biologist_id');
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    // هل القيمة خارج النطاق الطبيعي
    public function getStatusLabelAttribute()
    {
        if (!$this->test->normal_min || !$this->test->normal_max) return 'normal';
        if ((float)$this->value < (float)$this->test->normal_min) return 'low';
        if ((float)$this->value > (float)$this->test->normal_max) return 'high';
        return 'normal';
    }
}