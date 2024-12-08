<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Representative extends Model
{
    public function client(): HasMany
    {
        return $this->hasMany(Client::class);
    }

    protected $fillable = [
        'name',
        'identity',
        'city',
        'phone',
        'email',
        'relation',
    ];
}
