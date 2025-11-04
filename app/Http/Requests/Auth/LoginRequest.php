<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class LoginRequest extends FormRequest
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
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
            'user_type' => ['required', 'in:student,staff'],
            'staff_role' => ['nullable', 'required_if:user_type,staff', 'in:admin,approval,committee'],
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
            'login.required' => 'Student ID or Email is required.',
            'password.required' => 'Password is required.',
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $login = $this->input('login');
        $userType = $this->input('user_type');
        $staffRole = $this->input('staff_role');
        
        // Handle admin login
        if ($userType === 'staff' && $staffRole === 'admin') {
            $credentials = [
                'email' => $login,
                'password' => $this->input('password'),
            ];

            if (! Auth::guard('admin')->attempt($credentials, $this->boolean('remember'))) {
                RateLimiter::hit($this->throttleKey());

                throw ValidationException::withMessages([
                    'login' => 'These credentials do not match our records.',
                ]);
            }

            RateLimiter::clear($this->throttleKey());
            return;
        }
        
        // Handle committee login
        if ($userType === 'staff' && $staffRole === 'committee') {
            $credentials = [
                'email' => $login,
                'password' => $this->input('password'),
            ];

            if (! Auth::guard('committee')->attempt($credentials, $this->boolean('remember'))) {
                RateLimiter::hit($this->throttleKey());

                throw ValidationException::withMessages([
                    'login' => 'These credentials do not match our records.',
                ]);
            }

            RateLimiter::clear($this->throttleKey());
            return;
        }
        
        // Handle student login
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'student_id';
        
        $credentials = [
            $field => $login,
            'password' => $this->input('password'),
        ];

        if (! Auth::attempt($credentials, $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'login' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'login' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->input('login')).'|'.$this->ip());
    }
}