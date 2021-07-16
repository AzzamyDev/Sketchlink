<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('type')->insert([
            'name' => 'Admin',
        ]);
        DB::table('type')->insert([
            'name' => 'Beginner',
        ]);
        DB::table('type')->insert([
            'name' => 'Premium',
        ]);
        DB::table('type')->insert([
            'name' => 'Diamond',
        ]);
    }
}
