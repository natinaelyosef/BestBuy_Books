<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if users already exist to avoid duplicates
        if (User::where('email', 'superadmin@bookhub.com')->doesntExist()) {
            User::create([
                'name' => 'Super Admin',
                'email' => 'superadmin@bookhub.com',
                'password' => Hash::make('Admin@123'),
                'account_type' => 'super_admin',
                'email_verified_at' => now(),
            ]);
            
            $this->command->info('Super Admin created successfully!');
        } else {
            $this->command->info('Super Admin already exists.');
        }

        if (User::where('email', 'subadmin@bookhub.com')->doesntExist()) {
            User::create([
                'name' => 'Sub Admin',
                'email' => 'subadmin@bookhub.com',
                'password' => Hash::make('Admin@123'),
                'account_type' => 'sub_admin',
                'email_verified_at' => now(),
            ]);
            
            $this->command->info('Sub Admin created successfully!');
        } else {
            $this->command->info('Sub Admin already exists.');
        }
    }
}