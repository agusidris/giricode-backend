<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Post extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    /**
     * guarded
     *
     * @var array
     */
    protected $guarded = [];

    protected $hidden = [
        'category_id',
        'pivot'
    ];

    /**
     * category
     *
     * @return void
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * tags
     *
     * @return void
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
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
     * Likes
     *
     * @return void
     */
	public function likes() {
		return $this->morphMany(Like::class, 'likeable');
	}

    /**
     * views
     *
     * @return void
     */
    public function views() {
		return $this->hasMany(View::class)->orderByDesc('id');
	}

    /**
     * post_series
     *
     * @return void
     */
    public function post_series()
    {
        return $this->belongsToMany(PostSeries::class);
    }

    /**
     * Comments
     *
     * @return void
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')->whereNull('parent_id');
    }

    /**
     * commentcount
     *
     * @return void
     */
    public function commentcount()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }


    /**
     * getImageAttribute
     *
     * @param  mixed $image
     * @return void
     */
    // public function getImageAttribute($image)
    // {
    //     return asset('storage/posts/' . $image);
    // }

    /**
     * getCreatedAtAttribute
     *
     * @param  mixed $date
     * @return void
     */
    public function getCreatedAtAttribute($date)
    {
        return Carbon::parse($date)->isoFormat('dddd, DD MMM Y');
    }

    /**
     * getUpdatedAtAttribute
     *
     * @param  mixed $date
     * @return void
     */
    public function getUpdatedAtAttribute($date)
    {
        return Carbon::parse($date)->isoFormat('dddd, DD MMM Y');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
              ->width(600);
    }

}
