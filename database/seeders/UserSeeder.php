<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'role'      => 'programmer',
            'image'     => 'https://b.kisscc0.com/20180718/urw/kisscc0-ninja-computer-icons-samurai-youtube-avatar-ninja-5b4ed903c2dd20.4931332915318940197982.jpg',
            'name'      => 'Mochammad Agus Idris',
            'info'      => 'This is my info',
            'username'  => 'nuxtcraft',
            'email'     => 'magusidris@gmail.com',
            'password'  => Hash::make('Tidaktau'),
        ]);
    }
}
