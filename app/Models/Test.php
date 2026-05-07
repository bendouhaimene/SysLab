<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    protected $fillable = [
        'name', 'category', 'price',
        'unit', 'normal_min', 'normal_max',
        'normal_label', 'is_archived',
    ];

    protected $casts = [
        'is_archived' => 'boolean',
        'price'       => 'decimal:2',
        'normal_min'  => 'decimal:2',
        'normal_max'  => 'decimal:2',
    ];

    public function invoices()
    {
        return $this->belongsToMany(Invoice::class, 'invoice_tests')
                    ->withPivot('price_at_time')
                    ->withTimestamps();
    }

    public function results()
    {
        return $this->hasMany(Result::class);
    }

    // scope للتحاليل الغير مؤرشفة
    public function scopeActive($query)
    {
        return $query->where('is_archived', false);
    }
}