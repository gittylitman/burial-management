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
}
