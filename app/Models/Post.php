<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = 'posts';

    protected $fillable = ['content', 'file_name', 'user_id'];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'post_id', 'id');
    }

    public function suka()
    {
        return $this->hasMany(Like::class, 'post_id', 'id');
    }
}
