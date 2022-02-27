<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable
        = [
            'name',
            'description',
            'discount',
            'start_date',
            'end_date',
            'min_transaction_count',
            'last_transaction_days',
            'min_transaction_total',
            'max_redemption_per_user_count',
        ];


}
