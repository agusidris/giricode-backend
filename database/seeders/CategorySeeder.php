<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create([
            'image'     => 'abcdrfere',
            'name'      => 'Vue Js',
            'slug'      => 'vue-js',
        ]);
        Category::create([
            'image'     => 'abcdrfere',
            'name'      => 'React Js',
            'slug'      => 'react-js',
        ]);
        Category::create([
            'image'     => 'abcdrfere',
            'name'      => 'PHP',
            'slug'      => 'php',
        ]);
        Category::create([
            'image'     => 'abcdrfere',
            'name'      => 'Nuxt Js',
            'slug'      => 'nuxt-js',
        ]);
        Category::create([
            'image'     => 'abcdrfere',
            'name'      => 'Laravel',
            'slug'      => 'laravel',
        ]);
        Category::create([
            'image'     => 'abcdrfere',
            'name'      => 'Lumen',
            'slug'      => 'lumen',
        ]);
        Category::create([
            'image'     => 'abcdrfere',
            'name'      => 'Python',
            'slug'      => 'python',
        ]);
        Category::create([
            'image'     => 'abcdrfere',
            'name'      => 'JavaScript',
            'slug'      => 'javascript',
        ]);
        Category::create([
            'image'     => 'abcdrfere',
            'name'      => 'CodeIgniter',
            'slug'      => 'codeigniter',
        ]);
        Category::create([
            'image'     => 'abcdrfere',
            'name'      => 'DevOps',
            'slug'      => 'devops',
        ]);
    }
}
