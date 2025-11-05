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
        $firstName = $this->faker->firstName();
        $lastName = $this->faker->lastName();
        $username = strtolower($firstName . $lastName . $this->faker->numberBetween(1, 999));
        
        return [
            // Personal Information
            'full_name' => $firstName . ' ' . $lastName,
            'username' => $username,
            'email' => $this->faker->unique()->safeEmail(),
            'bank_name' => $this->faker->randomElement(['maybank', 'cimb', 'public_bank', 'rhb', 'hong_leong']),
            'bank_account_number' => $this->faker->numerify('##########'),
            
            // Contact Information
            'phone_number' => '+60' . $this->faker->numerify('#########'),
            'street_address' => $this->faker->streetAddress(),
            'city' => $this->faker->city(),
            'state' => $this->faker->randomElement(['selangor', 'kuala_lumpur', 'johor', 'perak', 'penang']),
            'postal_code' => $this->faker->numerify('#####'),
            
            // Academic Information
            'student_id' => 'RCMP' . $this->faker->unique()->numerify('######'),
            'course' => $this->faker->randomElement(['bachelor_medicine', 'diploma_nursing', 'diploma_pharmacy']),
            'semester' => $this->faker->numberBetween(1, 8),
            
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
