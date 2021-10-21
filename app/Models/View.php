<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class View extends Model
{
    use HasFactory;

    /**
     * guarded
     *
     * @var array
     */
    protected $guarded = [];


	public function user() {
		return $this->belongsTo(User::class);
	}

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
