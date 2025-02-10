<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Building extends Model
{
    use HasFactory;

    protected $fillable = [
        'address',
        'latitude',
        'longitude'
    ];

    public function organization(): HasOne
    {
        return $this->hasOne(Organization::class);
    }
}
