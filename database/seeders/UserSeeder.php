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
        User::query()->create([
            'name' => 'example admin',
            'address' => 'example admin address',
            'phone_number' => '0987654321',
            'email' => 'example_admin@gmail.com',
            'password' => Hash::make('example'),
            'role' => 'admin'
        ]);
    }
}
