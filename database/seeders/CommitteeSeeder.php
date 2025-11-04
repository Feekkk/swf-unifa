<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Committee;
use Illuminate\Support\Facades\Hash;

class CommitteeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $committees = [
            [
                'name' => 'Dr. Ahmad bin Abdullah',
                'email' => 'committee1@unikl.com',
                'password' => Hash::make('committee123'),
            ],
            [
                'name' => 'Prof. Dr. Siti Nurhaliza binti Hassan',
                'email' => 'committee2@unikl.com',
                'password' => Hash::make('committee123'),
            ],
            [
                'name' => 'Dr. Muhammad Faiz bin Ismail',
                'email' => 'committee3@unikl.com',
                'password' => Hash::make('committee123'),
            ],
            [
                'name' => 'Dr. Nurul Ain binti Mohd Zain',
                'email' => 'committee4@unikl.com',
                'password' => Hash::make('committee123'),
            ],
            [
                'name' => 'Dr. Lim Wei Ming',
                'email' => 'committee5@unikl.com',
                'password' => Hash::make('committee123'),
            ],
        ];

        foreach ($committees as $committee) {
            Committee::firstOrCreate(
                ['email' => $committee['email']],
                $committee
            );
        }
    }
}