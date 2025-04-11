<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => env('ADMIN_USERNAME', 'dreamzinc.id@gmail.com'),
            'password' => Hash::make(env('ADMIN_PASSWORD', '123@Dreamz')),
        ]);
    }
}
