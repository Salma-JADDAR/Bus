<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Créer un admin
        User::create([
            'name' => 'Admin System',
            'email' => 'admin@buscompany.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'phone' => '+212612345678',
        ]);

     

        // Créer 10 clients
        User::factory(10)->client()->create();
    }
}