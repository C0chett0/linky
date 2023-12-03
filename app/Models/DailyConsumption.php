<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyConsumption extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'consumption',
    ];

    protected $casts = [
        'date' => 'date',
    ];
}
