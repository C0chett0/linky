<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ConsumptionMaxPower extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'consumption',
        'time',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    protected function time(): Attribute
    {
        return Attribute::make(
            get: fn($value) => Carbon::createFromFormat('H:i:s', $value),
        );
    }
}
