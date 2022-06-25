<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
	protected $table = 'likes';

	protected $fillable = ['post_id', 'user_id'];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
