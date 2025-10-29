<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Welcome Back');
        $response->assertSee('Sign in to your UniKL RCMP Financial Aid account');
    }

    public function test_register_page_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertSee('Create Account');
        $response->assertSee('Join UniKL RCMP Financial Aid System');
    }

    public function test_users_can_authenticate_with_email(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post('/login', [
            'login' => 'test@example.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/');
    }

    public function test_users_can_authenticate_with_student_id(): void
    {
        $user = User::factory()->create([
            'student_id' => 'RCMP123456',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post('/login', [
            'login' => 'RCMP123456',
            'password' => 'password123',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/');
    }

    public function test_users_cannot_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post('/login', [
            'login' => 'test@example.com',
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors(['login']);
    }

    public function test_users_can_register(): void
    {
        $response = $this->post('/register', [
            'full_name' => 'John Doe',
            'username' => 'johndoe',
            'email' => 'john@example.com',
            'bank_name' => 'maybank',
            'bank_account_number' => '1234567890',
            'phone_number' => '+60123456789',
            'street_address' => '123 Main Street',
            'city' => 'Kuala Lumpur',
            'state' => 'wp_kuala_lumpur',
            'postal_code' => '50450',
            'student_id' => 'RCMP123456',
            'course' => 'bachelor_medicine',
            'semester' => 1,
            'year_of_study' => 1,
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'terms_accepted' => true,
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/');
        
        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
            'username' => 'johndoe',
            'student_id' => 'RCMP123456',
        ]);
    }

    public function test_registration_requires_all_fields(): void
    {
        $response = $this->post('/register', []);

        $response->assertSessionHasErrors([
            'full_name',
            'username',
            'email',
            'bank_name',
            'bank_account_number',
            'phone_number',
            'street_address',
            'city',
            'state',
            'postal_code',
            'student_id',
            'course',
            'semester',
            'year_of_study',
            'password',
            'terms_accepted',
        ]);
    }

    public function test_username_must_be_unique(): void
    {
        User::factory()->create(['username' => 'johndoe']);

        $response = $this->post('/register', [
            'full_name' => 'Jane Doe',
            'username' => 'johndoe', // Duplicate username
            'email' => 'jane@example.com',
            'bank_name' => 'maybank',
            'bank_account_number' => '1234567890',
            'phone_number' => '+60123456789',
            'street_address' => '123 Main Street',
            'city' => 'Kuala Lumpur',
            'state' => 'wp_kuala_lumpur',
            'postal_code' => '50450',
            'student_id' => 'RCMP654321',
            'course' => 'bachelor_medicine',
            'semester' => 1,
            'year_of_study' => 1,
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'terms_accepted' => true,
        ]);

        $response->assertSessionHasErrors(['username']);
    }

    public function test_ajax_username_availability_check(): void
    {
        // Test available username
        $response = $this->postJson('/check-username', [
            'username' => 'newuser'
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'available' => true,
                    'message' => 'Username is available'
                ]);

        // Create user and test unavailable username
        User::factory()->create(['username' => 'existinguser']);

        $response = $this->postJson('/check-username', [
            'username' => 'existinguser'
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'available' => false,
                    'message' => 'Username is already taken'
                ]);
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }
}