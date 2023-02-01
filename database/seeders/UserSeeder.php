<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::create([
            'name' => 'Admin',
            'no_hp' => '081234567890',
            'email' => 'admin@cek.id',
            'password' => bcrypt('12345'),
            'remember_token' => Str::random(10),
            'api_token' => Str::random(100),
        ]);

        $admin->assignRole('admin');

        $user = User::create([
            'name' => 'User',
            'no_hp' => '081234567890',
            'email' => 'user@cek.id',
            'password' => bcrypt('12345'),
            'remember_token' => Str::random(10),
            'api_token' => Str::random(100),
        ]);

        $user->assignRole('user');
    }
}
