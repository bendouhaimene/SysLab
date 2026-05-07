<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Archive extends Model
{
    protected $fillable = [
        'model_type', 'model_id', 'action',
        'performed_by', 'data_snapshot',
    ];

    protected $casts = [
        'data_snapshot' => 'array',
    ];

    public function performer()
    {
        return $this->belongsTo(User::class, 'performed_by');
    }
}