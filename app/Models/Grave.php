<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Grave extends Model
{
    public function client(): HasOne
    {
        return $this->hasOne(Client::class);
    }

    protected $fillable = [
        'cemetery',
        'burial_type',
        'plot',
        'block',
        'city',
        'chevra_kadisha',
        'price',
    ];
}
