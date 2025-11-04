<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::firstOrCreate(
            ['email' => 'admin@unikl.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('admin123'),
            ]
        );
    }
}
