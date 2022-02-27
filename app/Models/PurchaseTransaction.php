<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseTransaction extends Model
{
    use HasFactory;

    protected $fillable
        = [
            'customer_id',
        ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function promotionCode()
    {
        return $this->belongsTo(PromotionCode::class);
    }

    public function scopeHasPromotionCode($query)
    {
        return $query->whereNotNull('promotion_code_id');
    }

    public function scopeHasPromotion($query, $promo_id = null)
    {
        return $query->whereHas('promotionCode.promotion', function($q) use ($promo_id) {
            $q->where('id', $promo_id);
        });
    }

}
