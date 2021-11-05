<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\GuestBookResource;
use App\Models\GuestBook as Guest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GuestBookController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'          => 'required',
            'email'         => 'required|email',
            'subject'       => 'required',
            'body'          => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $guest = Guest::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'subject'       => $request->subject,
            'body'          => $request->body
        ]);

        if ($guest) {
            // return success with Api Resource
            return new GuestBookResource(true, 'Data GuestBook Berhasil Disimpan!', $guest);
        }

        // return failed with Api Resource
        return new GuestBookResource(false, 'Data GuestBook Gagal Disimpan!', null);
    }
}
