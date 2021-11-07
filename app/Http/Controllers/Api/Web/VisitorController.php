<?php

namespace App\Http\Controllers\Api\Web;

use App\Models\View;
use App\Models\Post;
use App\Http\Controllers\Controller;
use App\Http\Resources\VisitorResource;
use Carbon\Carbon;
use Stevebauman\Location\Facades\Location;

class VisitorController extends Controller
{

    /**
     * getUserIp
     *
     * @param  mixed $post
     * @return void
     */
    public function getUserIp($slug)
    {
        $post = Post::where('slug', $slug)->first();

        $ipAddress = '';

        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipAddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipAddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
            $ipAddress = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipAddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipAddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipAddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipAddress = 'UNKNOWN';
        // $ipAddress = '125.164.0.239';
        // return Location::get($ipAddress);
        // return $location;

        $check = View::where('post_id', $post->id)->where('ipAddress', $ipAddress)->whereDate('created_at', Carbon::today())->first();
        // return $post;

        if ($ipAddress === '127.0.0.1' || $ipAddress === 'UNKNOWN') {

            return new VisitorResource(false, 'Visitor Berjalan di Localhost', $ipAddress);

        }  else {

            if ($check) {
                return new VisitorResource(false, 'Visitor Sudah Terdaftar hari ini', null);
            }

            $location = Location::get($ipAddress);

            // create post
            $view = View::create([
                'post_id'       => $post->id,
                'ipAddress'     => $location->ip,
                'countryCode'   => $location->countryCode,
                'countryName'   => $location->countryName
            ]);

            return new VisitorResource(true, 'Visitor Berhasil', $view);

        }
    }

}
