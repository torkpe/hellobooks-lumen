<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert(
            [
                'email' => 'admin@hellobooks.com',
                'password' => Hash::make('silver'),
                'role' => 'admin'
            ]
        );
    }

}