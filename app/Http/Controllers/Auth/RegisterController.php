<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use App\Http\Requests\Auth\RegisterRequest;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation.
    |
    */

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Middleware is handled in routes/web.php for Laravel 11
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        return view('register');
    }

    /**
     * Get a validator instance for the incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            // Personal Information
            'full_name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:20', 'min:3', 'unique:users', 'regex:/^[a-zA-Z0-9]+$/'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'bank_name' => ['required', 'string', 'max:100'],
            'bank_account_number' => ['required', 'string', 'max:20'],
            
            // Contact Information
            'phone_number' => ['required', 'string', 'max:20'],
            'street_address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'postal_code' => ['required', 'string', 'regex:/^\d{5}$/'],
            
            // Academic Information
            'student_id' => ['required', 'string', 'max:20', 'unique:users'],
            'course' => ['required', 'string', 'max:100'],
            'semester' => ['required', 'integer', 'min:1', 'max:10'],
            'year_of_study' => ['required', 'integer', 'min:1', 'max:5'],
            
            // Security
            'password' => ['required', 'string', 'min:8', 'confirmed', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/'],
            'terms_accepted' => ['required', 'accepted'],
        ], [
            // Custom error messages
            'username.regex' => 'Username can only contain letters and numbers.',
            'username.unique' => 'This username is already taken.',
            'email.unique' => 'This email address is already registered.',
            'student_id.unique' => 'This Student ID is already registered.',
            'postal_code.regex' => 'Postal code must be exactly 5 digits.',
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.',
            'terms_accepted.required' => 'You must accept the terms and conditions.',
            'terms_accepted.accepted' => 'You must accept the terms and conditions.',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            // Personal Information
            'full_name' => $data['full_name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'bank_name' => $data['bank_name'],
            'bank_account_number' => $data['bank_account_number'],
            
            // Contact Information
            'phone_number' => $data['phone_number'],
            'street_address' => $data['street_address'],
            'city' => $data['city'],
            'state' => $data['state'],
            'postal_code' => $data['postal_code'],
            
            // Academic Information
            'student_id' => $data['student_id'],
            'course' => $data['course'],
            'semester' => $data['semester'],
            'year_of_study' => $data['year_of_study'],
            
            // Security
            'password' => Hash::make($data['password']),
            
            // Additional fields
            'email_verified_at' => now(), // Auto-verify for now
            'is_active' => true,
        ]);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \App\Http\Requests\Auth\RegisterRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(RegisterRequest $request)
    {
        try {
            $user = $this->create($request->validated());

            event(new Registered($user));

            // Log successful registration
            \Log::info('New user registered', [
                'user_id' => $user->id,
                'email' => $user->email,
                'student_id' => $user->student_id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            Auth::login($user);

            return redirect($this->redirectTo)->with('success', 
                'Welcome to UniKL RCMP Financial Aid System! Your account has been created successfully.'
            );
        } catch (\Exception $e) {
            \Log::error('Registration failed', [
                'error' => $e->getMessage(),
                'email' => $request->input('email'),
                'ip_address' => $request->ip()
            ]);

            return back()->withInput($request->except('password', 'password_confirmation'))
                ->withErrors(['registration' => 'Registration failed. Please try again.']);
        }
    }

    /**
     * Check if username is available via AJAX
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkUsername(Request $request)
    {
        $username = $request->input('username');
        
        if (!$username) {
            return response()->json(['available' => false, 'message' => 'Username is required']);
        }

        $exists = \App\Models\User::where('username', $username)->exists();
        
        return response()->json([
            'available' => !$exists,
            'message' => $exists ? 'Username is already taken' : 'Username is available'
        ]);
    }

    /**
     * Check if email is available via AJAX
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkEmail(Request $request)
    {
        $email = $request->input('email');
        
        if (!$email) {
            return response()->json(['available' => false, 'message' => 'Email is required']);
        }

        $exists = \App\Models\User::where('email', $email)->exists();
        
        return response()->json([
            'available' => !$exists,
            'message' => $exists ? 'Email is already registered' : 'Email is available'
        ]);
    }

    /**
     * Check if student ID is available via AJAX
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkStudentId(Request $request)
    {
        $studentId = $request->input('student_id');
        
        if (!$studentId) {
            return response()->json(['available' => false, 'message' => 'Student ID is required']);
        }

        $exists = \App\Models\User::where('student_id', $studentId)->exists();
        
        return response()->json([
            'available' => !$exists,
            'message' => $exists ? 'Student ID is already registered' : 'Student ID is available'
        ]);
    }


}