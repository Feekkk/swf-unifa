<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="UniKL RCMP Student Financial Aid Login - Access your account to apply for Student Welfare Fund">
    <title>Login - UniKL RCMP Financial Aid</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    @vite(['resources/css/variables.css', 'resources/css/base.css', 'resources/css/components.css', 'resources/css/layout.css'])
    
    <!-- Custom Login Styles -->
    <style>
        .auth-container {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: var(--spacing-lg);
            position: relative;
        }
        
        .auth-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.05)" stroke-width="0.5"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
            opacity: 0.3;
        }
        
        .auth-card {
            background: var(--white);
            border-radius: var(--border-radius-xl);
            box-shadow: var(--shadow-xl);
            padding: var(--spacing-2xl);
            width: 100%;
            max-width: 420px;
            position: relative;
            z-index: 1;
        }
        
        .auth-header {
            text-align: center;
            margin-bottom: var(--spacing-2xl);
        }
        
        .auth-logo {
            width: 80px;
            height: 80px;
            background: var(--primary-color);
            border-radius: var(--border-radius-lg);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto var(--spacing-lg);
            color: var(--white);
            font-size: 32px;
            font-weight: var(--font-weight-bold);
        }
        
        .auth-title {
            font-size: var(--font-size-h3);
            font-weight: var(--font-weight-semibold);
            color: var(--primary-color);
            margin-bottom: var(--spacing-sm);
        }
        
        .auth-subtitle {
            color: var(--gray-600);
            font-size: var(--font-size-body);
            margin-bottom: 0;
        }
        
        .auth-form {
            margin-bottom: var(--spacing-xl);
        }
        
        .form-group {
            margin-bottom: var(--spacing-lg);
        }
        
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: var(--spacing-xl);
            flex-wrap: wrap;
            gap: var(--spacing-sm);
        }
        
        .forgot-password {
            color: var(--primary-color);
            font-size: var(--font-size-small);
            text-decoration: none;
            font-weight: var(--font-weight-medium);
        }
        
        .forgot-password:hover,
        .forgot-password:focus {
            color: var(--primary-dark);
            text-decoration: underline;
        }
        
        .auth-footer {
            text-align: center;
            padding-top: var(--spacing-lg);
            border-top: 1px solid var(--gray-200);
        }
        
        .auth-footer p {
            color: var(--gray-600);
            margin-bottom: var(--spacing-sm);
        }
        
        .register-link {
            color: var(--primary-color);
            font-weight: var(--font-weight-medium);
            text-decoration: none;
        }
        
        .register-link:hover,
        .register-link:focus {
            color: var(--primary-dark);
            text-decoration: underline;
        }
        
        .back-home {
            position: absolute;
            top: var(--spacing-lg);
            left: var(--spacing-lg);
            color: var(--white);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: var(--spacing-xs);
            font-weight: var(--font-weight-medium);
            transition: all var(--transition-fast);
            z-index: 2;
        }
        
        .back-home:hover,
        .back-home:focus {
            color: var(--accent-color);
            text-decoration: none;
            transform: translateX(-2px);
        }
        
        /* Responsive Design */
        @media (max-width: 767px) {
            .auth-container {
                padding: var(--spacing-md);
            }
            
            .auth-card {
                padding: var(--spacing-xl);
            }
            
            .auth-title {
                font-size: var(--font-size-h4-mobile);
            }
            
            .form-options {
                flex-direction: column;
                align-items: flex-start;
                gap: var(--spacing-md);
            }
            
            .back-home {
                position: static;
                margin-bottom: var(--spacing-lg);
                color: var(--white);
                align-self: flex-start;
            }
        }
        
        /* Loading State */
        .btn.loading {
            position: relative;
            color: transparent;
        }
        
        .btn.loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 20px;
            height: 20px;
            margin: -10px 0 0 -10px;
            border: 2px solid currentColor;
            border-top-color: transparent;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <!-- Skip Link for Accessibility -->
    <a href="#main-content" class="skip-link">Skip to main content</a>
    
    <div class="auth-container">
        <a href="/" class="back-home">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M19 12H5M12 19l-7-7 7-7"/>
            </svg>
            Back to Home
        </a>
        
        <div class="auth-card">
            <div class="auth-header">
                <div class="auth-logo">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                        <path d="M2 17l10 5 10-5"/>
                        <path d="M2 12l10 5 10-5"/>
                    </svg>
                </div>
                <h1 class="auth-title">Welcome Back</h1>
                <p class="auth-subtitle">Sign in to your UniKL RCMP Financial Aid account</p>
            </div>
            
            <main id="main-content">
                @if ($errors->any())
                    <div class="alert alert-error" role="alert">
                        <ul style="margin: 0; padding-left: var(--spacing-lg);">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                
                <form method="POST" action="{{ route('login') }}" class="auth-form" id="login-form" data-validate novalidate>
                    @csrf
                    
                    <div class="form-group">
                        <label for="login" class="form-label required">Student ID or Email</label>
                        <input 
                            type="text" 
                            id="login" 
                            name="login" 
                            class="form-input @error('login') error @enderror" 
                            value="{{ old('login') }}" 
                            required 
                            autocomplete="username"
                            placeholder="Enter your Student ID or Email"
                            aria-describedby="login-error"
                        >
                        @error('login')
                            <span class="form-error" id="login-error" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="password" class="form-label required">Password</label>
                        <div class="form-input-group">
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                class="form-input @error('password') error @enderror" 
                                required 
                                autocomplete="current-password"
                                placeholder="Enter your password"
                                aria-describedby="password-error"
                            >
                            <button type="button" class="password-toggle" aria-label="Toggle password visibility">
                                <svg class="password-show" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                                <svg class="password-hide" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: none;">
                                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                                    <line x1="1" y1="1" x2="23" y2="23"/>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <span class="form-error" id="password-error" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-options">
                        <div class="form-checkbox">
                            <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label for="remember">Remember me</label>
                        </div>
                        
                        <a href="{{ route('password.request') }}" class="forgot-password">
                            Forgot your password?
                        </a>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-large" id="login-btn">
                        Sign In
                    </button>
                </form>
            </main>
            
            <div class="auth-footer">
                <p>Don't have an account?</p>
                <a href="{{ route('register') }}" class="register-link">Create an account</a>
            </div>
        </div>
    </div>
    
    <!-- Vite JavaScript -->
    @vite(['resources/js/app.js', 'resources/js/auth.js', 'resources/js/accessibility.js'])
    
    <!-- JavaScript for Enhanced UX -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Password toggle functionality
            const passwordToggle = document.querySelector('.password-toggle');
            const passwordInput = document.querySelector('#password');
            const showIcon = document.querySelector('.password-show');
            const hideIcon = document.querySelector('.password-hide');
            
            if (passwordToggle && passwordInput) {
                passwordToggle.addEventListener('click', function() {
                    const isPassword = passwordInput.type === 'password';
                    passwordInput.type = isPassword ? 'text' : 'password';
                    showIcon.style.display = isPassword ? 'none' : 'block';
                    hideIcon.style.display = isPassword ? 'block' : 'none';
                    passwordToggle.setAttribute('aria-label', 
                        isPassword ? 'Hide password' : 'Show password'
                    );
                });
            }
            
            // Form submission with loading state
            const form = document.querySelector('.auth-form');
            const submitBtn = document.querySelector('#login-btn');
            
            if (form && submitBtn) {
                form.addEventListener('submit', function(e) {
                    // Validate form before submission
                    const loginField = document.querySelector('#login');
                    const passwordField = document.querySelector('#password');
                    
                    if (!validateField(loginField) || !validateField(passwordField)) {
                        e.preventDefault();
                        return false;
                    }
                    
                    // Show loading state
                    submitBtn.classList.add('loading');
                    submitBtn.disabled = true;
                    submitBtn.textContent = 'Signing In...';
                    
                    // Re-enable button after 10 seconds as fallback
                    setTimeout(() => {
                        submitBtn.classList.remove('loading');
                        submitBtn.disabled = false;
                        submitBtn.textContent = 'Sign In';
                    }, 10000);
                });
            }
            
            // Real-time validation feedback
            const inputs = document.querySelectorAll('.form-input');
            inputs.forEach(input => {
                input.addEventListener('blur', function() {
                    validateField(this);
                });
                
                input.addEventListener('input', function() {
                    if (this.classList.contains('error')) {
                        validateField(this);
                    }
                });
            });
            
            function validateField(field) {
                const value = field.value.trim();
                const fieldName = field.name;
                let isValid = true;
                let errorMessage = '';
                
                // Remove existing error state
                field.classList.remove('error', 'success');
                const existingError = field.parentNode.querySelector('.form-error:not([id])');
                if (existingError) {
                    existingError.remove();
                }
                
                // Validate based on field type
                if (fieldName === 'login') {
                    if (!value) {
                        isValid = false;
                        errorMessage = 'Student ID or Email is required';
                    } else if (value.includes('@')) {
                        // Email validation
                        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        if (!emailRegex.test(value)) {
                            isValid = false;
                            errorMessage = 'Please enter a valid email address';
                        }
                    } else {
                        // Student ID validation (assuming format like RCMP123456)
                        if (value.length < 6) {
                            isValid = false;
                            errorMessage = 'Student ID must be at least 6 characters';
                        }
                    }
                } else if (fieldName === 'password') {
                    if (!value) {
                        isValid = false;
                        errorMessage = 'Password is required';
                    } else if (value.length < 6) {
                        isValid = false;
                        errorMessage = 'Password must be at least 6 characters';
                    }
                }
                
                // Apply validation state
                if (!isValid) {
                    field.classList.add('error');
                    const errorSpan = document.createElement('span');
                    errorSpan.className = 'form-error';
                    errorSpan.textContent = errorMessage;
                    errorSpan.setAttribute('role', 'alert');
                    field.parentNode.appendChild(errorSpan);
                } else if (value) {
                    field.classList.add('success');
                }
                
                return isValid;
            }
            
            // Keyboard navigation enhancement
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && e.target.matches('.form-input')) {
                    const inputs = Array.from(document.querySelectorAll('.form-input'));
                    const currentIndex = inputs.indexOf(e.target);
                    const nextInput = inputs[currentIndex + 1];
                    
                    if (nextInput) {
                        e.preventDefault();
                        nextInput.focus();
                    }
                }
            });
        });
    </script>
    
    <style>
        /* Password toggle button styles */
        .form-input-group {
            position: relative;
        }
        
        .password-toggle {
            position: absolute;
            right: var(--spacing-md);
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--gray-500);
            cursor: pointer;
            padding: var(--spacing-xs);
            border-radius: var(--border-radius-sm);
            transition: color var(--transition-fast);
        }
        
        .password-toggle:hover,
        .password-toggle:focus {
            color: var(--primary-color);
            outline: 2px solid var(--primary-color);
            outline-offset: 2px;
        }
        
        .form-input-group .form-input {
            padding-right: calc(var(--spacing-md) + 40px);
        }
        
        /* Enhanced focus styles */
        .form-input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(22, 71, 106, 0.1);
        }
        
        .form-input.success {
            border-color: var(--success);
        }
        
        .form-input.success:focus {
            box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.1);
        }
        
        .form-input.error {
            border-color: var(--error);
        }
        
        .form-input.error:focus {
            box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1);
        }
    </style>
</body>
</html>