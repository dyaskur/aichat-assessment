<?php

namespace App\Models;

use App\Exceptions\FailResponse;
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

    /**
     * @throws FailResponse
     */
    public function findByCode($code)
    {
        $code = $this->active()->where('code', $code)->first();
        if (!$code) {
            throw new FailResponse('Promotion code not found');
        }

        return $code;
    }


    /**
     * @throws FailResponse
     */
    public function findCodeByLockedFor(int $customerId)
    {
        $code = $this->codes()->where('locked_for', $customerId)
            ->where('locked_until', '>=', now())
            ->first();

        if (!$code) {
            throw new FailResponse('Invalid/expired locked code, feel free to redeem the code (again)');
        }

        return $code;
    }

    /**
     * @throws FailResponse
     */
    public function lockAvailableCode($user, $expiryInMinutes = 10)
    {
        $code = $this->codes()->available()->limit(1)
            ->lockForUpdate()->update([
                                          'locked_for'   => $user->id,
                                          'locked_until' => now()->addMinutes($expiryInMinutes),
                                      ]);
        if ($code == 0) {
            throw new FailResponse('No available promotion codes');
        }
        return $code;
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
        if ($eligible) {
            $eligible = $this->codes()->where(function($query) use ($customer) {
                    $query->where('locked_for', $customer->id)
                        ->where('locked_until', '>=', now());
                })->orWhere('claimed_for', $customer->id)->count() == 0;
        }

        if (!$eligible) {
            //todo: give reason why not eligible
            throw new FailResponse('You are not eligible for this promotion');
        }

        return $eligible;
    }

    //relationship
    public function codes(): HasMany
    {
        return $this->hasMany(PromotionCode::class);
    }
}
