<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostSeries extends Model
{
    use HasFactory;

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
     * User
     *
     * @return void
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * getCreatedAtAttribute
     *
     * @param  mixed $date
     * @return void
     */

    public function getCreatedAtAttribute($date)
    {
        return Carbon::parse($date)->isoFormat('DD MMM Y');
    }

    /**
     * getUpdatedAtAttribute
     *
     * @param  mixed $date
     * @return void
     */
    public function getUpdatedAtAttribute($date)
    {
        return Carbon::parse($date)->isoFormat('DD MMM Y');
    }
}
