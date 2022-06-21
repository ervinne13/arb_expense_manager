<?php

namespace Database\Seeders;

use App\Models\Security\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['code' => 'admin', 'name' => 'Administrator']);
        Role::create(['code' => 'user', 'name' => 'User']);
    }
}
