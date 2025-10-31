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
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $firstName = fake()->firstName();
        $lastName = fake()->lastName();
        $username = strtolower($firstName . $lastName . fake()->numberBetween(1, 999));
        
        return [
            // Personal Information
            'full_name' => $firstName . ' ' . $lastName,
            'username' => $username,
            'email' => fake()->unique()->safeEmail(),
            'bank_name' => fake()->randomElement(['maybank', 'cimb', 'public_bank', 'rhb', 'hong_leong']),
            'bank_account_number' => fake()->numerify('##########'),
            
            // Contact Information
            'phone_number' => '+60' . fake()->numerify('#########'),
            'street_address' => fake()->streetAddress(),
            'city' => fake()->city(),
            'state' => fake()->randomElement(['selangor', 'kuala_lumpur', 'johor', 'perak', 'penang']),
            'postal_code' => fake()->numerify('#####'),
            
            // Academic Information
            'student_id' => 'RCMP' . fake()->unique()->numerify('######'),
            'course' => fake()->randomElement(['bachelor_medicine', 'diploma_nursing', 'diploma_pharmacy']),
            'semester' => fake()->numberBetween(1, 8),
            
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
