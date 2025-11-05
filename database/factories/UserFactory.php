<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Counter for generating unique values
     */
    protected static int $counter = 0;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static::$counter++;
        
        $firstNames = ['Ahmad', 'Siti', 'Muhammad', 'Aina', 'Faris', 'Zara', 'Hakim', 'Sarah', 'Arif', 'Nur'];
        $lastNames = ['Abdullah', 'Rahman', 'Hassan', 'Ali', 'Ibrahim', 'Zainal', 'Ismail', 'Yusof', 'Osman', 'Hussein'];
        $banks = ['maybank', 'cimb', 'public_bank', 'rhb', 'hong_leong'];
        $states = ['selangor', 'kuala_lumpur', 'johor', 'perak', 'penang'];
        $courses = ['bachelor_medicine', 'diploma_nursing', 'diploma_pharmacy'];
        
        $firstName = $firstNames[array_rand($firstNames)];
        $lastName = $lastNames[array_rand($lastNames)];
        $number = static::$counter + rand(100, 999);
        $username = strtolower($firstName . $lastName . $number);
        
        return [
            // Personal Information
            'full_name' => $firstName . ' ' . $lastName,
            'username' => $username,
            'email' => strtolower($firstName . '.' . $lastName . $number . '@example.com'),
            'bank_name' => $banks[array_rand($banks)],
            'bank_account_number' => str_pad((string) rand(1000000000, 9999999999), 10, '0', STR_PAD_LEFT),
            
            // Contact Information
            'phone_number' => '+60' . rand(100000000, 999999999),
            'street_address' => rand(1, 999) . ' Jalan Example',
            'city' => 'Kuala Lumpur',
            'state' => $states[array_rand($states)],
            'postal_code' => str_pad((string) rand(10000, 99999), 5, '0', STR_PAD_LEFT),
            
            // Academic Information
            'student_id' => 'RCMP' . str_pad((string) (static::$counter + rand(1000, 9999)), 6, '0', STR_PAD_LEFT),
            'course' => $courses[array_rand($courses)],
            'semester' => rand(1, 8),
            
            // Security and Status
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'is_active' => true,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
