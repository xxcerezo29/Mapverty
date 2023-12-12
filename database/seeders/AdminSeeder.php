<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $developer = \App\Models\User::create([
            'name' => 'Developer',
            'email' => 'vcerezo29@gmail.com',
            'password' => bcrypt('Cerezo0129'),
        ]);

        $developer->assignRole('Developer');
    }
}
