<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\SliderResource;
use App\Models\Slider;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        // get Slider
        $sliders = Slider::latest()->get();

        // return success with Api Resource
        return new SliderResource(true, 'List Data Slider', $sliders);
    }
}
