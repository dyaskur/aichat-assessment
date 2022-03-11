<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    //scopes
    public function scopeActive($query)
    {
        return $query->where('start_date', '<=', now())
            ->where('end_date', '>=', now());
    }

    //custom methods
    public function findByCode($code)
    {
        return $this->active()->where('code', $code)->first();
    }


    public function findCodeByLockedFor(int $customerId)
    {
        return $this->codes()->where('locked_for', $customerId)
            ->where('locked_until', '>=', now())
            ->first();
    }


    public function eligibleCheck($customer): bool
    {
        $eligible             = true;
        $customer_transaction = $customer->transactions(); //init customer transaction queries

        if ($this->last_transaction_days > 0) {
            //when last_transaction_days more than zero, transaction must be more than last_transaction_days
            $customer_transaction = $customer_transaction->where('created_at', '>=', now()->subDays($this->last_transaction_days));
        }

        if ($this->min_transaction_count > 0 && $eligible) {
            $eligible = $customer_transaction->count() >= $this->min_transaction_count;
        }
        if ($this->min_transaction_total > 0 && $eligible) {
            $eligible = $customer_transaction->sum('total_spent') >= $this->min_transaction_total;
        }
        if ($this->max_redemption_per_user_count > 0 && $eligible) {
            $eligible = $customer->transactions()->hasPromotion($this->id)->count() <
                $this->max_redemption_per_user_count;
        }

        return $eligible;
    }

    //relationship
    public function codes(): HasMany
    {
        return $this->hasMany(PromotionCode::class);
    }
}
