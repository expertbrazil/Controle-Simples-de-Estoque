<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class CreateDefaultUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create-default';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a default user for the system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $existingUser = User::where('email', 'noreply@expertbrazil.com.br')->first();
        
        if ($existingUser) {
            $this->error('Default user already exists.');
            return 1;
        }

        $user = User::create([
            'name' => 'Expert Brazil',
            'email' => 'noreply@expertbrazil.com.br',
            'password' => Hash::make('Sucesso#2025'),
        ]);

        $this->info('Default user created successfully.');
    }
}
