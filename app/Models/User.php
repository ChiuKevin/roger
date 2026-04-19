<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'nickname',
        'country_code',
        'phone',
        'email',
        'password',
        'image',
        'tags',
        'remark',
        'is_pro',
        'is_disabled'
    ];

    protected $casts = [
        'tags' => 'array',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'deleted_at'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function notificationSetting()
    {
        return $this->hasOne(UserNotificationSetting::class);
    }

    public function addresses()
    {
        return $this
            ->hasMany(UserAddress::class, 'user_id')
            ->select('id', 'contact_name', 'email', 'country_code', 'phone', 'address_line1', 'address_line2');
    }

    public function cards($region)
    {
        return $this
            ->hasMany(UserCard::class)
            ->where('region', $region)
            ->select('id', 'card_last_four', 'brand', 'exp_month', 'exp_year', 'is_default');
    }
}
