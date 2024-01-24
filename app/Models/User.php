<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    function user_addresses()
    {
        return $this->hasMany(UserAddress::class);
    }
    function orders()
    {
        return $this->hasMany(Order::class);
    }
    function wishlist_items()
    {
        return $this->hasMany(WishlistItem::class);
    }
    function reviews()
    {
        return $this->hasMany(Review::class);
    }
    function helpfulReviews()
    {
        return $this->belongsToMany(Review::class, 'review_helpfuls')->withTimestamps();
    }
    function scopeFiltered(Builder $query)
    {
        $query->when(request('search'), function ($query) {
            $query->where('name', 'like', '%' . request('search') . '%');
        });
    }
}
