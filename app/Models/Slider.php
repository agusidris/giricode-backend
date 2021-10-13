<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
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
     * getImageAttribute
     *
     * @param  mixed $image
     * @return void
     */
    // public function getImageAttribute($image)
    // {
    //     return asset('storage/sliders/' . $image);
    // }
}
