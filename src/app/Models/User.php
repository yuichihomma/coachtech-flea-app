<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
    'name',
    'email',
    'password',
    'postcode',
    'address',
    'building',
    'image', 
];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function likedItems()
    {
        return $this->belongsToMany(Item::class, 'likes');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function chatRoomsAsBuyer()
{
    return $this->hasMany(ChatRoom::class, 'buyer_id');
}

public function chatRoomsAsSeller()
{
    return $this->hasMany(ChatRoom::class, 'seller_id');
}

// 評価された（受け取った）評価
public function receivedRatings()
{
    return $this->hasMany(\App\Models\Rating::class, 'ratee_id');
}

// 評価した（送った）評価
public function givenRatings()
{
    return $this->hasMany(\App\Models\Rating::class, 'rater_id');
}

}
