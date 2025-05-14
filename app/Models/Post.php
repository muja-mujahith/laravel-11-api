<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    protected $fillable = [
        'title',
        'content',
        'post_id'
    ];

    function user()
    {
       return $this->belongsTo(User::class, foreignKey:'user_id');
    }

    function comment()
    {
        return $this->hasMany(Comment::class, foreignKey:'user_id');
    
    }

    function like()
    {
        return $this->belongsTo(Like::class, foreignKey:'user_id');
    }
}
