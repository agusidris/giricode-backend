<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Menu::create([
            'name'      => 'Home',
            'url'       => '/'
        ]);
        Menu::create([
            'name'      => 'Laravel',
            'url'       => '/category/laravel'
        ]);
        Menu::create([
            'name'      => 'Nuxt Js',
            'url'       => '/category/nuxt-js'
        ]);
    }
}
