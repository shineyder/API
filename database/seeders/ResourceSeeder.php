<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ResourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('resources')->insert([
            [
                'name' => 'produtos',
                'slug' => 'product',
            ],
            [
                'name' => 'categorias',
                'slug' => 'category',
            ],
            [
                'name' => 'marcas',
                'slug' => 'brand',
            ]
        ]);
    }
}
