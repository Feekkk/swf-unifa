<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="UniKL RCMP Student Financial Aid Registration - Create your account to apply for Student Welfare Fund">
    <title>Register - UniKL RCMP Financial Aid</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    @vite(['resources/css/variables.css', 'resources/css/base.css', 'resources/css/components.css', 'resources/css/layout.css'])
    
    <!-- Custom Registration Styles -->
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
            max-width: 520px;
            position: relative;
            z-index: 1;
        }
        
        .auth-header {
            text-align: center;
            margin-bottom: var(--spacing-xl);
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
        
        /* Multi-step Progress Indicator */
        .step-progress {
            display: flex;
            justify-content: space-between;
            margin-bottom: var(--spacing-2xl);
            position: relative;
        }
        
        .step-progress::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 20px;
            right: 20px;
            height: 2px;
            background: var(--gray-200);
            z-index: 1;
        }
        
        .step-progress-line {
            position: absolute;
            top: 20px;
            left: 20px;
            height: 2px;
            background: var(--primary-color);
            z-index: 2;
            transition: width var(--transition-normal);
        }
        
        .step-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 3;
        }
        
        .step-circle {
            width: 40px;
            height: 40px;
            border-radius: var(--border-radius-full);
            background: var(--gray-200);
            color: var(--gray-500);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: var(--font-weight-semibold);
            font-size: var(--font-size-small);
            transition: all var(--transition-fast);
            margin-bottom: var(--spacing-xs);
        }
        
        .step-item.active .step-circle {
            background: var(--primary-color);
            color: var(--white);
        }
        
        .step-item.completed .step-circle {
            background: var(--success);
            color: var(--white);
        }
        
        .step-label {
            font-size: var(--font-size-xs);
            color: var(--gray-600);
            text-align: center;
            font-weight: var(--font-weight-medium);
        }
        
        .step-item.active .step-label {
            color: var(--primary-color);
        }
        
        /* Form Steps */
        .form-step {
            display: none;
        }
        
        .form-step.active {
            display: block;
        }
        
        .form-step-title {
            font-size: var(--font-size-h4);
            font-weight: var(--font-weight-semibold);
            color: var(--primary-color);
            margin-bottom: var(--spacing-lg);
            text-align: center;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: var(--spacing-md);
            margin-bottom: var(--spacing-lg);
        }
        
        .form-row.single {
            grid-template-columns: 1fr;
        }
        
        .form-group {
            margin-bottom: var(--spacing-lg);
        }
        
        .form-actions {
            display: flex;
            justify-content: space-between;
            gap: var(--spacing-md);
            margin-top: var(--spacing-xl);
        }
        
        .btn-back {
            background: var(--gray-200);
            color: var(--gray-700);
            border-color: var(--gray-200);
        }
        
        .btn-back:hover,
        .btn-back:focus {
            background: var(--gray-300);
            border-color: var(--gray-300);
            color: var(--gray-800);
        }
        
        .auth-footer {
            text-align: center;
            padding-top: var(--spacing-lg);
            border-top: 1px solid var(--gray-200);
            margin-top: var(--spacing-xl);
        }
        
        .auth-footer p {
            color: var(--gray-600);
            margin-bottom: var(--spacing-sm);
        }
        
        .login-link {
            color: var(--primary-color);
            font-weight: var(--font-weight-medium);
            text-decoration: none;
        }
        
        .login-link:hover,
        .login-link:focus {
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
        
        /* Terms and Conditions */
        .terms-section {
            background: var(--gray-50);
            border: 1px solid var(--gray-200);
            border-radius: var(--border-radius-md);
            padding: var(--spacing-lg);
            margin-bottom: var(--spacing-lg);
            max-height: 200px;
            overflow-y: auto;
        }
        
        .terms-section h4 {
            font-size: var(--font-size-h6);
            margin-bottom: var(--spacing-md);
            color: var(--primary-color);
        }
        
        .terms-section p {
            font-size: var(--font-size-small);
            line-height: 1.5;
            margin-bottom: var(--spacing-sm);
        }
        
        .terms-section ul {
            font-size: var(--font-size-small);
            line-height: 1.5;
            margin-bottom: var(--spacing-sm);
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
            
            .form-step-title {
                font-size: var(--font-size-h5-mobile);
            }
            
            .form-row {
                grid-template-columns: 1fr;
                gap: var(--spacing-sm);
            }
            
            .form-actions {
                flex-direction: column;
            }
            
            .step-progress {
                margin-bottom: var(--spacing-xl);
            }
            
            .step-circle {
                width: 32px;
                height: 32px;
                font-size: 12px;
            }
            
            .step-label {
                font-size: 10px;
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
                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="8.5" cy="7" r="4"/>
                        <path d="M20 8v6M23 11l-3 3-3-3"/>
                    </svg>
                </div>
                <h1 class="auth-title">Create Account</h1>
                <p class="auth-subtitle">Join UniKL RCMP Financial Aid System</p>
            </div>
            
            <!-- Progress Indicator -->
            <div class="step-progress">
                <div class="step-progress-line" id="progress-line"></div>
                <div class="step-item active" data-step="1">
                    <div class="step-circle">1</div>
                    <div class="step-label">Personal</div>
                </div>
                <div class="step-item" data-step="2">
                    <div class="step-circle">2</div>
                    <div class="step-label">Contact</div>
                </div>
                <div class="step-item" data-step="3">
                    <div class="step-circle">3</div>
                    <div class="step-label">Academic</div>
                </div>
                <div class="step-item" data-step="4">
                    <div class="step-circle">4</div>
                    <div class="step-label">Security</div>
                </div>
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
                
                <form method="POST" action="{{ route('register') }}" class="auth-form" id="register-form" data-validate novalidate>
                    @csrf
                    
                    <!-- Step 1: Personal Information -->
                    <div class="form-step active" data-step="1">
                        <h2 class="form-step-title">Personal Information</h2>
                        
                        <div class="form-group">
                            <label for="full_name" class="form-label required">Full Name</label>
                            <input 
                                type="text" 
                                id="full_name" 
                                name="full_name" 
                                class="form-input @error('full_name') error @enderror" 
                                value="{{ old('full_name') }}" 
                                required 
                                autocomplete="name"
                                placeholder="Enter your full name as per IC"
                                aria-describedby="full_name-error"
                            >
                            @error('full_name')
                                <span class="form-error" id="full_name-error" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="username" class="form-label required">Username</label>
                            <input 
                                type="text" 
                                id="username" 
                                name="username" 
                                class="form-input @error('username') error @enderror" 
                                value="{{ old('username') }}" 
                                required 
                                autocomplete="username"
                                placeholder="Choose a unique username"
                                aria-describedby="username-error username-help"
                            >
                            <span class="form-help" id="username-help">Username must be 3-20 characters, letters and numbers only</span>
                            @error('username')
                                <span class="form-error" id="username-error" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                        
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
                                placeholder="Enter your email address"
                                aria-describedby="email-error"
                            >
                            @error('email')
                                <span class="form-error" id="email-error" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="bank_name" class="form-label required">Bank Name</label>
                                <select 
                                    id="bank_name" 
                                    name="bank_name" 
                                    class="form-select @error('bank_name') error @enderror" 
                                    required
                                    aria-describedby="bank_name-error"
                                >
                                    <option value="">Select your bank</option>
                                    <option value="maybank" {{ old('bank_name') == 'maybank' ? 'selected' : '' }}>Maybank</option>
                                    <option value="cimb" {{ old('bank_name') == 'cimb' ? 'selected' : '' }}>CIMB Bank</option>
                                    <option value="public_bank" {{ old('bank_name') == 'public_bank' ? 'selected' : '' }}>Public Bank</option>
                                    <option value="rhb" {{ old('bank_name') == 'rhb' ? 'selected' : '' }}>RHB Bank</option>
                                    <option value="hong_leong" {{ old('bank_name') == 'hong_leong' ? 'selected' : '' }}>Hong Leong Bank</option>
                                    <option value="ambank" {{ old('bank_name') == 'ambank' ? 'selected' : '' }}>AmBank</option>
                                    <option value="bsn" {{ old('bank_name') == 'bsn' ? 'selected' : '' }}>Bank Simpanan Nasional</option>
                                    <option value="other" {{ old('bank_name') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('bank_name')
                                    <span class="form-error" id="bank_name-error" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="bank_account_number" class="form-label required">Bank Account Number</label>
                                <input 
                                    type="text" 
                                    id="bank_account_number" 
                                    name="bank_account_number" 
                                    class="form-input @error('bank_account_number') error @enderror" 
                                    value="{{ old('bank_account_number') }}" 
                                    required 
                                    placeholder="Enter account number"
                                    aria-describedby="bank_account_number-error"
                                >
                                @error('bank_account_number')
                                    <span class="form-error" id="bank_account_number-error" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Step 2: Contact Information -->
                    <div class="form-step" data-step="2">
                        <h2 class="form-step-title">Contact Information</h2>
                        
                        <div class="form-group">
                            <label for="phone_number" class="form-label required">Phone Number</label>
                            <input 
                                type="tel" 
                                id="phone_number" 
                                name="phone_number" 
                                class="form-input @error('phone_number') error @enderror" 
                                value="{{ old('phone_number') }}" 
                                required 
                                autocomplete="tel"
                                placeholder="e.g., +60123456789"
                                aria-describedby="phone_number-error phone_number-help"
                            >
                            <span class="form-help" id="phone_number-help">Include country code (e.g., +60 for Malaysia)</span>
                            @error('phone_number')
                                <span class="form-error" id="phone_number-error" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="street_address" class="form-label required">Street Address</label>
                            <input 
                                type="text" 
                                id="street_address" 
                                name="street_address" 
                                class="form-input @error('street_address') error @enderror" 
                                value="{{ old('street_address') }}" 
                                required 
                                autocomplete="street-address"
                                placeholder="Enter your street address"
                                aria-describedby="street_address-error"
                            >
                            @error('street_address')
                                <span class="form-error" id="street_address-error" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="city" class="form-label required">City</label>
                                <input 
                                    type="text" 
                                    id="city" 
                                    name="city" 
                                    class="form-input @error('city') error @enderror" 
                                    value="{{ old('city') }}" 
                                    required 
                                    autocomplete="address-level2"
                                    placeholder="Enter your city"
                                    aria-describedby="city-error"
                                >
                                @error('city')
                                    <span class="form-error" id="city-error" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="state" class="form-label required">State</label>
                                <select 
                                    id="state" 
                                    name="state" 
                                    class="form-select @error('state') error @enderror" 
                                    required
                                    autocomplete="address-level1"
                                    aria-describedby="state-error"
                                >
                                    <option value="">Select state</option>
                                    <option value="johor" {{ old('state') == 'johor' ? 'selected' : '' }}>Johor</option>
                                    <option value="kedah" {{ old('state') == 'kedah' ? 'selected' : '' }}>Kedah</option>
                                    <option value="kelantan" {{ old('state') == 'kelantan' ? 'selected' : '' }}>Kelantan</option>
                                    <option value="melaka" {{ old('state') == 'melaka' ? 'selected' : '' }}>Melaka</option>
                                    <option value="negeri_sembilan" {{ old('state') == 'negeri_sembilan' ? 'selected' : '' }}>Negeri Sembilan</option>
                                    <option value="pahang" {{ old('state') == 'pahang' ? 'selected' : '' }}>Pahang</option>
                                    <option value="perak" {{ old('state') == 'perak' ? 'selected' : '' }}>Perak</option>
                                    <option value="perlis" {{ old('state') == 'perlis' ? 'selected' : '' }}>Perlis</option>
                                    <option value="pulau_pinang" {{ old('state') == 'pulau_pinang' ? 'selected' : '' }}>Pulau Pinang</option>
                                    <option value="sabah" {{ old('state') == 'sabah' ? 'selected' : '' }}>Sabah</option>
                                    <option value="sarawak" {{ old('state') == 'sarawak' ? 'selected' : '' }}>Sarawak</option>
                                    <option value="selangor" {{ old('state') == 'selangor' ? 'selected' : '' }}>Selangor</option>
                                    <option value="terengganu" {{ old('state') == 'terengganu' ? 'selected' : '' }}>Terengganu</option>
                                    <option value="wp_kuala_lumpur" {{ old('state') == 'wp_kuala_lumpur' ? 'selected' : '' }}>WP Kuala Lumpur</option>
                                    <option value="wp_labuan" {{ old('state') == 'wp_labuan' ? 'selected' : '' }}>WP Labuan</option>
                                    <option value="wp_putrajaya" {{ old('state') == 'wp_putrajaya' ? 'selected' : '' }}>WP Putrajaya</option>
                                </select>
                                @error('state')
                                    <span class="form-error" id="state-error" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="postal_code" class="form-label required">Postal Code</label>
                            <input 
                                type="text" 
                                id="postal_code" 
                                name="postal_code" 
                                class="form-input @error('postal_code') error @enderror" 
                                value="{{ old('postal_code') }}" 
                                required 
                                autocomplete="postal-code"
                                placeholder="e.g., 50450"
                                pattern="[0-9]{5}"
                                aria-describedby="postal_code-error postal_code-help"
                            >
                            <span class="form-help" id="postal_code-help">5-digit Malaysian postal code</span>
                            @error('postal_code')
                                <span class="form-error" id="postal_code-error" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Step 3: Academic Information -->
                    <div class="form-step" data-step="3">
                        <h2 class="form-step-title">Academic Information</h2>
                        
                        <div class="form-group">
                            <label for="student_id" class="form-label required">Student ID</label>
                            <input 
                                type="text" 
                                id="student_id" 
                                name="student_id" 
                                class="form-input @error('student_id') error @enderror" 
                                value="{{ old('student_id') }}" 
                                required 
                                placeholder="e.g., RCMP123456"
                                aria-describedby="student_id-error student_id-help"
                            >
                            <span class="form-help" id="student_id-help">Your official UniKL RCMP student ID</span>
                            @error('student_id')
                                <span class="form-error" id="student_id-error" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="course" class="form-label required">Course/Program</label>
                            <select 
                                id="course" 
                                name="course" 
                                class="form-select @error('course') error @enderror" 
                                required
                                aria-describedby="course-error"
                            >
                                <option value="">Select your course</option>
                                <option value="bachelor_medicine" {{ old('course') == 'bachelor_medicine' ? 'selected' : '' }}>Bachelor of Medicine and Bachelor of Surgery (MBBS)</option>
                                <option value="diploma_medical_assistant" {{ old('course') == 'diploma_medical_assistant' ? 'selected' : '' }}>Diploma in Medical Assistant</option>
                                <option value="diploma_nursing" {{ old('course') == 'diploma_nursing' ? 'selected' : '' }}>Diploma in Nursing</option>
                                <option value="diploma_pharmacy" {{ old('course') == 'diploma_pharmacy' ? 'selected' : '' }}>Diploma in Pharmacy</option>
                                <option value="diploma_physiotherapy" {{ old('course') == 'diploma_physiotherapy' ? 'selected' : '' }}>Diploma in Physiotherapy</option>
                                <option value="diploma_medical_imaging" {{ old('course') == 'diploma_medical_imaging' ? 'selected' : '' }}>Diploma in Medical Imaging</option>
                                <option value="diploma_medical_laboratory" {{ old('course') == 'diploma_medical_laboratory' ? 'selected' : '' }}>Diploma in Medical Laboratory Technology</option>
                                <option value="other" {{ old('course') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('course')
                                <span class="form-error" id="course-error" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="semester" class="form-label required">Current Semester</label>
                                <select 
                                    id="semester" 
                                    name="semester" 
                                    class="form-select @error('semester') error @enderror" 
                                    required
                                    aria-describedby="semester-error"
                                >
                                    <option value="">Select semester</option>
                                    <option value="1" {{ old('semester') == '1' ? 'selected' : '' }}>Semester 1</option>
                                    <option value="2" {{ old('semester') == '2' ? 'selected' : '' }}>Semester 2</option>
                                    <option value="3" {{ old('semester') == '3' ? 'selected' : '' }}>Semester 3</option>
                                    <option value="4" {{ old('semester') == '4' ? 'selected' : '' }}>Semester 4</option>
                                    <option value="5" {{ old('semester') == '5' ? 'selected' : '' }}>Semester 5</option>
                                    <option value="6" {{ old('semester') == '6' ? 'selected' : '' }}>Semester 6</option>
                                    <option value="7" {{ old('semester') == '7' ? 'selected' : '' }}>Semester 7</option>
                                    <option value="8" {{ old('semester') == '8' ? 'selected' : '' }}>Semester 8</option>
                                    <option value="9" {{ old('semester') == '9' ? 'selected' : '' }}>Semester 9</option>
                                    <option value="10" {{ old('semester') == '10' ? 'selected' : '' }}>Semester 10</option>
                                </select>
                                @error('semester')
                                    <span class="form-error" id="semester-error" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="year_of_study" class="form-label required">Year of Study</label>
                                <select 
                                    id="year_of_study" 
                                    name="year_of_study" 
                                    class="form-select @error('year_of_study') error @enderror" 
                                    required
                                    aria-describedby="year_of_study-error"
                                >
                                    <option value="">Select year</option>
                                    <option value="1" {{ old('year_of_study') == '1' ? 'selected' : '' }}>Year 1</option>
                                    <option value="2" {{ old('year_of_study') == '2' ? 'selected' : '' }}>Year 2</option>
                                    <option value="3" {{ old('year_of_study') == '3' ? 'selected' : '' }}>Year 3</option>
                                    <option value="4" {{ old('year_of_study') == '4' ? 'selected' : '' }}>Year 4</option>
                                    <option value="5" {{ old('year_of_study') == '5' ? 'selected' : '' }}>Year 5</option>
                                </select>
                                @error('year_of_study')
                                    <span class="form-error" id="year_of_study-error" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Step 4: Security & Terms -->
                    <div class="form-step" data-step="4">
                        <h2 class="form-step-title">Security & Agreement</h2>
                        
                        <div class="form-group">
                            <label for="password" class="form-label required">Password</label>
                            <div class="form-input-group">
                                <input 
                                    type="password" 
                                    id="password" 
                                    name="password" 
                                    class="form-input @error('password') error @enderror" 
                                    required 
                                    autocomplete="new-password"
                                    placeholder="Create a strong password"
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
                            <label for="password_confirmation" class="form-label required">Confirm Password</label>
                            <div class="form-input-group">
                                <input 
                                    type="password" 
                                    id="password_confirmation" 
                                    name="password_confirmation" 
                                    class="form-input @error('password_confirmation') error @enderror" 
                                    required 
                                    autocomplete="new-password"
                                    placeholder="Confirm your password"
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
                        
                        <!-- Terms and Conditions -->
                        <div class="terms-section">
                            <h4>Terms and Conditions</h4>
                            <p><strong>UniKL RCMP Student Welfare Fund (SWF) Registration Agreement</strong></p>
                            <p>By creating an account, you agree to the following terms:</p>
                            <ul>
                                <li>You are a currently enrolled student at UniKL RCMP</li>
                                <li>All information provided is accurate and truthful</li>
                                <li>You understand that false information may result in account suspension</li>
                                <li>You agree to pay the required SWF contribution fees (RM30 for local students, RM50 for international students per semester)</li>
                                <li>You understand that SWF assistance is subject to available funds and committee approval</li>
                                <li>You agree to use this system responsibly and in accordance with university policies</li>
                                <li>Your personal information will be handled according to our Privacy Policy</li>
                            </ul>
                            <p><strong>Privacy Policy:</strong> Your personal information is collected solely for SWF administration purposes and will not be shared with third parties without your consent, except as required by law or university policy.</p>
                        </div>
                        
                        <div class="form-checkbox">
                            <input 
                                type="checkbox" 
                                id="terms_accepted" 
                                name="terms_accepted" 
                                required
                                {{ old('terms_accepted') ? 'checked' : '' }}
                                aria-describedby="terms_accepted-error"
                            >
                            <label for="terms_accepted">
                                I have read and agree to the Terms and Conditions and Privacy Policy
                            </label>
                        </div>
                        @error('terms_accepted')
                            <span class="form-error" id="terms_accepted-error" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <!-- Form Navigation -->
                    <div class="form-actions">
                        <button type="button" class="btn btn-back" id="prev-btn" style="display: none;">
                            Previous
                        </button>
                        <button type="button" class="btn btn-primary" id="next-btn">
                            Next
                        </button>
                        <button type="submit" class="btn btn-primary btn-large" id="submit-btn" style="display: none;">
                            Create Account
                        </button>
                    </div>
                </form>
            </main>
            
            <div class="auth-footer">
                <p>Already have an account?</p>
                <a href="{{ route('login') }}" class="login-link">Sign in here</a>
            </div>
        </div>
    </div>
    
    <!-- JavaScript for Multi-step Form -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let currentStep = 1;
            const totalSteps = 4;
            
            const form = document.getElementById('registration-form');
            const prevBtn = document.getElementById('prev-btn');
            const nextBtn = document.getElementById('next-btn');
            const submitBtn = document.getElementById('submit-btn');
            const progressLine = document.getElementById('progress-line');
            
            // Initialize form
            updateStepDisplay();
            updateProgressLine();
            
            // Next button click
            nextBtn.addEventListener('click', function() {
                if (validateCurrentStep()) {
                    if (currentStep < totalSteps) {
                        currentStep++;
                        updateStepDisplay();
                        updateProgressLine();
                    }
                }
            });
            
            // Previous button click
            prevBtn.addEventListener('click', function() {
                if (currentStep > 1) {
                    currentStep--;
                    updateStepDisplay();
                    updateProgressLine();
                }
            });
            
            // Form submission
            form.addEventListener('submit', function() {
                submitBtn.classList.add('loading');
                submitBtn.disabled = true;
            });
            
            function updateStepDisplay() {
                // Hide all steps
                document.querySelectorAll('.form-step').forEach(step => {
                    step.classList.remove('active');
                });
                
                // Show current step
                document.querySelector(`[data-step="${currentStep}"]`).classList.add('active');
                
                // Update step indicators
                document.querySelectorAll('.step-item').forEach((item, index) => {
                    const stepNumber = index + 1;
                    item.classList.remove('active', 'completed');
                    
                    if (stepNumber < currentStep) {
                        item.classList.add('completed');
                        item.querySelector('.step-circle').innerHTML = '✓';
                    } else if (stepNumber === currentStep) {
                        item.classList.add('active');
                        item.querySelector('.step-circle').innerHTML = stepNumber;
                    } else {
                        item.querySelector('.step-circle').innerHTML = stepNumber;
                    }
                });
                
                // Update navigation buttons
                prevBtn.style.display = currentStep > 1 ? 'block' : 'none';
                nextBtn.style.display = currentStep < totalSteps ? 'block' : 'none';
                submitBtn.style.display = currentStep === totalSteps ? 'block' : 'none';
                
                // Focus first input in current step
                const firstInput = document.querySelector(`[data-step="${currentStep}"] .form-input, [data-step="${currentStep}"] .form-select`);
                if (firstInput) {
                    firstInput.focus();
                }
            }
            
            function updateProgressLine() {
                const progress = ((currentStep - 1) / (totalSteps - 1)) * 100;
                progressLine.style.width = progress + '%';
            }
            
            function validateCurrentStep() {
                const currentStepElement = document.querySelector(`[data-step="${currentStep}"]`);
                const requiredFields = currentStepElement.querySelectorAll('[required]');
                let isValid = true;
                
                requiredFields.forEach(field => {
                    if (!validateField(field)) {
                        isValid = false;
                    }
                });
                
                return isValid;
            }
            
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
                
                // Basic required validation
                if (field.hasAttribute('required') && !value) {
                    isValid = false;
                    errorMessage = 'This field is required';
                } else if (value) {
                    // Field-specific validation
                    switch (fieldName) {
                        case 'email':
                            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                            if (!emailRegex.test(value)) {
                                isValid = false;
                                errorMessage = 'Please enter a valid email address';
                            }
                            break;
                        case 'username':
                            const usernameRegex = /^[a-zA-Z0-9]{3,20}$/;
                            if (!usernameRegex.test(value)) {
                                isValid = false;
                                errorMessage = 'Username must be 3-20 characters, letters and numbers only';
                            }
                            break;
                        case 'phone_number':
                            const phoneRegex = /^\+?[1-9]\d{1,14}$/;
                            if (!phoneRegex.test(value.replace(/\s/g, ''))) {
                                isValid = false;
                                errorMessage = 'Please enter a valid phone number';
                            }
                            break;
                        case 'postal_code':
                            const postalRegex = /^\d{5}$/;
                            if (!postalRegex.test(value)) {
                                isValid = false;
                                errorMessage = 'Postal code must be 5 digits';
                            }
                            break;
                        case 'student_id':
                            if (value.length < 6) {
                                isValid = false;
                                errorMessage = 'Student ID must be at least 6 characters';
                            }
                            break;
                        case 'password':
                            if (value.length < 8) {
                                isValid = false;
                                errorMessage = 'Password must be at least 8 characters';
                            } else {
                                updatePasswordStrength(value);
                            }
                            break;
                        case 'password_confirmation':
                            const password = document.getElementById('password').value;
                            if (value !== password) {
                                isValid = false;
                                errorMessage = 'Passwords do not match';
                            }
                            break;
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
            
            // Real-time validation with AJAX checks
            const inputs = document.querySelectorAll('.form-input, .form-select');
            inputs.forEach(input => {
                input.addEventListener('blur', function() {
                    validateField(this);
                    
                    // AJAX validation for unique fields
                    if (this.name === 'username' || this.name === 'email' || this.name === 'student_id') {
                        checkFieldAvailability(this);
                    }
                });
                
                input.addEventListener('input', function() {
                    if (this.classList.contains('error')) {
                        validateField(this);
                    }
                    
                    if (this.name === 'password') {
                        updatePasswordStrength(this.value);
                    }
                });
            });
            
            // AJAX field availability check
            function checkFieldAvailability(field) {
                const value = field.value.trim();
                if (!value) return;
                
                let endpoint = '';
                switch (field.name) {
                    case 'username':
                        endpoint = '{{ route("check.username") }}';
                        break;
                    case 'email':
                        endpoint = '{{ route("check.email") }}';
                        break;
                    case 'student_id':
                        endpoint = '{{ route("check.student.id") }}';
                        break;
                    default:
                        return;
                }
                
                // Show loading state
                field.classList.add('checking');
                
                fetch(endpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ [field.name]: value })
                })
                .then(response => response.json())
                .then(data => {
                    field.classList.remove('checking');
                    
                    // Remove existing availability messages
                    const existingMsg = field.parentNode.querySelector('.availability-message');
                    if (existingMsg) {
                        existingMsg.remove();
                    }
                    
                    // Add availability message
                    const msgSpan = document.createElement('span');
                    msgSpan.className = `availability-message ${data.available ? 'available' : 'unavailable'}`;
                    msgSpan.textContent = data.message;
                    field.parentNode.appendChild(msgSpan);
                    
                    // Update field state
                    if (data.available) {
                        field.classList.remove('error');
                        field.classList.add('success');
                    } else {
                        field.classList.remove('success');
                        field.classList.add('error');
                    }
                })
                .catch(error => {
                    field.classList.remove('checking');
                    console.error('Availability check failed:', error);
                });
            }
            
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
            
            // Keyboard navigation
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && e.target.matches('.form-input, .form-select')) {
                    e.preventDefault();
                    
                    const currentStepInputs = Array.from(
                        document.querySelectorAll(`[data-step="${currentStep}"] .form-input, [data-step="${currentStep}"] .form-select`)
                    );
                    const currentIndex = currentStepInputs.indexOf(e.target);
                    const nextInput = currentStepInputs[currentIndex + 1];
                    
                    if (nextInput) {
                        nextInput.focus();
                    } else if (currentStep < totalSteps) {
                        nextBtn.click();
                    } else {
                        submitBtn.click();
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
        
        /* Enhanced focus styles */
        .form-input:focus,
        .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(22, 71, 106, 0.1);
        }
        
        .form-input.success,
        .form-select.success {
            border-color: var(--success);
        }
        
        .form-input.success:focus,
        .form-select.success:focus {
            box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.1);
        }
        
        .form-input.error,
        .form-select.error {
            border-color: var(--error);
        }
        
        .form-input.error:focus,
        .form-select.error:focus {
            box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1);
        }
        
        /* Availability checking states */
        .form-input.checking {
            border-color: var(--primary-color);
            background-image: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="%2316476A" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M12 1v6m0 6v6m11-7h-6m-6 0H1"/></svg>');
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 16px;
            animation: spin 1s linear infinite;
        }
        
        .availability-message {
            display: block;
            font-size: var(--font-size-xs);
            margin-top: var(--spacing-xs);
            font-weight: var(--font-weight-medium);
        }
        
        .availability-message.available {
            color: var(--success);
        }
        
        .availability-message.unavailable {
            color: var(--error);
        }
        
        /* Password strength indicator */
        .password-strength {
            height: 4px;
            background: var(--gray-200);
            border-radius: 2px;
            margin-top: var(--spacing-xs);
            overflow: hidden;
        }
        
        .password-strength-bar {
            height: 100%;
            width: 0%;
            transition: all var(--transition-normal);
            border-radius: 2px;
        }
        
        .password-strength-weak .password-strength-bar {
            width: 25%;
            background: var(--error);
        }
        
        .password-strength-fair .password-strength-bar {
            width: 50%;
            background: #ffa500;
        }
        
        .password-strength-good .password-strength-bar {
            width: 75%;
            background: #3498db;
        }
        
        .password-strength-strong .password-strength-bar {
            width: 100%;
            background: var(--success);
        }
    </style>
    
    <!-- Vite JavaScript -->
    @vite(['resources/js/app.js', 'resources/js/auth.js'])
</body>
</html>