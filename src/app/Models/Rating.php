<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $fillable = [
        'chat_room_id',
        'rater_id',
        'ratee_id',
        'rating',
    ];

    public function rater()
    {
        return $this->belongsTo(\App\Models\User::class, 'rater_id');
    }

    public function ratee()
    {
        return $this->belongsTo(\App\Models\User::class, 'ratee_id');
    }
}
