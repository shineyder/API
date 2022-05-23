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
            [
                'name' => 'Adriano ADM',
                'email' => 'adrianoadm@example.com',
                'password' => Hash::make('123456'),
                'is_admin' => TRUE,
            ],
            [
                'name' => 'Adriano Product',
                'email' => 'adrianoproduct@example.com',
                'password' => Hash::make('123456'),
                'is_admin' => FALSE,
            ],
            [
                'name' => 'Adriano Category',
                'email' => 'adrianocategory@example.com',
                'password' => Hash::make('123456'),
                'is_admin' => FALSE,
            ],
            [
                'name' => 'Adriano Brand',
                'email' => 'adrianobrand@example.com',
                'password' => Hash::make('123456'),
                'is_admin' => FALSE,
            ],
            [
                'name' => 'Adriano Product Category',
                'email' => 'adrianoproductcategory@example.com',
                'password' => Hash::make('123456'),
                'is_admin' => FALSE,
            ],
            [
                'name' => 'Adriano None',
                'email' => 'adrianonone@example.com',
                'password' => Hash::make('123456'),
                'is_admin' => FALSE,
            ]
        ]);
    }
}
