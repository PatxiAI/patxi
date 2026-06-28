<?php

namespace PatxiAI\Patxi\Console\Commands;

use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Laravel\Fortify\Contracts\CreatesNewUsers;

use function Laravel\Prompts\password;
use function Laravel\Prompts\text;

#[Signature('patxiai:install')]
class InstallPatxiAI extends Command
{
    public function handle(): void
    {
        $name = text(
            label: 'What is your name?',
            required: 'Your name is required.'
        );
        $email = text(
            label: 'Email address',
            required: 'Your email address is required.',
            validate: function (string $value) {
                if (! filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    return 'Please enter a valid email address.';
                }

                if (DB::table('users')->where('email', $value)->exists()) {
                    return 'A user with this email address already exists.';
                }

                return null;
            }
        );

        $password = password(
            label: 'Password',
            required: 'The password is required.'
        );

        app(CreatesNewUsers::class)->create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $password,
        ]);

        $this->info("User {$email} created successfully.");
    }
}
