<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    /**
     * guarded
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * posts
     *
     * @return void
     */
    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }

    /**
     * category
     *
     * @return void
     */
    public function color()
    {
        return $this->belongsTo(Color::class);
    }
}
