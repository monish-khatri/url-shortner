<?php

namespace App\Console\Commands;

use App\Models\User;
use Hash;
use Illuminate\Console\Command;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command will create user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
       // Create the user
       User::where('email', 'admin@admin.com')->delete();
       $user = User::create([
            'name' => 'Administrator',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin'),
        ]);

        $this->info("User {$user->name} created successfully with ID {$user->id}.");

        return 0;
    }
}
