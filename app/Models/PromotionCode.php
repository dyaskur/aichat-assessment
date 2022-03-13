<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\hasOne;
use Illuminate\Database\Query\Builder;

class PromotionCode extends Model
{
    use HasFactory;

    protected $fillable
        = [
            'code',
            'locked_for',
            'locked_until',
            'promotion_id',
            'claimed_for',
        ];

    //relationships
    public function promotion(): BelongsTo
    {
        return $this->belongsTo('App\Models\Promotion');
    }

    public function purchaseTransaction(): hasOne
    {
        return $this->hasOne('App\Models\PurchaseTransaction');
    }

    //scopes
    public function scopeAvailable(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where(function($query) {
            return $query->whereNull('locked_until')->orWhere('locked_until', '<', now());
        })->whereNull('claimed_for')->doesntHave('purchaseTransaction');
    }

    public function lockForCustomer(Customer $user, $expiryInMinutes = 10): void
    {
        $this->update([
                          'locked_for'   => $user->id,
                          'locked_until' => now()->addMinutes($expiryInMinutes),
                      ]);
    }

    public function claimForCustomer(Customer $user): void
    {
        $this->update([
                          'claimed_for' => $user->id,
                      ]);
    }


    public function unlock(): void
    {
        $this->update([
                          'locked_for'   => null,
                          'locked_until' => null,
                      ]);
    }
}
