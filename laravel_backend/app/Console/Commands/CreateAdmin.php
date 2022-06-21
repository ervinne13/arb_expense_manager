<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CreateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'arb:new-admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new administrator. This should be used when initializing the application, or if we lost access to all admins somehow.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $email = $this->ask('Admin Email:');
        $name = $this->ask('Admin Name:');
        $pw = $this->ask('Password (Recommended to use strong password generator):');

        User::create([
            'email' => $email,
            'name' => $name,
            'role' => 'admin' # default, see default roles seeder
        ]);

        return 0;
    }
}
