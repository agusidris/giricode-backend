<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tag;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Tag::create([
            'name'      => 'Laravel',
            'slug'      => 'laravel',
            'color_id'  => '3',
        ]);
        Tag::create([
            'name'      => 'Nuxt Js',
            'slug'      => 'nuxt-js',
            'color_id'  => '2',
        ]);
        Tag::create([
            'name'      => 'PHP',
            'slug'      => 'php',
            'color_id'  => '6',
        ]);
        Tag::create([
            'name'      => 'Vue Js',
            'slug'      => 'vue-js',
            'color_id'  => '2',
        ]);
        Tag::create([
            'name'      => 'React Js',
            'slug'      => 'react-js',
            'color_id'  => '5',
        ]);
        Tag::create([
            'name'      => 'Git',
            'slug'      => 'git',
            'color_id'  => '8',
        ]);
        Tag::create([
            'name'      => 'JavaScript',
            'slug'      => 'javacript',
            'color_id'  => '4',
        ]);
    }
}
