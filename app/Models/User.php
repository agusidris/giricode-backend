<?php

namespace App\Models;

use Carbon\Carbon;
use Tymon\JWTAuth\Contracts\JWTSubject; // <-- import JWTSubject
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements JWTSubject // <-- tambahkan ini
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
    * prooducts
    *
    * @return void
    */
   public function posts()
   {
    return $this->hasMany(Post::class);
   }

    /**
    * prooducts
    *
    * @return void
    */
    public function postseries()
    {
     return $this->hasMany(PostSeries::class);
    }

    public function ownsPost(Post $post) {
        return $this->id === $post->user->id;
    }

   /**
    * hasLikedPost not increment again
    *
    * @return void
    */
	public function hasLikedPost(Post $post) {
		return $post->likes->where('user_id', $this->id)->count() === 1;
	}


    /**
     * getImageAttribute
     *
     * @param  mixed $image
     * @return void
     */
    // public function getImageAttribute($image)
    // {
    //     return asset('storage/users/' . $image);
    // }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
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
}
