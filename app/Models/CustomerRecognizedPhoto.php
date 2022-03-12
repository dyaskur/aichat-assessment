<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerRecognizedPhoto extends Model
{

    protected $fillable
        = [
            'customer_id',
            'photo_path',
        ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo('App\Models\Customer');
    }

}
