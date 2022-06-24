<?php

namespace Database\Seeders;

use App\Models\ExpenseCategory;
use Illuminate\Database\Seeder;

class DefaultExpenseCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ExpenseCategory::create(['name' => 'Travel', 'description' => 'Default expense category']);
        ExpenseCategory::create(['name' => 'Entertainment', 'description' => 'Default expense category']);
    }
}
