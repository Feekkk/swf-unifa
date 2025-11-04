<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use App\Http\Requests\Auth\LoginRequest;

class LoginController extends Controller
{
    use ThrottlesLogins;

    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen.
    |
    */

    /**
     * Where to redirect users after login.
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
     * Show the application's login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('login');
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'login';
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ], [
            'login.required' => 'Student ID or Email is required.',
            'password.required' => 'Password is required.',
        ]);
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        $login = $request->input('login');
        
        // Determine if login is email or student ID
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'student_id';
        
        return [
            $field => $login,
            'password' => $request->input('password'),
        ];
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Determine redirect based on user type
        $userType = $request->input('user_type');
        $staffRole = $request->input('staff_role');
        
        $redirectTo = $this->redirectTo;
        
        // Redirect admins to admin dashboard
        if ($userType === 'staff' && $staffRole === 'admin') {
            $redirectTo = '/admin/dashboard';
        }
        
        // Redirect committees to committee dashboard
        if ($userType === 'staff' && $staffRole === 'committee') {
            $redirectTo = '/committee/dashboard';
        }

        // Determine which guard to use for logging
        $guard = 'web';
        if ($userType === 'staff' && $staffRole === 'admin') {
            $guard = 'admin';
        } elseif ($userType === 'staff' && $staffRole === 'committee') {
            $guard = 'committee';
        }

        // Log successful login
        \Log::info('User logged in successfully', [
            'user_id' => Auth::guard($guard)->id(),
            'user_type' => $userType,
            'staff_role' => $staffRole,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return redirect($redirectTo)
            ->with('success', 'Welcome back! You have been logged in successfully.');
    }

    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            'login' => [trans('auth.failed')],
        ]);
    }



    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        // Log the logout event
        $userId = null;
        $guard = null;
        if (Auth::guard('admin')->check()) {
            $userId = Auth::guard('admin')->id();
            $guard = 'admin';
        } elseif (Auth::guard('committee')->check()) {
            $userId = Auth::guard('committee')->id();
            $guard = 'committee';
        } else {
            $userId = Auth::id();
            $guard = 'web';
        }

        \Log::info('User logged out', [
            'user_id' => $userId,
            'guard' => $guard,
            'ip_address' => $request->ip()
        ]);

        // Logout from all guards
        Auth::guard('admin')->logout();
        Auth::guard('committee')->logout();
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'You have been logged out successfully.');
    }

    /**
     * Get the throttle key for the given request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function throttleKey(Request $request)
    {
        return strtolower($request->input('login')).'|'.$request->ip();
    }

    /**
     * Get the maximum number of attempts to allow.
     *
     * @return int
     */
    public function maxAttempts()
    {
        return 5; // Allow 5 attempts before lockout
    }

    /**
     * Get the number of minutes to throttle for.
     *
     * @return int
     */
    public function decayMinutes()
    {
        return 15; // Lockout for 15 minutes
    }
}