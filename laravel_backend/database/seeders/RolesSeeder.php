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
        Role::create(['description' => 'With access to role, user, and expenses management', 'name' => 'Administrator']);
        Role::create(['description' => 'Can view expenses', 'name' => 'User']);
    }
}
