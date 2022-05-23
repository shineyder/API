<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserResourcePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_resource_permissions')->insert([
            [
                'user_id' => 2,
                'resource_id' => 1,
                'view' => TRUE,
                'create' => TRUE,
                'update' => TRUE,
                'delete' => TRUE,
            ],
            [
                'user_id' => 3,
                'resource_id' => 2,
                'view' => TRUE,
                'create' => TRUE,
                'update' => TRUE,
                'delete' => TRUE,
            ],
            [
                'user_id' => 4,
                'resource_id' => 3,
                'view' => TRUE,
                'create' => TRUE,
                'update' => TRUE,
                'delete' => TRUE,
            ],
            [
                'user_id' => 5,
                'resource_id' => 1,
                'view' => TRUE,
                'create' => TRUE,
                'update' => TRUE,
                'delete' => TRUE,
            ],
            [
                'user_id' => 5,
                'resource_id' => 2,
                'view' => TRUE,
                'create' => TRUE,
                'update' => TRUE,
                'delete' => TRUE,
            ]
        ]);
    }
}
