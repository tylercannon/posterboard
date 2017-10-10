<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'content',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function replies()
    {
        return $this->morphMany('App\Reply', 'repliable');
    }

    public function reposts()
    {
        return $this->hasMany('App\Repost');
    }

    public function favorites()
    {
        return $this->hasMany('App\Favorite');
    }
}