<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('admin:ensure {email} {password} {--name=Admin}', function (string $email, string $password) {
    if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $this->error('Please provide a valid email address.');

        return self::FAILURE;
    }

    if (strlen($password) < 8) {
        $this->error('Password must be at least 8 characters.');

        return self::FAILURE;
    }

    $role = Role::updateOrCreate(
        ['name' => 'admin'],
        ['label' => 'Admin']
    );

    $user = User::updateOrCreate(
        ['email' => $email],
        [
            'role_id' => $role->id,
            'name' => (string) $this->option('name'),
            'password' => Hash::make($password),
        ]
    );

    $this->info("Admin account ready: {$user->email}");

    return self::SUCCESS;
})->purpose('Create or reset an administrator login account');
