<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Client extends Model
{
    public function grave(): BelongsTo
    {
        return $this->belongsTo(Grave::class);
    }
    
    public function representative(): BelongsTo
    {
        return $this->belongsTo(Representative::class);
    }

    protected $fillable = [
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
        // 'grave.cemetery',
        // 'grave.plot',
        // 'grave.block',
        // 'grave.city',
        // 'grave.chevra_kadisha',
        // 'grave.price',
        // 'name',
        // 'identity',
        // 'city',
        // 'phone',
        // 'email',
        // 'relation',
    ];
}
