<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Personal Information
            'full_name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'username' => ['required', 'string', 'max:20', 'min:3', 'unique:users', 'regex:/^[a-zA-Z0-9]+$/'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'bank_name' => ['required', 'string', 'max:100'],
            'bank_account_number' => ['required', 'string', 'max:20', 'regex:/^[0-9]+$/'],
            
            // Contact Information
            'phone_number' => ['required', 'string', 'max:20', 'regex:/^\+?[1-9]\d{1,14}$/'],
            'street_address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100', 'regex:/^[a-zA-Z\s]+$/'],
            'state' => ['required', 'string', 'max:100'],
            'postal_code' => ['required', 'string', 'regex:/^\d{5}$/'],
            
            // Academic Information
            'student_id' => ['required', 'string', 'max:20', 'unique:users', 'regex:/^[A-Z0-9]+$/'],
            'course' => ['required', 'string', 'max:100'],
            'semester' => ['required', 'integer', 'min:1', 'max:10'],
            'year_of_study' => ['required', 'integer', 'min:1', 'max:5'],
            
            // Security
            'password' => ['required', 'string', 'confirmed', Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()],
            'terms_accepted' => ['required', 'accepted'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            // Personal Information
            'full_name.regex' => 'Full name can only contain letters and spaces.',
            'username.regex' => 'Username can only contain letters and numbers.',
            'username.unique' => 'This username is already taken.',
            'email.unique' => 'This email address is already registered.',
            'bank_account_number.regex' => 'Bank account number can only contain numbers.',
            
            // Contact Information
            'phone_number.regex' => 'Please enter a valid phone number with country code.',
            'city.regex' => 'City name can only contain letters and spaces.',
            'postal_code.regex' => 'Postal code must be exactly 5 digits.',
            
            // Academic Information
            'student_id.unique' => 'This Student ID is already registered.',
            'student_id.regex' => 'Student ID can only contain uppercase letters and numbers.',
            
            // Security
            'password.min' => 'Password must be at least 8 characters long.',
            'password.letters' => 'Password must contain at least one letter.',
            'password.mixed_case' => 'Password must contain both uppercase and lowercase letters.',
            'password.numbers' => 'Password must contain at least one number.',
            'password.symbols' => 'Password must contain at least one special character.',
            'terms_accepted.required' => 'You must accept the terms and conditions.',
            'terms_accepted.accepted' => 'You must accept the terms and conditions.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'full_name' => 'full name',
            'bank_account_number' => 'bank account number',
            'phone_number' => 'phone number',
            'street_address' => 'street address',
            'postal_code' => 'postal code',
            'student_id' => 'student ID',
            'year_of_study' => 'year of study',
            'password_confirmation' => 'password confirmation',
            'terms_accepted' => 'terms and conditions',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'username' => strtolower($this->username),
            'email' => strtolower($this->email),
            'student_id' => strtoupper($this->student_id),
            'full_name' => ucwords(strtolower($this->full_name)),
            'city' => ucwords(strtolower($this->city)),
        ]);
    }
}