<?php

namespace App\Models;

use App\Helpers\Helper;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Customer extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable
        = [
            'first_name',
            'email',
            'password',
        ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden
        = [
            'password',
            'remember_token',
        ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts
        = [
            'email_verified_at' => 'datetime',
        ];

    //relationships

    /**
     * @return HasMany
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(PurchaseTransaction::class);
    }

    /**
     * @return HasOne
     */
    public function lockedCode(): HasOne
    {
        return $this->hasOne(PromotionCode::class, 'locked_for')->where('locked_until', '>=', now());
    }

    /**
     * @return HasMany
     */
    public function lockedCodes(): HasMany
    {
        return $this->hasMany(PromotionCode::class, 'locked_for')->where('locked_until', '>=', now());
    }

    /**
     * @return HasMany
     */
    public function recognizedPhotos(): HasMany
    {
        return $this->hasMany(CustomerRecognizedPhoto::class, 'customer_id');
    }

    //custom method

    public function findLockedPromotion(Promotion $promotion): PromotionCode|null
    {
        return $this->lockedCodes()->where('promotion_id', $promotion->id)->first();
    }

    //mutator

    /**
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        return $this->first_name.' '.$this->last_name;
    }

    public function uploadImage(UploadedFile $image): bool|string
    {
        $extension = $image->getClientOriginalExtension(); // getting image extension
        $filePath  = Storage::putFileAs(
            "photos/customers/{$this->id}",
            $image,
            $this->id.'_'.time().'.'.$extension
        );

        $this->recognizedPhotos()->create(['photo_path' => $filePath]);

        return $filePath;
    }
}
