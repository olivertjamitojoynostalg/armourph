<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CreateAdmin extends Command
{
    protected $signature = 'armour:admin {email}';

    protected $description = 'Create a maintenance administrator with a generated temporary password';

    public function handle(): int
    {
        $email = $this->argument('email');
        if (Validator::make(['email' => $email], ['email' => 'required|email|max:255'])->fails()) {
            $this->error('Enter a valid email.');

            return self::FAILURE;
        }
        if (User::where('email', $email)->exists()) {
            $this->error('That account already exists; no changes made.');

            return self::FAILURE;
        }
        $password = Str::password(20);
        $user = new User(['name' => 'Armour Admin', 'email' => $email, 'password' => $password]);
        $user->is_admin = true;
        $user->save();
        $this->info('Administrator created: '.$email);
        $this->line('Temporary password: '.$password);
        $this->line('Change it in maintenance settings after signing in.');

        return self::SUCCESS;
    }
}
