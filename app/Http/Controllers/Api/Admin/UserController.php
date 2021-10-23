<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
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
        if ($role === 'programmer') {

            $users = User::when(request()->q, function($users) {
                $users = $users->where('name', 'like', '%'. request()->q . '%');
            })->orderByDesc('id')->paginate(5);

            // return with Api Resource
            return new UserResource(true, 'List Data User', $users);

        } else if ($role === 'admin') {

            $users = User::when(request()->q, function($users) {
                $users = $users->where('name', 'like', '%'. request()->q . '%');
            })->whereNotIn('role', ['programmer'])->latest()->paginate(5);

            // return with Api Resource
            return new UserResource(true, 'List Data User', $users);

        } else if ($role === 'operator') {

            $users = User::when(request()->q, function($users) {
                $users = $users->where('name', 'like', '%'. request()->q . '%');
            })->whereNotIn('role', ['programmer', 'admin'])->latest()->paginate(5);

            // return with Api Resource
            return new UserResource(true, 'List Data User', $users);

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
        if ($role === 'programmer') {

            $validator = Validator::make($request->all(), [
                'image'         => 'nullable|image|mimes:jpeg,jpg,png|max:2000',
                'name'          => 'required',
                'username'      => 'required|unique:users',
                'email'         => 'required|email|unique:users',
                'password'      => 'required|confirmed'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            // upload image
            $image = $request->file('image');
            $image->storeAs('public/users', $image->hashName());

            // create user
            $user = User::create([
                'role'          => $request->role,
                'image'         => $image->hashName(),
                'name'          => $request->name,
                'info'          => $request->info,
                'username'      => $request->username,
                'email'         => $request->email,
                'password'      => bcrypt($request->password)
            ]);

            if ($user) {
                // return success with Api Resource
                return new UserResource(true, 'Data User Berhasil Disimpan!', $user);
            }

            // return failed with Api Resource
            return new UserResource(false, 'Data User Gagal Disimpan!', null);

        } else if ($role === 'admin') {

            $validator = Validator::make($request->all(), [
                'image'         => 'nullable|image|mimes:jpeg,jpg,png|max:2000',
                'name'          => 'required',
                'username'      => 'required|unique:users',
                'email'         => 'required|email|unique:users',
                'password'      => 'required|confirmed'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            if ($request->role !== 'programmer') {

                // upload image
                $image = $request->file('image');
                $image->storeAs('public/users', $image->hashName());

                // create user
                $user = User::create([
                    'role'          => $request->role,
                    'image'         => $image->hashName(),
                    'name'          => $request->name,
                    'info'          => $request->info,
                    'username'      => $request->username,
                    'email'         => $request->email,
                    'password'      => bcrypt($request->password)
                ]);

                if ($user) {
                    // return success with Api Resource
                    return new UserResource(true, 'Data User Berhasil Disimpan!', $user);
                }

                // return failed with Api Resource
                return new UserResource(false, 'Data User Gagal Disimpan!', null);
            } else {
                return new UserResource(false, 'Akses Ditolak!', null);
            }
        } else if ($role === 'operator') {

            $validator = Validator::make($request->all(), [
                'image'         => 'nullable|image|mimes:jpeg,jpg,png|max:2000',
                'name'          => 'required',
                'username'      => 'required|unique:users',
                'email'         => 'required|email|unique:users',
                'password'      => 'required|confirmed'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            if ($request->role !== 'programmer' && $request->role !== 'admin') {

                // upload image
                $image = $request->file('image');
                $image->storeAs('public/users', $image->hashName());

                // create user
                $user = User::create([
                    'role'          => $request->role,
                    'image'         => $image->hashName(),
                    'name'          => $request->name,
                    'info'          => $request->info,
                    'username'      => $request->username,
                    'email'         => $request->email,
                    'password'      => bcrypt($request->password)
                ]);

                if ($user) {
                    // return success with Api Resource
                    return new UserResource(true, 'Data User Berhasil Disimpan!', $user);
                }

                // return failed with Api Resource
                return new UserResource(false, 'Data User Gagal Disimpan!', null);
            } else {
                return new UserResource(false, 'Akses Ditolak!', null);
            }
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
        if ($role === 'programmer') {

            $user = User::whereId($id)->first();

            if ($user) {
                return new UserResource(true, 'Detail Data User!', $user);
            }

        } else if ($role === 'admin') {

            $user = User::whereId($id)->whereNotIn('role', ['programmer'])->first();

            if ($user) {
                return new UserResource(true, 'Detail Data User!', $user);
            } else {
                return new UserResource(false, 'Akses Ditolak!', null);
            }

        } else if ($role === 'operator') {

            $user = User::whereId($id)->whereNotIn('role', ['programmer', 'admin'])->first();

            if ($user) {
                return new UserResource(true, 'Detail Data User!', $user);
            } else {
                return new UserResource(false, 'Akses Ditolak!', null);
            }

        }
    }

    /**
     * update
     *
     * @param  mixed $user
     * @param  mixed $request
     * @return void
     */
    public function update(User $user, Request $request)
    {
        $role = auth()->user()->role;

        $validator = Validator::make($request->all(), [
            'image'         => 'nullable|image|mimes:jpeg,jpg,png|max:2000',
            'name'          => 'required',
            'username'      => 'required',
            'email'         => 'required|email',
            'password'      => 'nullable|confirmed'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // check role
        if ($role === 'programmer') {

            $user = User::findOrFail($user->id);

            if(!empty($request->password)) {

                // check image update
                if($request->file('image')) {

                    // remove old image
                    Storage::disk('local')->delete('public/users/'.basename($user->image));

                    // upload new image
                    $image = $request->file('image');
                    $image->storeAs('public/users', $image->hashName());

                    // update post with new image
                    $user->update([
                        'role'          => $request->role,
                        'image'         => $image->hashName(),
                        'name'          => $request->name,
                        'info'          => $request->info,
                        'username'      => $request->username,
                        'email'         => $request->email,
                        'password'      => bcrypt($request->password)
                    ]);
                }

                // update post with new image
                $user->update([
                    'role'          => $request->role,
                    'name'          => $request->name,
                    'info'          => $request->info,
                    'username'      => $request->username,
                    'email'         => $request->email,
                    'password'      => bcrypt($request->password)
                ]);

                if ($user) {
                    // return success with Api Resource
                    return new UserResource(true, 'Data User Berhasil Diupdate!', $user);
                }

                // return failed with Api Resource
                return new UserResource(false, 'Data User Gagal Diupdate!', null);

            }

            // check image update
            if($request->file('image')) {

                // remove old image
                Storage::disk('local')->delete('public/users/'.basename($user->image));

                // upload new image
                $image = $request->file('image');
                $image->storeAs('public/users', $image->hashName());

                // update post with new image
                $user->update([
                    'role'          => $request->role,
                    'image'         => $image->hashName(),
                    'name'          => $request->name,
                    'info'          => $request->info,
                    'username'      => $request->username,
                    'email'         => $request->email
                ]);
            }

            // update post with new image
            $user->update([
                'role'          => $request->role,
                'name'          => $request->name,
                'info'          => $request->info,
                'username'      => $request->username,
                'email'         => $request->email
            ]);

            if ($user) {
                // return success with Api Resource
                return new UserResource(true, 'Data User Berhasil Diupdate!', $user);
            }

            // return failed with Api Resource
            return new UserResource(false, 'Data User Gagal Diupdate!', null);

        } else if ($role === 'admin') {

            $validator = Validator::make($request->all(), [
                'image'         => 'nullable|image|mimes:jpeg,jpg,png|max:2000',
                'name'          => 'required',
                'username'      => 'required',
                'email'         => 'required|email',
                'password'      => 'nullable|confirmed'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            if ($request->role !== 'programmer') {

                $user = User::findOrFail($user->id);

                if(!empty($request->password)) {

                    // check image update
                    if($request->file('image')) {

                        // remove old image
                        Storage::disk('local')->delete('public/users/'.basename($user->image));

                        // upload new image
                        $image = $request->file('image');
                        $image->storeAs('public/users', $image->hashName());

                        // update post with new image
                        $user->update([
                            'role'          => $request->role,
                            'image'         => $image->hashName(),
                            'name'          => $request->name,
                            'info'          => $request->info,
                            'username'      => $request->username,
                            'email'         => $request->email,
                            'password'      => bcrypt($request->password)
                        ]);
                    }

                    // update post with new image
                    $user->update([
                        'role'          => $request->role,
                        'name'          => $request->name,
                        'info'          => $request->info,
                        'username'      => $request->username,
                        'email'         => $request->email,
                        'password'      => bcrypt($request->password)
                    ]);

                    if ($user) {
                        // return success with Api Resource
                        return new UserResource(true, 'Data User Berhasil Diupdate!', $user);
                    }

                    // return failed with Api Resource
                    return new UserResource(false, 'Data User Gagal Diupdate!', null);

                }

                // check image update
                if($request->file('image')) {

                    // remove old image
                    Storage::disk('local')->delete('public/users/'.basename($user->image));

                    // upload new image
                    $image = $request->file('image');
                    $image->storeAs('public/users', $image->hashName());

                    // update post with new image
                    $user->update([
                        'role'          => $request->role,
                        'image'         => $image->hashName(),
                        'name'          => $request->name,
                        'info'          => $request->info,
                        'username'      => $request->username,
                        'email'         => $request->email
                    ]);
                }

                // update post with new image
                $user->update([
                    'role'          => $request->role,
                    'name'          => $request->name,
                    'info'          => $request->info,
                    'username'      => $request->username,
                    'email'         => $request->email
                ]);

                if ($user) {
                    // return success with Api Resource
                    return new UserResource(true, 'Data User Berhasil Diupdate!', $user);
                }

                // return failed with Api Resource
                return new UserResource(false, 'Data User Gagal Diupdate!', null);

            } else {
                return new UserResource(false, 'Akses Ditolak!', null);
            }

        } else if ($role === 'operator') {

            $validator = Validator::make($request->all(), [
                'image'         => 'nullable|image|mimes:jpeg,jpg,png|max:2000',
                'name'          => 'required',
                'username'      => 'required',
                'email'         => 'required|email',
                'password'      => 'nullable|confirmed'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            if ($request->role !== 'programmer' && $request->role !== 'admin') {

                $user = User::findOrFail($user->id);

                if(!empty($request->password)) {

                    // check image update
                    if($request->file('image')) {

                        // remove old image
                        Storage::disk('local')->delete('public/users/'.basename($user->image));

                        // upload new image
                        $image = $request->file('image');
                        $image->storeAs('public/users', $image->hashName());

                        // update post with new image
                        $user->update([
                            'role'          => $request->role,
                            'image'         => $image->hashName(),
                            'name'          => $request->name,
                            'info'          => $request->info,
                            'username'      => $request->username,
                            'email'         => $request->email,
                            'password'      => bcrypt($request->password)
                        ]);
                    }

                    // update post with new image
                    $user->update([
                        'role'          => $request->role,
                        'name'          => $request->name,
                        'info'          => $request->info,
                        'username'      => $request->username,
                        'email'         => $request->email,
                        'password'      => bcrypt($request->password)
                    ]);

                    if ($user) {
                        // return success with Api Resource
                        return new UserResource(true, 'Data User Berhasil Diupdate!', $user);
                    }

                    // return failed with Api Resource
                    return new UserResource(false, 'Data User Gagal Diupdate!', null);

                }

                // check image update
                if($request->file('image')) {

                    // remove old image
                    Storage::disk('local')->delete('public/users/'.basename($user->image));

                    // upload new image
                    $image = $request->file('image');
                    $image->storeAs('public/users', $image->hashName());

                    // update post with new image
                    $user->update([
                        'role'          => $request->role,
                        'image'         => $image->hashName(),
                        'name'          => $request->name,
                        'info'          => $request->info,
                        'username'      => $request->username,
                        'email'         => $request->email
                    ]);
                }

                // update post with new image
                $user->update([
                    'role'          => $request->role,
                    'name'          => $request->name,
                    'info'          => $request->info,
                    'username'      => $request->username,
                    'email'         => $request->email
                ]);

                if ($user) {
                    // return success with Api Resource
                    return new UserResource(true, 'Data User Berhasil Diupdate!', $user);
                }

                // return failed with Api Resource
                return new UserResource(false, 'Data User Gagal Diupdate!', null);

            } else {
                return new UserResource(false, 'Akses Ditolak!', null);
            }
        }
    }

    /**
     * destroy
     *
     * @param  mixed $user
     * @return void
     */
    public function destroy(User $user)
    {
        $role = auth()->user()->role;

        // check role
        if ($role === 'programmer') {

            // remove image
            Storage::disk('local')->delete('public/users/'.basename($user->image));

            if ($user->delete()) {
                // return success with Api Resource
                return new UserResource(true, 'Data User Berhasil Dihapus!', null);
            }

            // return failed with Api Resource
            return new UserResource(false, 'Data User Gagal Dihapus!', null);

        } else if ($role === 'admin') {

            if ($user->role !== 'programmer') {

                // remove image
                Storage::disk('local')->delete('public/users/'.basename($user->image));

                if ($user->delete()) {
                    // return success with Api Resource
                    return new UserResource(true, 'Data User Berhasil Dihapus!', null);
                }

                // return failed with Api Resource
                return new UserResource(false, 'Data User Gagal Dihapus!', null);
            } else {
                return new UserResource(false, 'Akses Ditolak!', null);
            }

        } else if ($role === 'operator') {

            if ($user->role !== 'programmer' && $user->role !== 'admin') {
                // remove image
                Storage::disk('local')->delete('public/users/'.basename($user->image));

                if ($user->delete()) {
                    // return success with Api Resource
                    return new UserResource(true, 'Data User Berhasil Dihapus!', null);
                }

                // return failed with Api Resource
                return new UserResource(false, 'Data User Gagal Dihapus!', null);
            } else {
                return new UserResource(false, 'Akses Ditolak!', null);
            }

        }
    }
}
