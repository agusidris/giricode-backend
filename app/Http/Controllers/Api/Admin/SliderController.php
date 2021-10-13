<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\SliderResource;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SliderController extends Controller
{
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth.role:admin,operator,programmer');
    }

    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        $role = auth()->user()->role;

        // check role
        if ($role === 'programmer' || $role === 'admin' || $role === 'operator') {

            // get Slider
            $sliders = Slider::orderByDesc('id')->paginate(5);

            // return with Api Resource
            return new SliderResource(true, 'List Data Slider', $sliders);
        }
    }

    /**
     * store
     *
     * @param  mixed $request
     * @return void
     */
    public function store(Request $request)
    {
        $role = auth()->user()->role;

        // check role
        if ($role === 'programmer' || $role === 'admin' || $role === 'operator') {

            // check validator $request
            $validator = Validator::make($request->all(), [
                'image' => 'required|image|mimes:jpeg,jpg,png|max:2000',
                'url'   =>  'required'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            // upload image
            $image = $request->file('image');
            $image->storeAs('public/sliders', $image->hashName());

            // create slider
            $slider = Slider::create([
                'image' => $image->hashName(),
                'url'   => $request->url
            ]);

            if ($slider) {
                // return success with Api Resource
                return new SliderResource(true, 'Data Slider Berhasil Disimpan!', $slider);
            }

            // return failed with Api Resource
            return new SliderResource(false, 'Data Slider Gagal Disimpan!', null);
        }
    }

    /**
     * show
     *
     * @param  mixed $id
     * @return void
     */
    public function show($id)
    {
        $role = auth()->user()->role;

        // check role
        if ($role === 'programmer' || $role === 'admin' || $role === 'operator') {
            $slider = Slider::whereId($id)->first();

            if ($slider) {
                // return success with Api Resource
                return new SliderResource(true, 'Detail Data Slider!', $slider);
            }

            // return failed with Api Resource
            return new SliderResource(false, 'Detail Data Slider Tidak Ditemukan!', null);
        }
    }

    /**
     * update
     *
     * @param  mixed $slider
     * @param  mixed $request
     * @return void
     */
    public function update(Slider $slider, Request $request)
    {
        $role = auth()->user()->role;

        // check role
        if ($role === 'programmer' || $role === 'admin' || $role === 'operator') {

            $validator = Validator::make($request->all(), [
                'url' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $slider = Slider::findOrFail($slider->id);

            // check image update
            if ($request->file('image')) {

                // remove old image
                Storage::disk('local')->delete('public/sliders/'.basename($slider->image));

                // upload new image
                $image = $request->file('image');
                $image->storeAs('public/sliders', $image->hashName());

                // update slider with new image
                $slider->update([
                    'image' => $image->hashName(),
                    'url'   => $request->url
                ]);
            }

            // update slider without image
            $slider->update([
                'url'   => $request->url
            ]);

            if ($slider) {
                // return success with Api Resource
                return new SliderResource(true, 'Data Slider Berhasil Diupdate!', $slider);
            }

            // return failed with Api Resource
            return new SliderResource(false, 'Data Slider Gagal Diupdate!', null);
        }
    }

    /**
     * destroy
     *
     * @param  mixed $slider
     * @return void
     */
    public function destroy(Slider $slider)
    {
        $role = auth()->user()->role;

        // check role
        if ($role === 'programmer' || $role === 'admin' || $role === 'operator') {

            // remove image
            Storage::disk('local')->delete('public/sliders/'.basename($slider->image));

            if ($slider->delete()) {
                // return success with Api Resource
                return new SliderResource(true, 'Data Slider Berhasil Dihapus!', null);
            }

            // return failed with Api Resource
            return new SliderResource(false, 'Data Slider Gagal Dihapus!', null);
        }
    }
}
