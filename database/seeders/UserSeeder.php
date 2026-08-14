<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password123'),
                'phone' => '01800000000',
                'status' => 'active',
            ]
        );
        $admin->syncRoles(['admin']);

        $trainer = User::updateOrCreate(
            ['email' => 'trainer@example.com'],
            [
                'name' => 'Trainer Demo',
                'password' => Hash::make('password123'),
                'phone' => '01811111111',
                'status' => 'active',
            ]
        );
        $trainer->syncRoles(['trainer']);

        $student = User::updateOrCreate(
            ['email' => 'student@example.com'],
            [
                'name' => 'Student Demo',
                'password' => Hash::make('password123'),
                'phone' => '01822222222',
                'status' => 'active',
            ]
        );
        $student->syncRoles(['student']);
    }
}