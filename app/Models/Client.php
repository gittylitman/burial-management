<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Client extends Model
{
    public function grave(): HasOne
    {
        return $this->hasOne(Grave::class);
    }
    
    public function representative(): HasOne
    {
        return $this->hasOne(Representative::class);
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
    ];
}
