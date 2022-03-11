<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function promotion(): BelongsTo
    {
        return $this->belongsTo('App\Models\Promotion');
    }

    public function scopeAvailable($query)
    {
        return $query->where(function($query) {
            return $query->whereNull('locked_until')->orWhere('locked_until', '<', now());
        });
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
