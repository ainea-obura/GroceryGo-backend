<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::create([
            //'uuid' => $this->generateUuid(),
            'firstName' => 'Admin',
            'lastName' => 'Test',
            'phone' => '0716332347',
            'email' => 'admin@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
        ])->assignRole('admin');

        // user
        $user = User::create([
            //'uuid' => $this->generateUuid(),
            'firstName' => 'User',
            'lastName' => 'Test',
            'phone' => '0716332547',
            'email' => 'user@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
        ])->assignRole('user');
    }
}
