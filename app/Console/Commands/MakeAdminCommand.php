<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class MakeAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:make-admin {email : The email of the user to make admin} {--password= : Optional password for new user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a user an admin or create a new admin user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->option('password');

        // Check if user exists
        $user = User::where('email', $email)->first();

        if ($user) {
            // Make existing user admin
            $user->update(['role' => 'admin']);
            $this->info("✓ User {$email} is now an admin");
        } else {
            // Create new admin user
            if (!$password) {
                $password = $this->secret('Enter password for new admin user');
            }

            $name = $this->ask('Enter admin name (press Enter for default)', ucfirst(explode('@', $email)[0]));

            User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'role' => 'admin'
            ]);

            $this->info("✓ New admin user created: {$email}");
            $this->info("  Password: {$password}");
        }
    }
}
