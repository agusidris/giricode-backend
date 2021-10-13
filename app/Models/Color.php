<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    use HasFactory;

    /**
     * guarded
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * hidden
     *
     * @var array
     */
    protected $hidden = [
        'updated_at'
    ];

    /**
     * post
     *
     * @return void
     */
    public function tags()
    {
        return $this->hasMany(Tag::class);
    }
}
