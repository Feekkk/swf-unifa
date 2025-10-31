<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $userId = $this->user()->id;
        return [
            'full_name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'username' => ['required', 'string', 'max:20', 'min:3', Rule::unique('users')->ignore($userId), 'regex:/^[a-zA-Z0-9]+$/'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($userId)],
            'bank_name' => ['required', 'string', 'max:100'],
            'bank_account_number' => ['required', 'string', 'max:20', 'regex:/^[0-9]+$/'],
            'phone_number' => ['required', 'string', 'max:20', 'regex:/^[0-9]{7,20}$/'],
            'street_address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100', 'regex:/^[a-zA-Z\s]+$/'],
            'state' => ['required', 'string', 'max:100'],
            'postal_code' => ['required', 'string', 'regex:/^\d{5}$/'],
            'student_id' => ['required', 'string', 'max:20', Rule::unique('users')->ignore($userId), 'regex:/^[A-Z0-9]+$/'],
            'course' => ['required', 'string', 'max:100'],
            'semester' => ['required', 'integer', 'min:1', 'max:10'],
        ];
    }

    public function prepareForValidation(): void
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


