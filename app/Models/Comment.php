<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
	protected $table = 'comments';

    protected $fillable = ['post_id', 'note', 'created_by'];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }
}
