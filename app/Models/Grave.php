<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Grave extends Model
{
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
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
