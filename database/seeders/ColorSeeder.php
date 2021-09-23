<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Color;

class ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Color::create([
            'name'      => 'Purple',
            'value'     => 'is-primary'
        ]);
        Color::create([
            'name'      => 'Green',
            'value'     => 'is-success'
        ]);
        Color::create([
            'name'      => 'Red',
            'value'     => 'is-danger'
        ]);
        Color::create([
            'name'      => 'Yellow',
            'value'     => 'is-warning'
        ]);
        Color::create([
            'name'      => 'Blue',
            'value'     => 'is-info'
        ]);
        Color::create([
            'name'      => 'Indigo',
            'value'     => 'is-link'
        ]);
        Color::create([
            'name'      => 'Gray',
            'value'     => 'is-light'
        ]);
        Color::create([
            'name'      => 'Dark',
            'value'     => 'is-dark'
        ]);
    }
}
