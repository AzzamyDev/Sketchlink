<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
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
            'name' => 'admin',
            'email' => 'anggabaskara60@gmail.com',
            'password' => Hash::make('angga890'),
            'type_id' => 1
        ]);

        DB::table('users')->insert([
            'name' => 'Abas',
            'email' => 'abas@gmail.com',
            'password' => Hash::make('angga890'),
            'type_id' => 3
        ]);
    }
}
