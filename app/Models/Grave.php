<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Grave extends Model
{
    public function clients(): HasOne
    {
        return $this->hasOne(Client::class, 'grave_id');
    }

    protected $fillable = [
        'cemetery',
        'plot',
        'block',
        'city',
        'chevra_kadisha',
        'price',
    ];
}
