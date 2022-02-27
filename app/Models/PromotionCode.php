<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromotionCode extends Model
{
    use HasFactory;

    public function promotion()
    {
        return $this->belongsTo('App\Models\Promotion');
    }
}
