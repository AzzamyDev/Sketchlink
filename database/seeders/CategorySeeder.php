<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('category')->insert([
            'name' => 'Tools',
        ]);
        DB::table('category')->insert([
            'name' => 'Books',
        ]);
        DB::table('category')->insert([
            'name' => 'Games',
        ]);
        DB::table('category')->insert([
            'name' => 'Comunications',
        ]);
        DB::table('category')->insert([
            'name' => 'Editors',
        ]);
    }
}
