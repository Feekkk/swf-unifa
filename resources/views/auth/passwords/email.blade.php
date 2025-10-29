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
                        <path d="M9 12l2 2 4-4"/>
                        <path d="M21 12c-1 0-3-1-3-3s2-3 3-3 3 1 3 3-2 3-3 3"/>
                        <path d="M3 12c1 0 3-1 3-3s-2-3-3-3-3 1-3 3 2 3 3 3"/>
                        <path d="M12 3c0 1-1 3-3 3s-3-2-3-3 1-3 3-3 3 2 3 3"/>
                        <path d="M12 21c0-1 1-3 3-3s3 2 3 3-1 3-3 3-3-2-3-3"/>
                    </svg>
                </div>
                <h1 class="auth-title">Reset Password</h1>
                <p class="auth-subtitle">Enter your email address and we'll send you a password reset link</p>
            </div>
            
            <main id="main-content">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                
                @if ($errors->any())
                    <div class="alert alert-error" role="alert">
                        <ul style="margin: 0; padding-left: var(--spacing-lg);">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <form method="POST" action="{{ route('password.email') }}" class="auth-form" novalidate>
                    @csrf
                    
                    <div class="form-group">
                        <label for="email" class="form-label required">Email Address</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            class="form-input @error('email') error @enderror" 
                            value="{{ old('email') }}" 
                            required 
                            autocomplete="email"
                            placeholder="Enter your registered email address"
                            aria-describedby="email-error"
                            autofocus
                        >
                        @error('email')
                            <span class="form-error" id="email-error" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-large" id="reset-btn">
                        Send Password Reset Link
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
            // Form submission with loading state
            const form = document.querySelector('.auth-form');
            const submitBtn = document.querySelector('#reset-btn');
            
            if (form && submitBtn) {
                form.addEventListener('submit', function() {
                    submitBtn.classList.add('loading');
                    submitBtn.disabled = true;
                    submitBtn.textContent = 'Sending...';
                });
            }
            
            // Real-time email validation
            const emailInput = document.querySelector('#email');
            if (emailInput) {
                emailInput.addEventListener('blur', function() {
                    validateEmail(this);
                });
                
                emailInput.addEventListener('input', function() {
                    if (this.classList.contains('error')) {
                        validateEmail(this);
                    }
                });
            }
            
            function validateEmail(field) {
                const value = field.value.trim();
                let isValid = true;
                let errorMessage = '';
                
                // Remove existing error state
                field.classList.remove('error', 'success');
                const existingError = field.parentNode.querySelector('.form-error:not([id])');
                if (existingError) {
                    existingError.remove();
                }
                
                if (!value) {
                    isValid = false;
                    errorMessage = 'Email address is required';
                } else {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(value)) {
                        isValid = false;
                        errorMessage = 'Please enter a valid email address';
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