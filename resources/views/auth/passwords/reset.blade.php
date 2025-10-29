<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="UniKL RCMP Financial Aid - Reset Password">
    <title>Reset Password - UniKL RCMP Financial Aid</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    @vite(['resources/css/variables.css', 'resources/css/base.css', 'resources/css/components.css', 'resources/css/layout.css'])
    
    <!-- Custom Auth Styles -->
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
        
        .auth-footer {
            text-align: center;
            padding-top: var(--spacing-lg);
            border-top: 1px solid var(--gray-200);
        }
        
        .auth-footer p {
            color: var(--gray-600);
            margin-bottom: var(--spacing-sm);
        }
        
        .back-link {
            color: var(--primary-color);
            font-weight: var(--font-weight-medium);
            text-decoration: none;
        }
        
        .back-link:hover,
        .back-link:focus {
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
            
            .back-home {
                position: static;
                margin-bottom: var(--spacing-lg);
                color: var(--white);
                align-self: flex-start;
            }
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
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                        <circle cx="12" cy="16" r="1"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                </div>
                <h1 class="auth-title">Reset Password</h1>
                <p class="auth-subtitle">Enter your new password below</p>
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
                
                <form method="POST" action="{{ route('password.update') }}" class="auth-form" novalidate>
                    @csrf
                    
                    <input type="hidden" name="token" value="{{ $token }}">
                    
                    <div class="form-group">
                        <label for="email" class="form-label required">Email Address</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            class="form-input @error('email') error @enderror" 
                            value="{{ $email ?? old('email') }}" 
                            required 
                            autocomplete="email"
                            readonly
                            aria-describedby="email-error"
                        >
                        @error('email')
                            <span class="form-error" id="email-error" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="password" class="form-label required">New Password</label>
                        <div class="form-input-group">
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                class="form-input @error('password') error @enderror" 
                                required 
                                autocomplete="new-password"
                                placeholder="Enter your new password"
                                aria-describedby="password-error password-help"
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
                        <div class="password-strength" id="password-strength">
                            <div class="password-strength-bar"></div>
                        </div>
                        <span class="form-help" id="password-help">Password must be at least 8 characters with uppercase, lowercase, number, and special character</span>
                        @error('password')
                            <span class="form-error" id="password-error" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="password_confirmation" class="form-label required">Confirm New Password</label>
                        <div class="form-input-group">
                            <input 
                                type="password" 
                                id="password_confirmation" 
                                name="password_confirmation" 
                                class="form-input @error('password_confirmation') error @enderror" 
                                required 
                                autocomplete="new-password"
                                placeholder="Confirm your new password"
                                aria-describedby="password_confirmation-error"
                            >
                            <button type="button" class="password-toggle-confirm" aria-label="Toggle password visibility">
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
                        @error('password_confirmation')
                            <span class="form-error" id="password_confirmation-error" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-large" id="reset-btn">
                        Reset Password
                    </button>
                </form>
            </main>
            
            <div class="auth-footer">
                <p>Remember your password?</p>
                <a href="{{ route('login') }}" class="back-link">Back to Login</a>
            </div>
        </div>
    </div>
    
    <!-- JavaScript for Enhanced UX -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Password toggle functionality
            const passwordToggle = document.querySelector('.password-toggle');
            const passwordInput = document.querySelector('#password');
            const showIcon = passwordToggle.querySelector('.password-show');
            const hideIcon = passwordToggle.querySelector('.password-hide');
            
            passwordToggle.addEventListener('click', function() {
                const isPassword = passwordInput.type === 'password';
                passwordInput.type = isPassword ? 'text' : 'password';
                showIcon.style.display = isPassword ? 'none' : 'block';
                hideIcon.style.display = isPassword ? 'block' : 'none';
                passwordToggle.setAttribute('aria-label', 
                    isPassword ? 'Hide password' : 'Show password'
                );
            });
            
            // Confirm password toggle
            const passwordToggleConfirm = document.querySelector('.password-toggle-confirm');
            const passwordConfirmInput = document.querySelector('#password_confirmation');
            const showIconConfirm = passwordToggleConfirm.querySelector('.password-show');
            const hideIconConfirm = passwordToggleConfirm.querySelector('.password-hide');
            
            passwordToggleConfirm.addEventListener('click', function() {
                const isPassword = passwordConfirmInput.type === 'password';
                passwordConfirmInput.type = isPassword ? 'text' : 'password';
                showIconConfirm.style.display = isPassword ? 'none' : 'block';
                hideIconConfirm.style.display = isPassword ? 'block' : 'none';
                passwordToggleConfirm.setAttribute('aria-label', 
                    isPassword ? 'Hide password' : 'Show password'
                );
            });
            
            // Form submission with loading state
            const form = document.querySelector('.auth-form');
            const submitBtn = document.querySelector('#reset-btn');
            
            if (form && submitBtn) {
                form.addEventListener('submit', function() {
                    submitBtn.classList.add('loading');
                    submitBtn.disabled = true;
                });
            }
            
            // Password strength indicator
            passwordInput.addEventListener('input', function() {
                updatePasswordStrength(this.value);
            });
            
            function updatePasswordStrength(password) {
                const strengthIndicator = document.getElementById('password-strength');
                let strength = 0;
                
                // Check password criteria
                if (password.length >= 8) strength++;
                if (/[a-z]/.test(password)) strength++;
                if (/[A-Z]/.test(password)) strength++;
                if (/[0-9]/.test(password)) strength++;
                if (/[^A-Za-z0-9]/.test(password)) strength++;
                
                // Remove existing strength classes
                strengthIndicator.className = 'password-strength';
                
                // Add appropriate strength class
                if (strength <= 2) {
                    strengthIndicator.classList.add('password-strength-weak');
                } else if (strength === 3) {
                    strengthIndicator.classList.add('password-strength-fair');
                } else if (strength === 4) {
                    strengthIndicator.classList.add('password-strength-good');
                } else if (strength === 5) {
                    strengthIndicator.classList.add('password-strength-strong');
                }
            }
            
            // Real-time validation
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
                if (fieldName === 'password') {
                    if (!value) {
                        isValid = false;
                        errorMessage = 'Password is required';
                    } else if (value.length < 8) {
                        isValid = false;
                        errorMessage = 'Password must be at least 8 characters';
                    } else {
                        const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/;
                        if (!passwordRegex.test(value)) {
                            isValid = false;
                            errorMessage = 'Password must contain uppercase, lowercase, number, and special character';
                        }
                    }
                } else if (fieldName === 'password_confirmation') {
                    const password = document.getElementById('password').value;
                    if (!value) {
                        isValid = false;
                        errorMessage = 'Password confirmation is required';
                    } else if (value !== password) {
                        isValid = false;
                        errorMessage = 'Passwords do not match';
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
        });
    </script>
    
    <style>
        /* Password toggle button styles */
        .form-input-group {
            position: relative;
        }
        
        .password-toggle,
        .password-toggle-confirm {
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
        .password-toggle:focus,
        .password-toggle-confirm:hover,
        .password-toggle-confirm:focus {
            color: var(--primary-color);
            outline: 2px solid var(--primary-color);
            outline-offset: 2px;
        }
        
        .form-input-group .form-input {
            padding-right: calc(var(--spacing-md) + 40px);
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
    
    <!-- Vite JavaScript -->
    @vite(['resources/js/app.js', 'resources/js/auth.js'])
</body>
</html>