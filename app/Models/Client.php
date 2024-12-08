<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Client extends Model
{
    public function grave(): BelongsTo
    {
        return $this->belongsTo(Grave::class, 'grave_id');
    }
    
    public function representative(): BelongsTo
    {
        return $this->belongsTo(Representative::class, 'representative_id');
    }

    protected $fillable = [
        'grave_id',
        'representative_id',
        'identity',
        'name',
        'city',
        'nation',
        'religion',
        'phone',
        'death_date',
        'burial_city',
        'burial_type',
        'cemetery',
    ];
}
