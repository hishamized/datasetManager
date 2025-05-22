<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin user
        User::factory()->create([
            'fullName' => 'System Admin',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123'), // secure hash
            'dateOfBirth' => '1990-01-01',
            'authorization' => 'active',
            'role' => 'master',
        ]);

        // Test user
        User::factory()->create([
            'fullName' => 'Test User',
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => Hash::make('test123'), // secure hash
            'dateOfBirth' => '2000-01-01',
            'authorization' => 'active',
            'role' => 'scholar',
        ]);
    }
}
