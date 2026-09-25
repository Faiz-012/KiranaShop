<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Altaf',
            'email' => 'altaf@gmail.com',
            'password'=> bcrypt('password123')
        ]);
    }
}
// 3|pEJ9E318W60tmo4rhlEnVP5eXFDqKS05RS7qCae99f5661ba
